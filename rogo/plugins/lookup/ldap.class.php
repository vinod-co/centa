<?php
// This file is part of Rogō
//
// Rogō is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Rogō is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Rogō.  If not, see <http://www.gnu.org/licenses/>.


/**
 *
 * The ldap lookup handler class
 *
 * @author Simon Atack
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */


require_once 'outline_lookup.class.php';

include_once $configObject->get('cfg_web_root') . 'lang/en/include/common.inc';


class ldap_lookup extends outline_lookup {
  public $impliments_api_lookup_version = 1;
  public $version = 0.9;

  function register_callback_routines() {
    $callbackarray[] = array(array($this, 'userlookup'), 'userlookup', $this->number, $this->name);


    return $callbackarray;
  }

  function userlookup($lookupobj) {
    $overrideallset = false;
    $overrideset = false;
    $this->savetodebug('The LDAP userlookup function has been called');
    // if the lookup doesnt have these set and the default for the module configuration exist use them
    if (!isset($lookupobj->settings->override) and isset($this->settings['override'])) {
      foreach ($this->settings['override'] as $key => $value) {
        $lookupobj->settings->override[$key] = $this->settings['override'][$key];
      }
      $overrideset = true;
      $this->savetodebug('Overriding settings as none supplied');
    }
    if (!isset($lookupobj->settings->overrideall) and isset($this->settings['overrideall'])) {
      $lookupobj->settings->overrideall = $this->settings['overrideall'];
      $overrideallset = true;
    }

    extract($this->settings);


    $ldap = ldap_connect($ldap_server);
    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
    if (!ldap_bind($ldap, $ldap_bind_rdn, $ldap_bind_password)) {
      $this->savetodebug('Couldnt Bind to ldap server');
      $this->set_error('Couldnt bind to ldap server');

      return $lookupobj;
    }

    $searchsuccess = false;
    foreach ($lookupobj->searchorder as $keyno => $orderitem) {
      $filter = '';
      $this->savetodebug("search order item no $keyno started");

      if (is_array($orderitem)) {
        $this->savetodebug('search order item is an array');
        $countcheck = 0;
        $countcheck2 = 0;
        $count = count($orderitem);
        $filter = '(&';
        foreach ($orderitem as $item) {
          $this->savetodebug('search order item array contains a search for: ' . $item);
          if (count(array_keys($ldap_attributes, $item)) > 0) {
            //searching item exists in ldap attribute so we can search
            $countcheck++;
            //check if we have any data for this item to actually search for
            if (isset($lookupobj->lookupdata->{$item})) {
              $countcheck2++;
              $filter .= $this->create_filter($ldap_attributes, $item, $lookupobj->lookupdata->{$item});
            }

          }
        }
        $filter .= ')';

        if (!(($countcheck == $countcheck2) and ($countcheck == $count))) {
          $filter = '';
        }

        //multiple filter option use and
      } else {
        $this->savetodebug('search order item is a single item');
        //single filter option
        if (count(array_keys($ldap_attributes, $orderitem)) > 0) {
          //searching item exists in ldap attribute so we can search
          //check if we have any data for this item to actually search for
          if (isset($lookupobj->lookupdata->{$orderitem})) {
            $filter = $this->create_filter($ldap_attributes, $orderitem, $lookupobj->lookupdata->{$orderitem});


          }
        } //else skip as cant search for this as we dont have corresponding attribute
      }

      $this->savetodebug("Using search filter: $filter");
      if (!($search = @ldap_search($ldap, $ldap_search_dn, $filter))) {

        $this->savetodebug('Unknown problem with the ldap search ldap_search_dn:' . $ldap_search_dn . ' filter: ' . $filter);
      } else {
        $info = ldap_get_entries($ldap, $search);
        if ($info['count'] > 0) {
          $searchsuccess = true;
          $count = $info['count'];
          if ($count > 1) {
            $lookupobj->multiple = true;
          }
          $this->savetodebug("Found $count records");
          if (isset($lookupobj->settings->firstentry) and $lookupobj->settings->firstentry == true) {
            //only
            $this->savetodebug('Saving First Entry Only');
            $datablock = $info[0];
            $lookupobj = $this->store_in_data($datablock, $ldap_attributes, $lookupobj);

          } elseif (isset($lookupobj->settings->lastentry) and $lookupobj->settings->lastentry == true) {
            //
            $this->savetodebug('Saving Last Entry Only');
            $datablock = $info[$count - 1];
            $lookupobj = $this->store_in_data($datablock, $ldap_attributes, $lookupobj);
          } else {

            unset($info['count']);
            foreach ($info as $numb => $datablock) {
              $this->savetodebug("Saving Entry #$numb");
              $lookupobj = $this->store_in_data($datablock, $ldap_attributes, $lookupobj);

            }
          }


        } else {
          $this->savetodebug('No records found from search');
        }

      }


      if ($searchsuccess == true) {
        break;
      }
      //end of searchorder loop
    }

    if ($overrideallset == true) {
      unset($lookupobj->settings->overrideall);
    }
    if ($overrideset == true) {
      unset($lookupobj->settings->override);
    }

    return $lookupobj;

  }

