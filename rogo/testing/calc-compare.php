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
 * compare phpEval & Rserve maths
 *
 * @author Simon Atack
 * @version 1.0
 * @copyright Copyright (c) 2013 The University of Nottingham
 * @package
 */
set_time_limit(0);
$root = str_replace('/include', '/', str_replace('\\', '/', dirname(__FILE__)));
require_once $root . '/../include/auth.inc';
include_once $root . '/../include/load_config.php';
$cfg_web_root = $configObject->get('cfg_web_root');
require_once $cfg_web_root . 'classes/configobject.class.php';
require_once $cfg_web_root . 'classes/lang.class.php';
require_once $cfg_web_root . 'lang/' . $language . '/include/common.inc'; // Include common language file that all scripts need
require_once $cfg_web_root . 'include/custom_error_handler.inc';
require_once $cfg_web_root . 'classes/dbutils.class.php';
require_once $cfg_web_root . 'classes/userobject.class.php';
require_once $cfg_web_root . 'classes/authentication.class.php';
require_once $cfg_web_root . 'classes/lang.class.php';

require_once $cfg_web_root . '/classes/moduleutils.class.php';
require_once $cfg_web_root . '/classes/schoolutils.class.php';
require_once $cfg_web_root . '/classes/usernotices.class.php';

global $cfg_web_root;
global $cfg_web_root;
require $cfg_web_root . 'plugins/questions/enhancedcalc/enhancedcalc.class.php';

echo "Start";

  $enhancedcalcType = 'phpEval'; // $this->configObj->get('enhancedcalc_type');


  require_once $cfg_web_root . '/plugins/questions/enhancedcalc/' . $enhancedcalcType . '.php';
  $name = 'enhancedcalc_' . $enhancedcalcType;
  $enhancedcalcObj1 = new $name($enhancedcalcType);

  require_once $cfg_web_root . '/plugins/questions/enhancedcalc/' .  'Rrserve.php';
  $enhancedcalcObj2 = new EnhancedCalc_Rrserve($configObject->getbyref('enhancedcalculation'));

$numbers[]=0;
$numbers[]=10;
$numbers[]=1000;

$numbers[]=1000000;
$numbers[]=100000000000;
$numbers[]=1000000000000000;
$numbers[]=0.1;
$numbers[]=0.00001;
$numbers[]=0.000000001;
$numbers[]=0.0000000000001;
$numbers[]=0.000000000000000001;

$numbers[]=3;
$numbers[]=13;
$numbers[]=13450;
$numbers[]=13346346;
$numbers[]=156852353425;
$numbers[]=145745745747475474;
$numbers[]=0.5;
$numbers[]=0.243;
$numbers[]=0.23534654;
$numbers[]=0.45736647895568;
$numbers[]=0.334643672345869654574;

$numbers[]=0;
$numbers[]=135.345;
$numbers[]=235341643.35345235235;
$numbers[]=345254.546723623623623;
$numbers[]=235236236523.233623626236236;
$numbers[]=3462356792.252362362623626;
$numbers[]=234.1;
$numbers[]=3456.00001;
$numbers[]=3453.000000001;
$numbers[]=3485.0000000001;
$numbers[]=2534.0000000001;


print "<table border=1>";

foreach ($numbers as $value) {

$rnd='';
  $disp1='';
  $disp2='';

    for ($rnd = 0; $rnd < 10; $rnd++) {
      print"<tr>";
   $disp1 = $enhancedcalcObj1->calculate_tolerance_percent($value, $rnd);
    $disp2 = $enhancedcalcObj2->calculate_tolerance_percent($value, $rnd);

    //  $disp1=implode(' ',$disp1);
      //$disp2=implode(' ',$disp2);
      $disp3='';
      $disp4='';
      foreach($disp1 as $k =>$v) {
        if(!($disp1[$k] == $disp2[$k])) {
          $disp3.='FAILED ON ' . $k . ' ' . $disp1[$k] . '    '  . $disp2[$k] . '';
        }
      }
      if($disp3=='') {$disp3='GOOD';}
    print "<td>$value</td>";
    print "<td>$rnd</td>";
    print "<td>$disp3</td>";
    print "<td>$disp4</td>";
      print "</tr>";
  }


  ob_flush();
}


print "<table>";