  function store_in_data($datablock, $ldap_attributes, $lookupobj) {
    $prepend = '';
    if (isset($this->settings['lowercasecompare']) and $this->settings['lowercasecompare'] == true) {
      $this->savetodebug('Setting ldap_attributes to lowercase');
      foreach ($ldap_attributes as $key => $value) {
        $ldap_attributes[mb_strtolower($key)] = $value;
      }
    }
    if (isset($this->settings['storeprepend'])) {
      $prepend = $this->settings['storeprepend'];
      $this->savetodebug("Setting prepend to $prepend");
    }
    $lookupdatas = new stdClass();
    foreach ($ldap_attributes as $key => $value) {
      $keyorig = $key;
      if (isset($this->settings['lowercasecompare']) and $this->settings['lowercasecompare'] == true) {
        $key = mb_strtolower($key); //think this actually needs to change the datablock without changing the original datablock
      }
      $reverse_attribute = $value;
      if (isset($datablock[$key][0]) and (((isset($lookupobj->lookupdata->$reverse_attribute)) and ((isset($lookupobj->settings->overrideall) and $lookupobj->settings->overrideall == true) or ((isset($lookupobj->settings->override[$key]) and $lookupobj->settings->override[$key] == true) or (isset($lookupobj->settings->override[$reverse_attribute]) and $lookupobj->settings->override[$reverse_attribute] == true)))) or (!isset($lookupobj->lookupdata->$reverse_attribute)))) {
        // store data to lookup if ldap_attribute listed and ( not set or if set and ( overrideall or override value or override inverse ldap se+t))

        $lookupobj->lookupdata->$reverse_attribute = $datablock[$key][0];
        $this->savetodebug("saving value for $reverse_attribute using ldap_attribute: $key");

      }
      if (isset($datablock[$key][0]) and !isset($lookupdatas->$reverse_attribute)) {
        $lookupdatas->$reverse_attribute = $datablock[$key][0];
      }
    }
    $lookupobj->lookupdatas[] = $lookupdatas;

    $datablockstore = array();
    foreach ($datablock as $key => $value) {

      if (!is_int($key)) {

        if (isset($this->settings['lowercasecompare']) and $this->settings['lowercasecompare'] == true) {
          $key = mb_strtolower($key);
        }

        if ((isset($ldap_attributes[$key]))) {
          $gdgdfgdsgds = 1;
        }


        if (((isset($lookupobj->datablockstore[$prepend . $key])) and ((isset($lookupobj->settings->overrideall) and $lookupobj->settings->overrideall == true) or ((isset($lookupobj->settings->override[$key]) and $lookupobj->settings->override[$key] == true)))) or (!isset($lookupobj->datablockstore[$prepend . $key]))) {
          // store data to datablock store if not set or if set and ( overrideall or override value set)
          $lookupobj->datablockstore[$prepend . $key] = $value;
        }

        $datablockstore[$prepend . $key] = $value;
      }


    }
    $lookupobj->datablockstores[] = $datablockstore;

    return $lookupobj;
  }

  function create_filter($ldap_attributes, $reverse_attribute, $value) {
    $filtergen = array_keys($ldap_attributes, $reverse_attribute);

    $filter = '';
    if (count($filtergen) > 1) {

      $filter = '(|';
      foreach ($filtergen as $item) {
        $filter .= '(' . $item . '=' . $value . ')';
      }

      $filter .= ')';
    } else {
      $filter = '(' . $filtergen[0] . '=' . $value . ')';

    }

    return $filter;
  }


}
