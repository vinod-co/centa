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
* @author Anthony Brown
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/
require_once $cfg_web_root . 'include/auth.inc';
require_once $cfg_web_root . 'classes/userutils.class.php';
require_once $cfg_web_root . 'classes/moduleutils.class.php';
require_once $cfg_web_root . 'classes/schoolutils.class.php';
require_once $cfg_web_root . 'classes/facultyutils.class.php';
require_once $cfg_web_root . 'classes/question_status.class.php';
require_once $cfg_web_root . 'classes/lang.class.php';
require_once $cfg_web_root . 'classes/configobject.class.php';
require_once $cfg_web_root . 'lang/' . $language . '/include/timezones.inc';
require_once $cfg_web_root . 'lang/' . $language . '/install/index.php';

Class InstallUtils {
  public static $db;
  public static $rogo_path;

  public static $warnings;

  public static $cfg_company;
  public static $cfg_short_date;
  public static $cfg_long_date_time;
  public static $cfg_short_date_time;
  public static $cfg_long_date_php;
  public static $cfg_short_date_php;
  public static $cfg_long_time_php;
  public static $cfg_short_time_php;
  public static $cfg_timezone;
  public static $cfg_tmpdir;
  public static $cfg_tablesorter_date_time;

  //database config options
  public static $cfg_db_host;
  public static $cfg_db_port;
  public static $cfg_db_username;
  public static $cfg_db_password;
  public static $cfg_db_charset;
  public static $cfg_page_charset;

  public static $cfg_web_host;
  public static $cfg_db_basename;
  public static $cfg_db_student_user;
  public static $cfg_db_student_passwd;
  public static $cfg_db_staff_user;
  public static $cfg_db_staff_passwd;
  public static $cfg_db_external_user;
  public static $cfg_db_external_passwd;
  public static $cfg_db_sysadmin_user;
  public static $cfg_db_sysadmin_passwd;
  public static $cfg_db_sct_user;
  public static $cfg_db_sct_passwd;
  public static $cfg_db_inv_user;
  public static $cfg_db_inv_passwd;

  public static $cfg_cron_user;
  public static $cfg_cron_passwd;

  public static $cfg_db_name;
  public static $db_admin_username;
  public static $db_admin_passwd;

  public static $support_email;
  public static $cfg_SysAdmin_username;

  public static $cfg_ldap_server;
  public static $cfg_ldap_search_dn;
  public static $cfg_ldap_bind_rdn;
  public static $cfg_ldap_bind_password;
  public static $cfg_ldap_user_prefix;

  public static $cfg_auth_ldap = 'false';
  public static $cfg_auth_lti = 'true';
  public static $cfg_auth_internal = 'true';
  public static $cfg_auth_guest = 'true';
  public static $cfg_auth_impersonation = 'true';

  public static $cfg_lookup_ldap_server;
  public static $cfg_lookup_ldap_search_dn;
  public static $cfg_lookup_ldap_bind_rdn;
  public static $cfg_lookup_ldap_bind_password;
  public static $cfg_lookup_ldap_user_prefix;

  public static $cfg_uselookupLdap = 'false';
  public static $cfg_uselookupXML = 'false';
  
  public static $cfg_labsecuritytype;
  public static $cfg_interactivequestions;

  public static $cfg_support_email;
  public static $emergency_support_numbers;


  static function displayForm() {
    global $string, $language, $timezone_array;

    ?>
    <script type="text/javascript" src="../js/system_tooltips.js"></script>
    <script>
      $(function () {
        $("#installForm").validate();
      
        $('#useLdap').change(function() {
          $('#ldapOptions').toggle();
        });
      
        $('#uselookupLdap').change(function() {
          $('#ldaplookupOptions').toggle();
        });
      });
    </script>
    <form id="installForm" class="cmxform" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">

      <?php
        if (!defined('PHP_VERSION_ID')) {
          $version = explode('.', PHP_VERSION);
          define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
        }
        if (PHP_VERSION_ID < 50302) {
          echo "<div class=\"warning\"><img src=\"../artwork/small_yellow_warning_icon.gif\" width=\"12\" height=\"11\" alt=\"!\" /> Current PHP version " . phpversion() . " is below recommended version 5.3.2</div>\n";
        }
      ?>
      <table class="h"><tr><td><nobr><?php echo $string['company']; ?></nobr></td><td class="line"><hr /></td></tr></table>
        <div><label for="company_name"><?php echo $string['companyname']; ?></label> <input type="text" id="company_name" name="company_name" value="University of" class="required" minlength="2" /></div>

      <table class="h"><tr><td><nobr><?php echo $string['server']; ?></nobr></td><td class="line"><hr /></td></tr></table>
        <br />
        <div><label for="web_host"><?php echo $string['webhost']; ?></label> <input type="text" value="127.0.0.1" id="web_host" name="web_host" class="required" minlength="3" maxlength="10" /></div>
        <div><label for="tmpdir"><?php echo $string['tempdirectory']; ?></label> <input type="text" id="tmpdir" name="tmpdir" value="/tmp/" /></div>
        <div style="clear: left"><label for="page_charset"><?php echo $string['pagecharset']; ?></label> <select id="page_charset" name="page_charset"><option value="UTF-8">UTF-8</option><option value="ISO-8859-1">ISO 8859-1</option></select></div>

      <table class="h"><tr><td><nobr><?php echo $string['databaseadminuser']; ?></nobr></td><td class="line"><hr /></td></tr></table>
        <div><?php echo $string['needusername']; ?></div>
        <br />
        <div><label for="mysql_admin_user"><?php echo $string['dbusername']; ?></label> <input type="text" value="" id="mysql_admin_user" name="mysql_admin_user" class="required" minlength="2" /></div>
        <div><label for="mysql_admin_pass"><?php echo $string['dbpassword']; ?></label> <input type="password" value="" id="mysql_admin_pass" name="mysql_admin_pass"/></div>

        <table class="h"><tr><td><nobr><?php echo $string['databasesetup']; ?></nobr></td><td class="line"><hr /></td></tr></table>
        <br />
        <div><label for="mysql_db_host"><?php echo $string['databasehost']; ?></label> <input type="text" value="127.0.0.1" id="mysql_db_host" name="mysql_db_host" class="required" /></div>
        <div><label for="mysql_db_port"><?php echo $string['databaseport']; ?></label> <input type="text" value="3306" id="mysql_db_port" name="mysql_db_port" class="required" /></div>
        <div><label for="mysql_db_name"><?php echo $string['databasename']; ?></label> <input type="text" value="rogo" id="mysql_db_name" name="mysql_db_name" class="required" minlength="3" /></div>
        <div><label for="mysql_db_charset"><?php echo $string['databasecharset']; ?></label> <select id="mysql_db_charset" name="mysql_db_charset"><option value="utf8">UTF-8</option><option value="latin1">latin1</option></select></div>

        <div><label for="mysql_baseusername"><?php echo $string['rdbbasename']; ?></label> <input type="text" value="rogo" id="mysql_baseusername" name="mysql_baseusername" class="required" minlength="3" maxlength="10" /></div>

      <table class="h"><tr><td><nobr><?php echo $string['timedateformats']; ?></nobr></td><td class="line"><hr /></td></tr></table>
<?php
$mysql_date_url = 'http://dev.mysql.com/doc/refman/5.1/en/date-and-time-functions.html#function_date-format';
$php_date_url = 'http://www.php.net/manual/en/function.date.php';
?>
        <div><label for="cfg_short_date"><?php echo sprintf($string['date'], '<a href="' . $mysql_date_url . '" target="_blank">MySQL</a>'); ?></label> <input type="text" id="cfg_short_date" name="cfg_short_date" class="required" minlength="2" value="%d/%m/%y" /></div>
        <div><label for="cfg_long_date_time"><?php echo sprintf($string['longdatetime'], '<a href="' . $mysql_date_url . '" target="_blank">MySQL</a>'); ?></label> <input type="text" id="cfg_long_date_time" name="cfg_long_date_time" class="required" value="%d/%m/%Y %H:%i" /></div>
        <div><label for="cfg_short_date_time"><?php echo sprintf($string['shortdatetime'], '<a href="' . $mysql_date_url . '" target="_blank">MySQL</a>'); ?></label> <input type="text" id="cfg_short_date_time" name="cfg_short_date_time" class="required" value="%d/%m/%y %H:%i" /></div>
        <div><label for="cfg_long_date_php"><?php echo sprintf($string['longdatephp'], '<a href="' . $php_date_url . '" target="_blank">PHP</a>'); ?></label> <input type="text" id="cfg_long_date_php" name="cfg_long_date_php" class="required" value="d/m/Y" /></div>
        <div><label for="cfg_short_date_php"><?php echo sprintf($string['shortdatephp'], '<a href="' . $php_date_url . '" target="_blank">PHP</a>'); ?></label> <input type="text" id="cfg_short_date_php" name="cfg_short_date_php" class="required" value="d/m/y" /></div>
        <div><label for="cfg_long_time_php"><?php echo sprintf($string['longtimephp'], '<a href="' . $php_date_url . '" target="_blank">PHP</a>'); ?></label> <input type="text" id="cfg_long_time_php" name="cfg_long_time_php" class="required" value="H:i:s" /></div>
        <div><label for="cfg_short_time_php"><?php echo sprintf($string['shorttimephp'], '<a href="' . $php_date_url . '" target="_blank">PHP</a>'); ?></label> <input type="text" id="cfg_short_time_php" name="cfg_short_time_php" class="required" value="H:i" /></div>
        <div><label for="cfg_timezone"><?php echo $string['currenttimezone']; ?></label> <select id="cfg_timezone" name="cfg_timezone">
        <?php
          $default_timezone = date_default_timezone_get();
          if ($default_timezone == 'UTC') $default_timezone = 'Europe/London';
          foreach ($timezone_array as $individual_zone => $display_zone) {
            if ($individual_zone == $default_timezone) {
              echo "<option value=\"$individual_zone\" selected>$display_zone</option>";
            } else {
              echo "<option value=\"$individual_zone\">$display_zone</option>";
            }
          }
        ?>
        </select></div>

        <table class="h"><tr><td><nobr><?php echo $string['authentication']; ?></nobr></td><td class="line"><hr /></td></tr></table>
        <div><label for="useLti"><?php echo $string['allowlti']; ?></label><input id="useLti" name="useLti" type="checkbox" checked="checked" /><img src="../artwork/tooltip_icon.gif" class="help_tip" title="Allow authentication from successful LTI launch" /></div><br />
        <div><label for="useInternal"><?php echo $string['allowintdb']; ?></label><input id="useInternal" name="useInternal" type="checkbox" checked="checked" /><img src="../artwork/tooltip_icon.gif" class="help_tip" title="Allow authentication from internal Rogo user database" /></div><br />
        <div><label for="useGuest"><?php echo $string['allowguest']; ?></label><input id="useGuest" name="useGuest" type="checkbox" checked="checked" /><img src="../artwork/tooltip_icon.gif" class="help_tip" title="Allow guest temporary accouts for students who forget their normal log in details" /></div><br /><br />
        <div><label for="useImpersonation"><?php echo $string['allowimpersonation']; ?></label><input id="useImpersonation" name="useImpersonation" type="checkbox" checked="checked" /><img src="../artwork/tooltip_icon.gif" class="help_tip" title="Allow SysAdmin users to impersonate other users" /></div><br clear="all" /><br />
        <div><label for="useLdap"><?php echo $string['useldap']; ?></label><input id="useLdap" name="useLdap" type="checkbox" /></div>
        <div id="ldapOptions" style="display:none">
          <br/>
          <div><label for="ldap_server"><?php echo $string['ldapserver']; ?></label> <input type="text" value="" id="ldap_server" name="ldap_server" /></div>
          <div><label for="ldap_search_dn"><?php echo $string['searchdn']; ?></label> <input type="text" value="" id="ldap_search_dn" name="ldap_search_dn" /></div>
          <div><label for="ldap_bind_rdn"><?php echo $string['bindusername']; ?></label> <input type="text" value="" id="ldap_bind_rdn" name="ldap_bind_rdn" /></div>
          <div><label for="ldap_bind_password"><?php echo $string['bindpassword']; ?></label> <input type="password" value="" id="ldap_bind_password" name="ldap_bind_password" /></div>
          <div><label for="ldap_user_prefix"><?php echo $string['userprefix']; ?></label> <input type="text" value="" id="ldap_user_prefix" name="ldap_user_prefix" /> <img src="../artwork/tooltip_icon.gif" class="help_tip" title="<?php echo $string['userprefixtip'] ?>" /></div>
        </div>


        <table class="h"><tr><td><nobr><?php echo $string['lookup']; ?></nobr></td><td class="line"><hr /></td></tr></table>


        <div><label for="uselookupLdap"><?php echo $string['useldap']; ?></label><input id="uselookupLdap" name="uselookupLdap" type="checkbox" /></div>
        <div id="ldaplookupOptions" style="display:none;">
            <br/>
            <div><label for="ldap_lookup_server"><?php echo $string['ldapserver']; ?></label> <input type="text" value="" id="ldap_lookup_server" name="ldap_lookup_server" /></div>
            <div><label for="ldap_lookup_search_dn"><?php echo $string['searchdn']; ?></label> <input type="text" value="" id="ldap_lookup_search_dn" name="ldap_lookup_search_dn" /></div>
            <div><label for="ldap_lookup_bind_rdn"><?php echo $string['bindusername']; ?></label> <input type="text" value="" id="ldap_lookup_bind_rdn" name="ldap_lookup_bind_rdn" /></div>
            <div><label for="ldap_lookup_bind_password"><?php echo $string['bindpassword']; ?></label> <input type="password" value="" id="ldap_lookup_bind_password" name="ldap_lookup_bind_password" /></div>
            <div><label for="ldap_lookup_user_prefix"><?php echo $string['userprefix']; ?></label> <input type="text" value="" id="ldap_lookup_user_prefix" name="ldap_lookup_user_prefix" /> <img src="../artwork/tooltip_icon.gif" class="help_tip" title="<?php echo $string['userprefixtip'] ?>" /></div>
        </div><br clear="all" />
        <div><label for="uselookupXML"><?php echo $string['allowlookupXML']; ?></label><input id="uselookupXML" name="uselookupXML" type="checkbox" /><img src="../artwork/tooltip_icon.gif" class="help_tip" title="Allow guest temporary accouts for students who forget their normal log in details" /></div><br clear="all" /><br />


        <table class="h"><tr><td><nobr><?php echo $string['sysadminuser']; ?></nobr></td><td class="line"><hr /></td></tr></table>
        <div><?php echo $string['initialsysadmin']; ?></div>
        <br />
        <div><label for="SysAdmin_title"><?php echo $string['title']; ?></label>
          <select id="SysAdmin_title" name="SysAdmin_title" class="required">
		<?php
		  if ($language != 'en') {
		    echo "<option value=\"\"></option>\n";
		  }
		  $titles = explode(',', $string['title_types']);
		  foreach ($titles as $tmp_title) {
		    echo "<option value=\"$tmp_title\" selected>$tmp_title</option>";
		  }
		  ?>
          </select>
        </div>
        <div><label for="SysAdmin_first"><?php echo $string['firstname']; ?></label> <input type="text" value="" name="SysAdmin_first" id="SysAdmin_first" class="required" /> </div>
        <div><label for="SysAdmin_last"><?php echo $string['surname']; ?></label> <input type="text" value="" id="SysAdmin_last" name="SysAdmin_last" class="required" minlength="3" /> </div>
        <div><label for="SysAdmin_email"><?php echo $string['emailaddress']; ?></label> <input type="text" value="" id="SysAdmin_email" name="SysAdmin_email" class="required email" /></div>
        <div><label for="SysAdmin_username"><?php echo $string['username']; ?></label> <input type="text" value="" id="SysAdmin_username" name="SysAdmin_username" class="required" minlength="3" /></div>
        <div><label for="SysAdmin_password"><?php echo $string['password']; ?></label> <input type="password" value="" id="SysAdmin_password" name="SysAdmin_password" class="required" minlength="8" /></div>

      <table class="h"><tr><td><nobr><?php echo $string['helpdb']; ?></nobr></td><td class="line"><hr /></td></tr></table>
        <div><label for="loadHelp"><?php echo $string['loadhelp']; ?></label> <input id="loadHelp" name="loadHelp" type="checkbox" checked="checked" /></div>
        
      <table class="h"><tr><td><nobr><?php echo $string['interactivequestions']; ?></nobr></td><td class="line"><hr /></td></tr></table>
        <div><label><?php echo $string['flash']; ?></label> <input name="interactivequestions" value="flash" type="radio"/><img src="../artwork/tooltip_icon.gif" class="help_tip" title="Adobe Flash is best for backwards browser compatibility but will be deprecated in future versions.  HTML5 is best for future proofing and works in IE9, Firefox 23, chrome 28.0 and Safari 5.1 and above" /></div>
        <div><label><?php echo $string['html5']; ?></label> <input name="interactivequestions" type="radio" value="html5" checked = "checked"/></div>
        
      <table class="h"><tr><td><nobr><?php echo $string['labsecuritytype']; ?></nobr></td><td class="line"><hr /></td></tr></table>
        <div><label><?php echo $string['IP']; ?></label> <input name="labsecuritytype" value="ipaddress" type="radio" checked = "checked" /><img src="../artwork/tooltip_icon.gif" class="help_tip" title="Rogo can lock summative exams to either IP address or hostname. If your institution uses static IPs then chose IP address otherwise chose hostname. " /></div>
        <div><label><?php echo $string['hostname']; ?></label> <input name="labsecuritytype" type="radio" value="hostname" /></div>
      
      <table class="h"><tr><td><nobr><?php echo $string['supportemaila']; ?></nobr></td><td class="line"><hr /></td></tr></table>
        <div></div>
        <br />
        <div><label for="support_email"><?php echo $string['supportemail']; ?></label> <input type="text" value="" id="support_email" name="support_email" class="" class="email" /> </div>

      <table class="h"><tr><td><nobr><?php echo $string['supportnumbers']; ?></nobr></td><td class="line"><hr /></td></tr></table>
        <div><label for="emergency_support1"><?php echo $string['name']; ?></label> <input type="text" value="" id="emergency_support1" name="emergency_support1" class="" /> <?php echo $string['number']; ?> <input type="text" value="" name="emergency_support_number1" class="" /></div>
        <div><label for="emergency_support2"><?php echo $string['name']; ?></label> <input type="text" value="" id="emergency_support2" name="emergency_support2" class="" /> <?php echo $string['number']; ?> <input type="text" value="" name="emergency_support_number2" class="" /></div>
        <div><label for="emergency_support3"><?php echo $string['name']; ?></label> <input type="text" value="" id="emergency_support3" name="emergency_support3" class="" /> <?php echo $string['number']; ?> <input type="text" value="" name="emergency_support_number3" class="" /></div>

      <div class="submit"> <input type="submit" name="install" value="<?php echo $string['install']; ?>" class="ok" /> </div>
    </form>
    <?php
  }

  /**
   * Determines if a database user already exists.
   *
   * @param string $username - The name of the user to be tested.
   *
   * @return bool - True = user exists, False = user does not exist.
   */
  static function does_user_exist($username) {
    $result  = self::$db->prepare('SELECT User FROM mysql.user WHERE user = ?');
    $result->bind_param('s', $username);
    $result->execute();
    $result->store_result();
    $num_rows =  $result->num_rows;

    $result->close();

    if ($num_rows < 1) {
      return false;
    }

    return true;    
  }
  
  static function processForm() {
    global $string, $cfg_encrypt_salt;

    self::$cfg_company = $_POST['company_name'];
    //check admin database user name and password and create the connection
    self::$cfg_db_host = $_POST['mysql_db_host'];
    self::$cfg_db_charset = $_POST['mysql_db_charset'];
    self::$cfg_page_charset = $_POST['page_charset'];
    self::$cfg_db_port = $_POST['mysql_db_port'];
    self::$cfg_db_name = $_POST['mysql_db_name'];
    self::$db_admin_username = $_POST['mysql_admin_user'];
    self::$db_admin_passwd = $_POST['mysql_admin_pass'];

    self::$cfg_web_host = $_POST['web_host'];


    self::$cfg_db_basename = $_POST['mysql_baseusername'];

    self::$cfg_SysAdmin_username = $_POST['SysAdmin_username'];

    self::$cfg_short_date = $_POST['cfg_short_date'];
    self::$cfg_long_date_time = $_POST['cfg_long_date_time'];
    self::$cfg_short_date_time = $_POST['cfg_short_date_time'];
    self::$cfg_long_date_php = $_POST['cfg_long_date_php'];
    self::$cfg_short_date_php = $_POST['cfg_short_date_php'];
    self::$cfg_long_time_php = $_POST['cfg_long_time_php'];
    self::$cfg_short_time_php = $_POST['cfg_short_time_php'];
    self::$cfg_timezone = $_POST['cfg_timezone'];
    self::$cfg_tmpdir = $_POST['tmpdir'];
    if (self::$cfg_long_date_time == "%d/%m/%Y %H:%i") {
      self::$cfg_tablesorter_date_time = 'uk';
    } else {
      self::$cfg_tablesorter_date_time = 'us';
    }
    //Authentication
    if (isset($_POST['useLti'])) {
      self::$cfg_auth_lti = true;
    } else {
      self::$cfg_auth_lti = false;
    }
    if (isset($_POST['useInternal'])) {
      self::$cfg_auth_internal = true;
    } else {
      self::$cfg_auth_internal = false;
    }
    if (isset($_POST['useGuest'])) {
      self::$cfg_auth_guest = true;
    } else {
      self::$cfg_auth_guest = false;
    }
    if (isset($_POST['useImpersonation'])) {
      self::$cfg_auth_impersonation = true;
    } else {
      self::$cfg_auth_impersonation = false;
    }
    if (isset($_POST['useLdap'])) {
      self::$cfg_auth_ldap = true;
    } else {
      self::$cfg_auth_ldap = false;
    }


    //LDAP
    self::$cfg_ldap_server = $_POST['ldap_server'];
    self::$cfg_ldap_search_dn = $_POST['ldap_search_dn'];
    self::$cfg_ldap_bind_rdn = $_POST['ldap_bind_rdn'];
    self::$cfg_ldap_bind_password = $_POST['ldap_bind_password'];
    if (self::$cfg_ldap_server != '') {
      self::$cfg_auth_ldap = true;
    } else {
      self::$cfg_auth_ldap = false;
    }
    self::$cfg_ldap_user_prefix = $_POST['ldap_user_prefix'];

    //LDAP for lookup
    self::$cfg_lookup_ldap_server = $_POST['ldap_lookup_server'];
    self::$cfg_lookup_ldap_search_dn = $_POST['ldap_lookup_search_dn'];
    self::$cfg_lookup_ldap_bind_rdn = $_POST['ldap_lookup_bind_rdn'];
    self::$cfg_lookup_ldap_bind_password = $_POST['ldap_lookup_bind_password'];
    self::$cfg_lookup_ldap_user_prefix = $_POST['ldap_lookup_user_prefix'];

    //ASSISTANCE
    self::$cfg_support_email = $_POST['support_email'];
    self::$emergency_support_numbers = 'array(';
    for ($i = 1; $i<=3; $i++) {
      if ($_POST["emergency_support$i"] != '') {
        self::$emergency_support_numbers .= "'" . $_POST["emergency_support$i"] . "'=>'" . $_POST["emergency_support_number$i"] . "', ";
      }
    }
    self::$emergency_support_numbers = rtrim(self::$emergency_support_numbers, ', ');
    self::$emergency_support_numbers .= ')';
    
    
    //Other settings 
    self::$cfg_labsecuritytype = $_POST['labsecuritytype'];
    self::$cfg_interactivequestions = $_POST['interactivequestions'];
  
    // Check we can write to the config file first if not passwords will be lost!
    $rogo_path = str_ireplace('/install/index.php','', normalise_path($_SERVER['SCRIPT_FILENAME']));

    if (file_exists($rogo_path . '/config/config.inc.php')) {
      if (!is_writable($rogo_path . '/config/config.inc.php')) {
        self::displayError(array(300=>'Could not write config file!'));
      }
    } elseif (!is_writable($rogo_path . '/config')) {
      self::displayError(array(300=>'Could not write config file!'));
    }

    //CREATE and populate DB
    self::$db = new mysqli(self::$cfg_db_host, self::$db_admin_username, self::$db_admin_passwd, '', self::$cfg_db_port);

    if (mysqli_connect_error()) {
      self::displayError(array('001' => mysqli_connect_error()));
    }
    self::$db->set_charset(self::$cfg_db_charset);

    //create salt as this is needed to generate the passwords that are created in the next function rather than created during config file settings
    $salt = '';
    $characters = 'abcdefghijklmnopqrstuvwxzyABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    for ($i=0; $i<16; $i++) {
      $salt .= substr($characters, rand(0,61), 1);
    }
    $cfg_encrypt_salt = $salt;

    $configObj = Config::get_instance();

    $authentication = array(
      array('internaldb', array('table' => '', 'username_col' => '', 'passwd_col' => '', 'id_col' => '', 'sql_extra' => '', 'encrypt' => 'SHA-512', 'encrypt_salt' => $cfg_encrypt_salt), 'Internal Database')
    );
    $configObj->set('authentication', $authentication);
    
    InstallUtils::checkDBUsers();


    self::createDatabase(self::$cfg_db_name, self::$cfg_db_charset);

    //LOAD help if requested
    if (isset($_POST['loadHelp'])) {
      self::loadHelp();
    }

    //Write out the config file
    self::writeConfigFile();
    if (!is_array(self::$warnings)) {
      echo "<p style=\"margin-left:10px\">" . $string['installed'] . "</p>\n";
      echo "<p style=\"margin-left:10px\">" . $string['deleteinstall'] . "</p>\n";
      echo "<p style=\"margin-left:10px\"><input type=\"button\" class=\"ok\" name=\"home\" value=\"" . $string['staffhomepage'] . "\" onclick=\"window.location='../index.php'\" /></p>\n";
    } else {
      self::displayWarnings();
    }
  }

  /**
  * Load the Help databases
  *
  */
  static function loadHelp() {
    global $string;
    $staff_help = './staff_help.sql';
    $student_help = './student_help.sql';

    //make sure we are using the right DB
    self::$db->select_db(self::$cfg_db_name);

    self::$db->autocommit(false);
    if (file_exists($staff_help)) {
      $query = file_get_contents($staff_help);
      self::$db->query("TRUNCATE staff_help");


      self::$db->multi_query($query);
      if (self::$db->error) {
        try {
          throw new Exception("MySQL error " . self::$db->error . " <br /> Query:<br /> ", self::$db->errno);
        } catch (Exception $e) {
          echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br />";
          echo nl2br($e->getTraceAsString());
        }
        self::$db->rollback();
      }

      if (self::$db->errno != 0) {
        self::logWarning(array('501' => $string['logwarning1'] . self::$db->error));
        $ext = '';
      }
      while (self::$db->more_results()) {
        self::$db->next_result();
        if (self::$db->error) {
          try {
            throw new Exception("MySQL error " . self::$db->error . " <br /> Query:<br /> ", self::$db->errno);
          } catch (Exception $e) {
            echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br />";
            echo nl2br($e->getTraceAsString());
          }
          self::$db->rollback();
        }
      }
    } else {
      self::logWarning(array('502' => $string['logwarning2']));
    }
    self::$db->commit();

    if (file_exists($student_help)) {
      $query = file_get_contents($student_help);
      self::$db->query("TRUNCATE student_help");

      self::$db->multi_query($query);
      if (self::$db->error) {
        try {
          throw new Exception("MySQL error " . self::$db->error . " <br /> Query:<br /> ", self::$db->errno);
        } catch (Exception $e) {
          echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br />";
          echo nl2br($e->getTraceAsString());
        }
        self::$db->rollback();
      }
      if (self::$db->errno != 0) {
        self::logWarning(array('503' => $string['logwarning3'] . self::$db->error));
        $ext = '';
        while (self::$db->more_results()) {
          self::$db->next_result();
          if (self::$db->error) {
            try {
              throw new Exception("MySQL error " . self::$db->error . " <br /> Query:<br /> ", self::$db->errno);
            } catch (Exception $e) {
              echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br />";
              echo nl2br($e->getTraceAsString());
            }
            self::$db->rollback();
          }
        }
      }
    } else {
      self::logWarning(array('504' => $string['logwarning4']));
    }
    self::$db->commit();
    self::$db->autocommit(true);
  }

  /**
  * create the database and users if they do not exist
  *
  */
  static function createDatabase($dbname, $dbcharset) {
    global $string;
    $res = self::$db->prepare("SHOW DATABASES LIKE '$dbname'");
    $res->execute();
    $res->store_result();
    @ob_flush();
    @flush();
    if ($res->num_rows > 0) {
      self::displayError(array('010' => sprintf($string['displayerror1'],$dbname)));
    }
    $res->close();

    switch ($dbcharset) {
      case 'utf8':
        $collation = 'utf8_general_ci';
        break;
      default:
        $collation = 'latin1_swedish_ci';
    }

    self::$db->query("CREATE DATABASE $dbname CHARACTER SET = $dbcharset COLLATE = $collation"); //have to use query here oldvers of php throw an error
    if (self::$db->errno != 0) {
      self::displayError(array('011' => $string['displayerror2']));
    }

    //select the newly created database
    self::$db->change_user(self::$db_admin_username, self::$db_admin_passwd,self::$cfg_db_name);

    //create tables
    $tables = new databaseTables($dbcharset);
    self::$db->autocommit(false);
    while ($sql = $tables->next()) {
      $res = self::$db->query($sql);
      @ob_flush();
      @flush();
      if (self::$db->errno != 0) {
        self::displayError(array('012' => $string['displayerror3'] . self::$db->error . "<br /> $sql"));
        try {
          $err=self::$db->error;
          $mess=self::$db->errno;
          throw new Exception("MySQL error $err", $mess);
        } catch (Exception $e) {
          echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br />";
        }
        self::$db->rollback();
      }
    }
   self::$db->commit();


    self::$cfg_db_username = self::$cfg_db_basename . '_auth';
    self::$cfg_db_password = gen_password() . gen_password();

    self::$cfg_db_student_user = self::$cfg_db_basename . '_stu';
    self::$cfg_db_student_passwd = gen_password() . gen_password();
    self::$cfg_db_staff_user = self::$cfg_db_basename . '_staff';
    self::$cfg_db_staff_passwd = gen_password() . gen_password();
    self::$cfg_db_external_user = self::$cfg_db_basename . '_ext';
    self::$cfg_db_external_passwd  = gen_password() . gen_password();
    self::$cfg_db_sysadmin_user = self::$cfg_db_basename . '_sys';
    self::$cfg_db_sysadmin_passwd = gen_password() . gen_password();
    self::$cfg_db_sct_user = self::$cfg_db_basename . '_sct';
    self::$cfg_db_sct_passwd = gen_password() . gen_password();
    self::$cfg_db_inv_user = self::$cfg_db_basename . '_inv';
    self::$cfg_db_inv_passwd = gen_password() . gen_password();

    self::$cfg_cron_user = 'cron';
    self::$cfg_cron_passwd = gen_password() . gen_password();

    $priv_SQL = array();
    //create 'database user authentication user' and grant permissions
    self::$db->query("CREATE USER '" . self::$cfg_db_username . "'@'". self::$cfg_web_host . "' IDENTIFIED BY '" . self::$cfg_db_password . "'");
    if (self::$db->errno != 0) {
      self::displayError(array('013'=> $string['wdatabaseuser'] . self::$cfg_db_username . $string['wnotcreated'] . ' ' . self::$db->error ));
    }
    //$priv_SQL[] = "REVOKE ALL PRIVILEGES ON $dbname.* FROM '". self::$cfg_db_username . "'@'" . self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".admin_access TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT ON " . $dbname . ".courses TO '" . self::$cfg_db_username . "'@'" . self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".client_identifiers TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".labs TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".lti_keys TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".lti_user TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".modules_student TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".paper_metadata_security TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, UPDATE, INSERT, DELETE ON " . $dbname . ".password_tokens TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".properties TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".schools TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT ON " . $dbname . ".sid TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".special_needs TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT INSERT ON " . $dbname . ".sys_errors TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT,INSERT ON " . $dbname . ".temp_users TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".users TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".users_metadata TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT INSERT ON " . $dbname . ".denied_log TO '". self::$cfg_db_username . "'@'". self::$cfg_web_host . "'";

    $priv_SQL[] = "FLUSH PRIVILEGES";

    foreach($priv_SQL as $sql) {
      self::$db->query($sql);
      @ob_flush();
      @flush();
      if (self::$db->errno != 0) {
        self::displayError(array('013'=> $string['wdatabaseuser'] . self::$cfg_db_username . $string['wnotpermission'] . ' ' . self::$db->error ));
        self::$db->rollback();
      }
    }
   self::$db->commit();


    $priv_SQL = array();
    //create 'database user student user' and grant permissions
    self::$db->query("CREATE USER  '" . self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "' IDENTIFIED BY '" . self::$cfg_db_student_passwd . "'");
    if (self::$db->errno != 0) {
      self::displayError(array('013'=> $string['wdatabaseuser'] . self::$cfg_db_student_user . $string['wnotcreated'] . ' ' . self::$db->error ));
    }
   //$priv_SQL[] = "REVOKE ALL PRIVILEGES ON $dbname.* FROM '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".announcements TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".cache_median_question_marks TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".cache_paper_stats TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".cache_student_paper_marks TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".exam_announcements TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".feedback_release TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".help_log TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".help_searches TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".help_tutorial_log TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".client_identifiers TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".keywords_question TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".labs TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log0 TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log1 TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log2 TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log3 TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log4 TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log4_overall TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log5 TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log6 TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".log_extra_time TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".log_lab_end_time TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log_late TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log_metadata TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".lti_resource TO '". self::$cfg_db_student_user . "'@'".self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".lti_context TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".marking_override TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".modules TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT ON " . $dbname . ".modules_student TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".objectives TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".options TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".paper_feedback TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".paper_metadata_security TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".papers TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".properties TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".properties_modules TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".questions TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".question_exclude TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".question_statuses TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".reference_material TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".reference_modules TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".reference_papers TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".relationships TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".schools TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".sid TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".sessions TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".std_set TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".std_set_questions TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".state TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".student_help TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".special_needs TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT INSERT ON " . $dbname . ".sys_errors TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".temp_users TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".users TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".users_metadata TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT INSERT ON " . $dbname . ".access_log TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT INSERT ON " . $dbname . ".denied_log TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
		$priv_SQL[] = "GRANT SELECT ON " . $dbname . ".killer_questions TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
		$priv_SQL[] = "GRANT INSERT ON " . $dbname . ".save_fail_log TO '". self::$cfg_db_student_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "FLUSH PRIVILEGES";

    foreach ($priv_SQL as $sql) {
      self::$db->query($sql);
      @ob_flush();
      @flush();
      if (self::$db->errno != 0) {
        self::displayError(array('013'=> $string['wdatabaseuser'] . self::$cfg_db_student_user . $string['wnotpermission'] . ' ' . self::$db->error ));
        self::$db->rollback();
      }
    }
   self::$db->commit();
    $priv_SQL = array();
    //create 'database user external user' and grant permissions
    self::$db->query("CREATE USER  '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "' IDENTIFIED BY '" . self::$cfg_db_external_passwd . "'");
    if (self::$db->errno != 0) {
      self::displayError(array('013'=> $string['wdatabaseuser'] . self::$cfg_db_external_user . $string['wnotcreated'] . ' ' . self::$db->error ));
    }
    //$priv_SQL[] = "REVOKE ALL PRIVILEGES ON $dbname.* FROM '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT ON " . $dbname . ".help_log TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT ON " . $dbname . ".help_searches TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".keywords_question TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log0 TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log1 TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log2 TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log3 TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log4 TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log4_overall TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log5 TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log_late TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log_metadata TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".modules TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".modules_staff TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".options TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".papers TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".properties TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".questions TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".question_statuses TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".reference_material TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".reference_modules TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".reference_papers TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".review_comments TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".review_metadata TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".special_needs TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".std_set TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".std_set_questions TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".staff_help TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".student_help TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT INSERT ON " . $dbname . ".sys_errors TO '" . self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".users TO '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT INSERT ON " . $dbname . ".access_log TO '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT INSERT ON " . $dbname . ".denied_log TO '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".properties_reviewers TO '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".client_identifiers TO '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".labs TO '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".properties_modules TO '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".log_extra_time TO '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".log_lab_end_time TO '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".schools TO '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".paper_metadata_security TO '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".modules_student TO '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".question_exclude TO '" . self::$cfg_db_external_user . "'@'" . self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".users_metadata TO '" . self::$cfg_db_external_user . "'@'" . self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".marking_override TO '" . self::$cfg_db_external_user . "'@'" . self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".sid TO '" . self::$cfg_db_external_user . "'@'" . self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".student_notes TO '" . self::$cfg_db_external_user . "'@'" . self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".paper_notes TO '" . self::$cfg_db_external_user . "'@'" . self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".exam_announcements TO '" . self::$cfg_db_external_user . "'@'" . self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".relationships TO '" . self::$cfg_db_external_user . "'@'" . self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".feedback_release TO '" . self::$cfg_db_external_user . "'@'" . self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".cache_paper_stats TO '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".paper_feedback TO '". self::$cfg_db_external_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "FLUSH PRIVILEGES";
    foreach ($priv_SQL as $sql) {
      self::$db->query($sql);
      @ob_flush();
      @flush();
      if (self::$db->errno != 0) {
        self::displayError(array('013'=> $string['wdatabaseuser'] . self::$cfg_db_external_user . $string['wnotpermission'] . ' ' . self::$db->error ));
        self::$db->rollback();
      }
    }
   self::$db->commit();

    $priv_SQL = array();
    //create 'database user staff user' and grant permissions
    self::$db->query("CREATE USER  '" . self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "' IDENTIFIED BY '" . self::$cfg_db_staff_passwd . "'");
    if (self::$db->errno != 0) {
      self::displayError(array('013'=> $string['wdatabaseuser'] . self::$cfg_db_staff_user . $string['wnotcreated'] . ' ' . self::$db->error ));
    }
    //$priv_SQL[] = "REVOKE ALL PRIVILEGES ON $dbname.* FROM '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".* TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".cache_median_question_marks TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".cache_paper_stats TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".cache_student_paper_marks TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".ebel TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".exam_announcements TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".feedback_release TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".folders TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".folders_modules_staff TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".help_log TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".help_searches TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".help_tutorial_log TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".hofstee TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".keywords_question TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".keywords_user TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log0 TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log1 TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log2 TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log3 TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".log4 TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".log4_overall TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".log5 TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".log6 TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".log_late TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".log_metadata TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".lti_resource TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".lti_context TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".marking_override TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT ON " . $dbname . ".modules TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".modules_staff TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".modules_student TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".objectives TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".options TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".paper_metadata_security TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".paper_notes TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".paper_feedback TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".papers TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".password_tokens TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".performance_main TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".performance_details TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".properties TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".properties_modules TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".question_exclude TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".questions TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".question_statuses TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".questions_metadata TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".questions_modules TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".recent_papers TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".reference_material TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".reference_modules TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".reference_papers TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".relationships TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".review_comments TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".review_metadata TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, DELETE ON " . $dbname . ".scheduling TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".sessions TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".sid TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT INSERT ON " . $dbname . ".sms_imports TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".special_needs TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".std_set TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".std_set_questions TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".state TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".student_notes TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".temp_users TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".textbox_marking TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".textbox_remark TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".track_changes TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".users TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".users_metadata TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT ON " . $dbname . ".access_log TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT ON " . $dbname . ".denied_log TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".properties_reviewers TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT INSERT ON " . $dbname . ".sys_errors TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".killer_questions TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT ON " . $dbname . ".save_fail_log TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, UPDATE ON " . $dbname . ".toilet_breaks TO '". self::$cfg_db_staff_user . "'@'". self::$cfg_web_host . "'";

    $priv_SQL[] = "FLUSH PRIVILEGES";
    foreach ($priv_SQL as $sql) {
      self::$db->query($sql);
      @ob_flush();
      @flush();
      if (self::$db->errno != 0) {
        self::displayError(array('013'=> $string['wdatabaseuser'] . self::$cfg_db_staff_user . $string['wnotpermission'] . ' ' . self::$db->error ));
        self::$db->rollback();
      }
    }
   self::$db->commit();

    $priv_SQL = array();
    //create 'database user SCT user' and grant permissions
    self::$db->query("CREATE USER  '" . self::$cfg_db_sct_user . "'@'". self::$cfg_web_host . "' IDENTIFIED BY '" . self::$cfg_db_sct_passwd . "'");
    if (self::$db->errno != 0) {
      self::displayError(array('013'=> $string['wdatabaseuser'] . self::$cfg_db_sct_user . $string['wnotcreated'] . ' ' . self::$db->error ));
    }
    //$priv_SQL[] = "REVOKE ALL PRIVILEGES ON $dbname.* FROM '". self::$cfg_db_sct_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".options TO '". self::$cfg_db_sct_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".paper_metadata_security TO '". self::$cfg_db_sct_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".paper_notes TO '". self::$cfg_db_sct_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".papers TO '". self::$cfg_db_sct_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".properties TO '". self::$cfg_db_sct_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".questions TO '". self::$cfg_db_sct_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".question_statuses TO '". self::$cfg_db_sct_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".questions_metadata TO '". self::$cfg_db_sct_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".sct_reviews TO '". self::$cfg_db_sct_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT INSERT ON " . $dbname . ".denied_log TO '". self::$cfg_db_sct_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "FLUSH PRIVILEGES";
    foreach ($priv_SQL as $sql) {
      self::$db->query($sql);
      if (self::$db->errno != 0) {
        self::displayError(array('013'=> $string['wdatabaseuser'] . self::$cfg_db_sct_user . $string['wnotpermission'] . ' ' . self::$db->error ));
        self::$db->rollback();
      }
    }
    self::$db->commit();

    $priv_SQL = array();
    //create 'database user Invigilator user' and grant permissions
    self::$db->query("CREATE USER  '" . self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "' IDENTIFIED BY '" . self::$cfg_db_inv_passwd . "'");
    if (self::$db->errno != 0) {
      self::displayError(array('013'=> $string['wdatabaseuser'] . self::$cfg_db_inv_user . $string['wnotcreated'] . ' ' . self::$db->error ));
    }
    //$priv_SQL[] = "REVOKE ALL PRIVILEGES ON $dbname.* FROM '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".exam_announcements TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".client_identifiers TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".labs TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".log2 TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".log_metadata TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".log_extra_time TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $dbname . ".log_lab_end_time TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".modules_student TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".paper_notes TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".properties TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".properties_modules TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".modules TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".papers TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".questions TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".question_statuses TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE ON " . $dbname . ".student_notes TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".sid TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".special_needs TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT ON " . $dbname . ".users TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT INSERT ON " . $dbname . ".access_log TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT INSERT ON " . $dbname . ".denied_log TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, DELETE ON " . $dbname . ".toilet_breaks TO '". self::$cfg_db_inv_user . "'@'". self::$cfg_web_host . "'";

    $priv_SQL[] = "FLUSH PRIVILEGES";
    foreach ($priv_SQL as $sql) {
      self::$db->query($sql);
      @ob_flush();
      @flush();
      if (self::$db->errno != 0) {
        self::displayError(array('013'=> $string['wdatabaseuser'] . self::$cfg_db_inv_user . $string['wnotpermission'] . ' ' . self::$db->error ));
        self::$db->rollback();
      }
    }
    self::$db->commit();

    $priv_SQL = array();
    //create 'database user sysadmin user' and grant permissions
    self::$db->query("CREATE USER  '" . self::$cfg_db_sysadmin_user . "'@'". self::$cfg_web_host . "' IDENTIFIED BY '" . self::$cfg_db_sysadmin_passwd . "'");
    if (self::$db->errno != 0) {
      self::displayError(array('013'=> $string['wdatabaseuser'] . self::$cfg_db_sysadmin_user . $string['wnotcreated'] . ' ' . self::$db->error ));
    }
    //$priv_SQL[] = "REVOKE ALL PRIVILEGES ON $dbname.* FROM '". self::$cfg_db_sysadmin_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "GRANT SELECT, INSERT, UPDATE, DELETE, ALTER, DROP  ON " . $dbname . ".* TO '". self::$cfg_db_sysadmin_user . "'@'". self::$cfg_web_host . "'";
    $priv_SQL[] = "FLUSH PRIVILEGES";
    foreach ($priv_SQL as $sql) {
      self::$db->query($sql);
      @ob_flush();
      @flush();
      if (self::$db->errno != 0) {
        echo self::$db->error . "<br />";
        self::displayError(array('013'=> $string['wdatabaseuser'] . self::$cfg_db_sysadmin_user . $string['wnotpermission'] . ' ' . self::$db->error ));
        self::$db->rollback();
      }
    }
    self::$db->commit();

    //create sysadmin user
    UserUtils::create_user( $_POST['SysAdmin_username'],
                            $_POST['SysAdmin_password'],
                            $_POST['SysAdmin_title'],
                            $_POST['SysAdmin_first'],
                            $_POST['SysAdmin_last'],
                            $_POST['SysAdmin_email'],
                            'University Lecturer',
                            '',
                            '1',
                            'Staff,SysAdmin',
                            '',
                            self::$db
                          );

    //create cron user
    UserUtils::create_user( self::$cfg_cron_user,
                            self::$cfg_cron_passwd,
                            '',
                            '',
                            'cron',
                            '',
                            '',
                            '',
                            '',
                            'Staff,SysCron',
                            '',
                            self::$db
                          );

    //create 100 guest accounts
    for ($i=1; $i<=100; $i++) {
      UserUtils::create_user( 'user' . $i,
                              '', //blank password will be generated
                              'Dr',
                              'A',
                              'User' . $i,
                              '',
                              'none',
                              '',
                              '1',
                              'Student',
                              '',
                              self::$db
                            );
     }
   self::$db->commit();

    //add unknown school & faculty

    $facultyID = FacultyUtils::add_faculty('UNKNOWN Faculty',
      self::$db
    );

    $scoolID = SchoolUtils::add_school(  $facultyID,
      'UNKNOWN School',
      self::$db
    );

     //add traing school
    $facultyID = FacultyUtils::add_faculty('Administrative and Support Units',
                                        self::$db
                                     );

    $scoolID = SchoolUtils::add_school(  $facultyID,
                                        'Training',
                                        self::$db
                                     );

     //create special modules
     module_utils::add_modules( 'TRAIN',
                                'Training Module',
                                1,
                                $scoolID,
                                '',
                                '',
                                0,
                                false,
                                false,
                                false,
                                true,
                                null,
                                null,
                                self::$db,
                                0,
                                0,
                                1,
                                1,
																'07/01'
                             );

    module_utils::add_modules(  'SYSTEM',
                                'Online Help',
                                1,
                                $scoolID,
                                '',
                                '',
                                0,
                                true,
                                true,
                                true,
                                true,
                                null,
                                null,
                                self::$db,
                                0,
                                0,
                                1,
                                1,
																'07/01'
                             );
    self::$db->commit();

    // Create default question statuses
    $statuses = array(
      array('name' => 'Normal', 'exclude_marking' => false, 'retired' => false, 'is_default' => true, 'change_locked' => true, 'validate' => true, 'display_warning' => 0, 'colour' => '#000000', 'display_order' => 0),
      array('name' => 'Retired', 'exclude_marking' => false, 'retired' => true, 'is_default' => false, 'change_locked' => true, 'validate' => false, 'display_warning' => 1, 'colour' => '#808080', 'display_order' => 1),
      array('name' => 'Incomplete', 'exclude_marking' => false, 'retired' => false, 'is_default' => false, 'change_locked' => false, 'validate' => false, 'display_warning' => 1, 'colour' => '#000000', 'display_order' => 2),
      array('name' => 'Experimental', 'exclude_marking' => true, 'retired' => false, 'is_default' => false, 'change_locked' => false, 'validate' => true, 'display_warning' => 0, 'colour' => '#808080', 'display_order' => 3),
      array('name' => 'Beta', 'exclude_marking' => false, 'retired' => false, 'is_default' => false, 'change_locked' => false, 'validate' => true, 'display_warning' => 1, 'colour' => '#000000', 'display_order' => 4)
    );

    foreach ($statuses as $data) {
      $qs = new QuestionStatus(self::$db, $string, $data);
      $qs->save();
    }

    //FLUSH PRIVILEGES
    self::$db->query("FLUSH PRIVILEGES");
    if (self::$db->errno != 0) {
      self::logWarning(array('014'=> $string['logwarning20']));
    }
    self::$db->commit();
    self::$db->autocommit(false);
  }
  /**
  * Check that we do not have a config file and that we can write one
  *
  */
  static function configFile() {
    global $string;

    $rogo_path = str_ireplace('/install/index.php','', normalise_path($_SERVER['SCRIPT_FILENAME']));
    $errors = array();
    if (file_exists($rogo_path . '/config/config.inc.php')) {
      $errors['90'] =  sprintf($string['errors1'], $rogo_path."/config/config.inc.php");
      self::displayError($errors);
    }
  }

  /**
  * Check that  config file is writeable
  *
  */
  static function configFileIsWriteable() {

    $rogo_path = '';

    if (strpos(normalise_path($_SERVER['SCRIPT_FILENAME']), '/install/index.php')  !== false) {
      $rogo_path = str_ireplace('/install/index.php','',  normalise_path($_SERVER['SCRIPT_FILENAME']));
    }

    if (strpos(normalise_path($_SERVER['SCRIPT_FILENAME']), '/updates/version4.php') !== false) {
      $rogo_path = str_ireplace('/updates/version4.php','', normalise_path($_SERVER['SCRIPT_FILENAME']));
    }

    if (strpos(normalise_path($_SERVER['SCRIPT_FILENAME']), '/updates/version5.php') !== false) {
      $rogo_path = str_ireplace('/updates/version5.php','', normalise_path($_SERVER['SCRIPT_FILENAME']));
    }

    if (is_writable($rogo_path . '/config/config.inc.php')) {
      return true;
    } else {
      return false;
    }
  }

  /**
  * Check that we write to the /config/ dir
  *
  */
  static function configPathIsWriteable() {

    $rogo_path = '';

    if (strpos(normalise_path($_SERVER['SCRIPT_FILENAME']), '/install/index.php')  !== false) {
      $rogo_path = str_ireplace('/install/index.php','',normalise_path($_SERVER['SCRIPT_FILENAME']));
    }

    if (strpos(normalise_path($_SERVER['SCRIPT_FILENAME']), '/updates/version4.php') !== false) {
      $rogo_path = str_ireplace('/updates/version4.php','',normalise_path($_SERVER['SCRIPT_FILENAME']));
    }

    if (strpos(normalise_path($_SERVER['SCRIPT_FILENAME']), '/updates/version5.php') !== false) {
      $rogo_path = str_ireplace('/updates/version5.php','',normalise_path($_SERVER['SCRIPT_FILENAME']));
    }

    if (is_writable($rogo_path . '/config')) {
      return true;
    } else {
      return false;
    }
  }

  /**
  * Check Apache can write to the required directories
  *
  */
  static function checkDirPermissionsPre() {
    global $string;

    // This should work for both windows and UNIX style paths.
    self::$rogo_path = str_ireplace('/install/index.php','', normalise_path($_SERVER['SCRIPT_FILENAME']));
    $errors = array();
    //media
    if (!is_writable(self::$rogo_path . '/media')) {
      $errors['102'] = sprintf($string['errors4'], self::$rogo_path);
    }
    //qti imports
    if (!is_writable(self::$rogo_path . '/qti/imports')) {
      $errors['103'] = sprintf($string['errors5'], self::$rogo_path);
    }
    //qti exports
    if (!is_writable(self::$rogo_path . '/qti/exports')) {
      $errors['104'] = sprintf($string['errors6'], self::$rogo_path);
    }
    if (!is_writable(self::$rogo_path . '/config/config.inc.php')) {
      if (!is_writable(self::$rogo_path . '/config')) {
        $errors['901'] = sprintf($string['errors16'], self::$rogo_path, self::$rogo_path);
      }
    }


    if (count($errors) > 0) {
      self::displayError($errors);
    }
  }

  /**
  * Check Apache can write to the required directories
  *
  */
  static function checkDirPermissionsPost() {
    global $string;
    self::$rogo_path = str_ireplace('/install/index.php','', normalise_path($_SERVER['SCRIPT_FILENAME']));
    $errors = array();
    //tmp
    if (!is_writable($_POST['tmpdir'])) {
      $errors['100'] = sprintf($string['errors3'], $_POST['tmpdir']);
    }
    if (count($errors) > 0) {
      self::displayError($errors);
    }
  }
  
  static function checkDBUsers() {
    $errors = array();

    $usernames = array('auth'=>300, 'stu'=>301, 'staff'=>302, 'ext'=>303, 'sys'=>304, 'sct'=>305, 'inv'=>306);
    foreach ($usernames as $username=>$err_code){
      $test_username = self::$cfg_db_basename . '_' . $username;
      if (self::does_user_exist($test_username)) {
        $errors[$err_code] = "User '" . $test_username . "' already exists.";

      }
    }
    
    if (count($errors) > 0) {
      self::displayError($errors);
    }

  }

  /**
  * Check for installed software versions PHP, Apache
  *
  */
  static function checkSoftware() {
    global $string;

    $errors = array();
    //apache
    $apache = explode('/', $_SERVER['SERVER_SOFTWARE']);

    //php
    $php_min_ver = '5.0';
    if (phpversion() < $php_min_ver) {
      $errors['202'] = $string['errors10'];
    }
    $phpModules = get_loaded_extensions();
    if ( !in_array('mysqli', $phpModules) ) {
      $errors['203'] = $string['errors11'];
    }

    if (count($errors) > 0) {
      self::displayError($errors);
    }
  }

  /**
  * Check we are accessing through HTTPS for security
  *
  */
  static function checkHTTPS() {
    global $string;

    if ($_SERVER['SERVER_PORT'] != 443 and $_SERVER['SERVER_PORT'] != 8080) {
      self::displayError(array(100=> $string['errors12']));
      return false;
    }
    return true;
  }

  /**
  * Display errors with a nice message
  *
  */
  static function displayError($error = '') {
    global $string;

    echo "<div class=\"error\">\n";
    if (is_array($error)) {
      foreach($error as $errCode => $message) {
        echo "\t<div><img src=\"../artwork/small_yellow_warning_icon.gif\" width=\"12\" height=\"11\" alt=\"!\" /> <strong>" . $string['errors13'] . " $errCode:</strong> $message</div>\n";
      }
    }
    echo "</div>\n";
    self::displayFooter();
    exit;
  }

  /**
  * Log warnings with a nice message
  *
  */
  static function logWarning($warning = '') {
    if (is_array($warning)) {
      foreach($warning as $key => $val) {
        self::$warnings[] = $key . ':: ' . $val;
      }
    }
  }

  /**
  * Display warnings with a nice message
  *
  */
  static function displayWarnings() {
    global $string;

    if (is_array(self::$warnings)) {
      echo "<h1>". $string['errors14']."</h1>";
      echo "<div class=\"warning\">\n";
      foreach(self::$warnings as $message) {
        echo "\t<div>" . $string['errors15'] . " $message</div>\n";
      }
      echo "</div>\n";
    }

  }

  /**
  * Display header
  *
  */
  static function displayHeader() {
    global $string, $version;

    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta http-equiv="content-type" content="text/html;charset=UTF-8" />

      <title>Rog&#333; Install script</title>

      <link rel="stylesheet" type="text/css" href="../css/body.css" />
      <link rel="stylesheet" type="text/css" href="../css/rogo_logo.css" />
      <link rel="stylesheet" type="text/css" href="../css/header.css" />
      <style type="text/css">
        body {font-size:90%}
        h1 {margin-left:16px; font-size:140%; color;#1F497D}
        .error {float:none; color:#C00000; padding-left: .5em; vertical-align:top}
        .warning {float:none; color:#C00000; padding-left: .5em; vertical-align:top}
        label {float:left; width:175px; padding-left:0em; text-align:right; padding-right:6px}
        p {clear:both}
        .submit {margin-left:42%; padding-top:2em}
        table {border:none;padding:0px}
        .h {margin-top:1.5em; margin-bottom:0.5em; width:97%; color:#1E3287}
        .h hr {border:0px; height:1px; color:#E5E5E5; background-color:#E5E5E5; width:98%}
        td.line {width:98%}
        input[type=text], input[type=password] {width:200px}
        form {padding:1em}
        form div {padding-left:2em}
      </style>

      <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
      <script type="text/javascript" src="../js/jquery.validate.min.js"></script>
      <script type="text/javascript" src="../js/jquery-ui-1.10.4.min.js"></script>
      <script>
        $(function() {
          $(document).tooltip();
        });
      </script>
    </head>
    <body>
    <table cellpadding="0" cellspacing="0" border="0" class="header">
    <tr>
      <th style="padding-top:4px; padding-bottom:4px; padding-left:16px">
      <img class="logo_img" src="../artwork/r_logo.gif" alt="logo" />
      <div class="logo_lrg_txt">Rog&#333; <?php echo $version; ?></div>
      <div class="logo_small_txt">System Installation</div>
      </th>
      <th style="text-align:right; padding-right:10px">
      <img src="../artwork/software_64.png" width="64" height="64" alt="Upgrade Icon" />
      </th>
      </tr>
    </table>
    <?php
  }

  /**
  * Display footer
  *
  */
  static function displayfooter() {
    ?>
      </body>
      </html>
    <?php
  }

  static function writeConfigFile() {
    global $version, $cfg_encrypt_salt;

    $config = <<<CONFIG
<?php
/**
*
* config file
*
* @author Simon Wilkinson, Anthony Brown
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

if (empty(\$root)) \$root = str_replace('/config', '/', str_replace('\\\\', '/', dirname(__FILE__)));
require \$root . '/include/path_functions.inc.php';

\$rogo_version = '{rogo_version}';
\$cfg_web_root = get_root_path() . '/';
\$cfg_root_path = rtrim('/' . trim(str_replace(normalise_path(\$_SERVER['DOCUMENT_ROOT']), '', \$cfg_web_root), '/'), '/');
\$cfg_secure_connection = true;    // If true site must be accessed via HTTPS
\$cfg_page_charset 	   = '{cfg_page_charset}';
\$cfg_company = '{cfg_company}';
\$cfg_academic_year_start = '07/01';
\$cfg_tmpdir = '{cfg_tmpdir}';

\$cfg_summative_mgmt = false;     // Set this to true for central summative exam administration.
\$cfg_client_lookup = '{labsecuritytype}'; //ipadress or name
\$cfg_interactive_qs = '{interactivequestions}'; //flash or html5


  \$cfg_web_host = '{cfg_web_host}';

// Local database
  \$cfg_db_username = '{cfg_db_username}';
  \$cfg_db_passwd   = '{cfg_db_passwd}';
  \$cfg_db_database = '{cfg_db_database}';
  \$cfg_db_host 	  = '{cfg_db_host}';
  \$cfg_db_charset 	= '{cfg_db_charset}';
//student db user
  \$cfg_db_student_user = '{cfg_db_student_user}';
  \$cfg_db_student_passwd = '{cfg_db_student_passwd}';
//staff db user
  \$cfg_db_staff_user = '{cfg_db_staff_user}';
  \$cfg_db_staff_passwd = '{cfg_db_staff_passwd}';
//external examiner db user
  \$cfg_db_external_user = '{cfg_db_external}';
  \$cfg_db_external_passwd = '{cfg_db_external_passwd}';
//sysdamin db user
  \$cfg_db_sysadmin_user = '{cfg_db_sysadmin_user}';
  \$cfg_db_sysadmin_passwd = '{cfg_db_sysadmin_passwd}';
//sct db user
  \$cfg_db_sct_user = '{cfg_db_sct_user}';
  \$cfg_db_sct_passwd = '{cfg_db_sct_passwd}';
//invigilator db user
  \$cfg_db_inv_user = '{cfg_db_inv_user}';
  \$cfg_db_inv_passwd = '{cfg_db_inv_passwd}';
// Date formats in MySQL DATE_FORMAT format
  \$cfg_short_date = '{cfg_short_date}';
  \$cfg_long_date_time = '{cfg_long_date_time}';
  \$cfg_tablesorter_date_time = '{cfg_tablesorter_date_time}';
  \$cfg_short_date_time = '{cfg_short_date_time}';
  \$cfg_long_date_php = '{cfg_long_date_php}';
  \$cfg_short_date_php = '{cfg_short_date_php}';
  \$cfg_long_time_php = '{cfg_long_time_php}';
  \$cfg_short_time_php = '{cfg_short_time_php}';
  \$cfg_timezone = '{cfg_timezone}';
  date_default_timezone_set(\$cfg_timezone);
// cron user
  \$cfg_cron_user = '{cfg_cron_user}';
  \$cfg_cron_passwd = '{cfg_cron_passwd}';

// Reports
  \$percent_decimals = 2;

// Standard Setting
  \$hofstee_defaults = array('pass'=>array(0, 'median', 0, 100), 'distinction'=>array('median', 100, 0, 100));
  \$hofstee_whole_numbers = true;

// SMS Imports
  \$cfg_sms_api = '';

// LTI these configure the default lti integration if you want more ability than this then you will need to override the lti_integration class using the lti_integration variable below to set the relative path & filename of the new integration class or left as blank or default to use the built in functionality.
\$cfg_lti_allow_module_self_reg = false; // allows rogo to auto add student to module if selfreg is set for module if from lti launch
\$cfg_lti_allow_staff_module_register = false; // allows rogo to register staff onto the module team if set to true and from lti launch and staff in vle
\$cfg_lti_allow_module_create = false;  // allows rogo to create module if it doesnt exist

\$lti_integration = 'default';


\$authentication_fields_required_to_create_user = array('username', 'title', 'firstname', 'surname', 'email', 'role');

//Authentication settings
\$authentication = array(
  {cfg_authentication_arrays}
);
\$cfg_password_expire = 30;    // Set in days

\$enhancedcalculation = array('host' => 'localhost', 'port'=>6311,'timeout'=>5); //default enhancedcalc Rserve config options

//but use phpEval as default for enhanced calculation questions
\$enhancedcalc_type = 'phpEval'; //set the enhanced calculation to use php for maths
\$enhancedcalculation = array(); //no config options for phpEval plugin

//Lookup settings
\$lookup = array(
  {cfg_lookup_arrays}
);

// Objectives mapping
\$vle_apis = array();


// Institutional email domains
// If using external authentication (e.g. LDAP) list the domains that will authenticate against the external system
// This will allow you to change the password of any users that do not match against those domains (e.g. external examiners)
  \$cfg_institutional_domains = array('nottingham.ac.uk');

// Root path for JS
  \$cfg_js_root = <<< SCRIPT
<script>
  if (typeof cfgRootPath == 'undefined') {
    var cfgRootPath = '\$cfg_root_path';
  }
</script>
SCRIPT;

//Editor
  \$cfg_editor_name = 'tinymce';
  \$cfg_editor_javascript = <<< SCRIPT
\$cfg_js_root
<script type="text/javascript" src="\$cfg_root_path/tools/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="\$cfg_root_path/tools/tinymce/jscripts/tiny_mce/tiny_config.js"></script>
SCRIPT;

if(!isset(\$_SERVER['HTTP_HOST'])) {
  \$_SERVER['HTTP_HOST']='';
}

//Server specific configuration based on hostname.
switch (strtolower(\$_SERVER['HTTP_HOST'])) {
  case 'rogo.local':
    \$cfg_install_type = ' (local)';
    break;
  case 'rogotest.local':
    \$cfg_install_type = ' (local testing)';
    error_reporting(E_ALL);
    break;
  default:
    \$cfg_install_type = '';
    error_reporting(0);
    break;
}

//Warnings
  \$cfg_hour_warning = 10;       // Warning for summative exams

//Paper auto saving settings
  \$cfg_autosave_settimeout = 5; //Maximum time to wait for one request to succeed
  \$cfg_autosave_frequency = 30; //How often to auto save in seconds
  \$cfg_autosave_retrylimit = 3; //How many times to retry a failed save befor informing the user
  \$cfg_autosave_backoff_factor = 1.5; //each retry is lenghtend to \$cfg_autosave_settimeout + (\$cfg_autosave_backoff_factor * \$cfg_autosave_settimeout * retryCount);

//Assistance
  \$support_email = '{cfg_support_email}';
  \$emergency_support_numbers = {emergency_support_numbers};

//Global DEBUG OUTPUT
  //require_once \$_SERVER['DOCUMENT_ROOT'] . 'include/debug.inc';   // Uncomment for debugging output (after uncommenting, comment out line below)
  \$dbclass = 'mysqli';

  //\$display_auth_debug = true; // set this to deisplay debug on failed authentication

  //used for debugging
  \$debug_lang_string = false;  // set to true to show lang string in stored system_error_log messages

  ?>
CONFIG;

    $config = str_replace('{rogo_version}', $version, $config);
    $config = str_replace('{SysAdmin_username}', 'USERNMAE_FOR_DEBUG', $config);
    $config = str_replace('{cfg_web_host}', self::$cfg_web_host, $config);
    $config = str_replace('{cfg_db_host}', self::$cfg_db_host, $config);
    $config = str_replace('{cfg_db_port}', self::$cfg_db_port, $config);
    $config = str_replace('{cfg_db_charset}', self::$cfg_db_charset, $config);
    $config = str_replace('{cfg_page_charset}', self::$cfg_page_charset, $config);
    $config = str_replace('{cfg_company}', self::$cfg_company, $config);

    $config = str_replace('{cfg_db_database}', self::$cfg_db_name, $config);
    $config = str_replace('{cfg_db_username}', self::$cfg_db_username, $config);
    $config = str_replace('{cfg_db_passwd}', self::$cfg_db_password, $config);
    $config = str_replace('{cfg_db_student_user}', self::$cfg_db_student_user, $config);
    $config = str_replace('{cfg_db_student_passwd}', self::$cfg_db_student_passwd, $config);
    $config = str_replace('{cfg_db_staff_user}', self::$cfg_db_staff_user, $config);
    $config = str_replace('{cfg_db_staff_passwd}', self::$cfg_db_staff_passwd, $config);
    $config = str_replace('{cfg_db_external}', self::$cfg_db_external_user, $config);
    $config = str_replace('{cfg_db_external_passwd}', self::$cfg_db_external_passwd, $config);
    $config = str_replace('{cfg_db_sysadmin_user}', self::$cfg_db_sysadmin_user, $config);
    $config = str_replace('{cfg_db_sysadmin_passwd}', self::$cfg_db_sysadmin_passwd, $config);
    $config = str_replace('{cfg_db_sct_user}', self::$cfg_db_sct_user, $config);
    $config = str_replace('{cfg_db_sct_passwd}', self::$cfg_db_sct_passwd, $config);
    $config = str_replace('{cfg_db_inv_user}', self::$cfg_db_inv_user, $config);
    $config = str_replace('{cfg_db_inv_passwd}', self::$cfg_db_inv_passwd, $config);

    $config = str_replace('{cfg_cron_user}', self::$cfg_cron_user, $config);
    $config = str_replace('{cfg_cron_passwd}', self::$cfg_cron_passwd, $config);

    $config = str_replace('{cfg_support_email}', self::$cfg_support_email, $config);
    $config = str_replace('{emergency_support_numbers}', self::$emergency_support_numbers, $config);

    $config = str_replace('{cfg_short_date}', self::$cfg_short_date, $config);
    $config = str_replace('{cfg_long_date_time}', self::$cfg_long_date_time, $config);
    $config = str_replace('{cfg_short_date_time}', self::$cfg_short_date_time, $config);
    $config = str_replace('{cfg_long_date_php}', self::$cfg_long_date_php, $config);
    $config = str_replace('{cfg_short_date_php}', self::$cfg_short_date_php, $config);
    $config = str_replace('{cfg_long_time_php}', self::$cfg_long_time_php, $config);
    $config = str_replace('{cfg_short_time_php}', self::$cfg_short_time_php, $config);
    $config = str_replace('{cfg_timezone}', self::$cfg_timezone, $config);
    $config = str_replace('{cfg_tmpdir}', self::$cfg_tmpdir, $config);
    $config = str_replace('{cfg_tablesorter_date_time}', self::$cfg_tablesorter_date_time, $config);
    $config = str_replace('{labsecuritytype}', self::$cfg_labsecuritytype, $config);
    $config = str_replace('{interactivequestions}', self::$cfg_interactivequestions, $config);



    $authentication_arrays = array();
    if (self::$cfg_auth_lti) {
      $authentication_arrays[] = "array('ltilogin', array(), 'LTI Auth')";
    }
    if (self::$cfg_auth_guest) {
      $authentication_arrays[] = "array('guestlogin', array(), 'Guest Login')";
    }
    if (self::$cfg_auth_impersonation) {
      $authentication_arrays[] = "array('impersonation', array('separator' => '_'), 'Impersonation')";
    }
    if (self::$cfg_auth_internal) {
      $authentication_arrays[] = "array('internaldb', array('table' => 'users', 'username_col' => 'username', 'passwd_col' => 'password', 'id_col' => 'id', 'encrypt' => 'SHA-512', 'encrypt_salt' => '{cfg_encrypt_salt}'), 'Internal Database')";
    }
    if (self::$cfg_auth_ldap) {
      $authentication_arrays[] = "array('ldap', array('table' => 'users', 'username_col' => 'username', 'id_col' => 'id', 'ldap_server' => '{cfg_ldap_server}', 'ldap_search_dn' => '{cfg_ldap_search_dn}', 'ldap_bind_rdn' => '{cfg_ldap_bind_rdn}', 'ldap_bind_password' => '{cfg_ldap_bind_password}', 'ldap_user_prefix' => '{cfg_ldap_user_prefix}'), 'LDAP')";
    }

    $config = str_replace('{cfg_authentication_arrays}', implode(",\n  ", $authentication_arrays), $config);

    $lookup_arrays= array();
    if (self::$cfg_uselookupLdap) {
      $lookup_arrays[]=  "array('ldap', array('ldap_server' => '{cfg_lookup_ldap_server}', 'ldap_search_dn' => '{cfg_lookup_ldap_search_dn}', 'ldap_bind_rdn' => '{cfg_lookup_ldap_bind_rdn}', 'ldap_bind_password' => '{cfg_lookup_ldap_bind_password}', 'ldap_user_prefix' => '{cfg_lookup_ldap_user_prefix}', 'ldap_attributes' => array('sAMAccountName' => 'username', 'sn' => 'surname', 'title' => 'title', 'givenName' => 'firstname', 'department' => 'school', 'mail' => 'email',  'cn' => 'username',  'employeeType' => 'role',  'initials' => 'initials'), 'lowercasecompare' => true, 'storeprepend' => 'ldap_'), 'LDAP')";
    }
    if (self::$cfg_uselookupXML) {
      $lookup_arrays[]= "array('XML', array('baseurl' => 'http://exports/', 'userlookup' => array( 'url' => '/student.ashx?campus=uk', 'mandatoryurlfields' => array('username'), 'urlfields' => array('username' => 'username'), 'xmlfields' => array('StudentID' => 'studentID', 'Title' => 'title', 'Forename' => 'firstname', 'Surname' => 'surname', 'Email' => 'email', 'Gender' => 'gender', 'YearofStudy' => 'yearofstudy', 'School' => 'school', 'Degree' => 'degree', 'CourseCode' => 'coursecode', 'CourseTitle' => 'coursetitle', 'AttendStatus' => 'attendstatus'), 'oneitemreturned' => true, 'override' => array('firstname' => true), 'storeprepend' => 'sms_userlookup_')), 'XML')";
    }

    $config = str_replace('{cfg_lookup_arrays}', implode(",\n  ", $lookup_arrays), $config);

    $salt = $cfg_encrypt_salt; //=$salt;

    $config = str_replace('{cfg_encrypt_salt}', $salt, $config);

    $config = str_replace('{cfg_ldap_server}', self::$cfg_ldap_server, $config);
    $config = str_replace('{cfg_ldap_search_dn}', self::$cfg_ldap_search_dn, $config);
    $config = str_replace('{cfg_ldap_bind_rdn}', self::$cfg_ldap_bind_rdn, $config);
    $config = str_replace('{cfg_ldap_bind_password}', self::$cfg_ldap_bind_password, $config);
    $config = str_replace('{cfg_ldap_user_prefix}', self::$cfg_ldap_user_prefix, $config);


    $config = str_replace('{cfg_lookup_ldap_server}', self::$cfg_lookup_ldap_server, $config);
    $config = str_replace('{cfg_lookup_ldap_search_dn}', self::$cfg_lookup_ldap_search_dn, $config);
    $config = str_replace('{cfg_lookup_ldap_bind_rdn}', self::$cfg_lookup_ldap_bind_rdn, $config);
    $config = str_replace('{cfg_lookup_ldap_bind_password}', self::$cfg_lookup_ldap_bind_password, $config);
    $config = str_replace('{cfg_lookup_ldap_user_prefix}', self::$cfg_lookup_ldap_user_prefix, $config);

    $config = str_replace('{SERVER_NAME}', $_SERVER['HTTP_HOST'], $config);

    if (file_exists(self::$rogo_path . '/config/config.inc.php')) {
      rename(self::$rogo_path . '/config/config.inc.php', self::$rogo_path . '/config/config.inc.old.php');
    }

    if (file_put_contents(self::$rogo_path . '/config/config.inc.php', $config) === false) {
      self::displayError(array(300=>'Could not write config file!'));
    }
  }
}

class databaseTables {

  private $tableList = array();

  function __construct($charset) {
    $this->tableList['access_log'] = <<<QUERY
      CREATE TABLE `access_log` (
        `id` int(11) unsigned NOT NULL auto_increment,
        `userID` int(11) unsigned default NULL,
        `type` varchar(255) default NULL,
        `accessed` datetime default NULL,
        `ipaddress` char(60) default NULL,
        `page` varchar(255) default NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['admin_access'] = <<<QUERY
      CREATE TABLE `admin_access` (
        `adminID` int(11) NOT NULL auto_increment,
        `userID` int(10) unsigned default NULL,
        `schools_id` int(11) default NULL,
        PRIMARY KEY (`adminID`),
        KEY idx_schoolsid_userid (schools_id, userID )
      ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['announcements'] = <<<QUERY
      CREATE TABLE `announcements` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(255) DEFAULT NULL,
        `staff_msg` text,
        `student_msg` text,
        `icon` varchar(255) DEFAULT NULL,
        `startdate` datetime DEFAULT NULL,
        `enddate` datetime DEFAULT NULL,
        `deleted` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['cache_median_question_marks'] = <<<QUERY
      CREATE TABLE `cache_median_question_marks` (
        `paperID` mediumint(8) unsigned NOT NULL,
        `questionID` int(10) unsigned NOT NULL DEFAULT '0',
        `median` decimal(10,5) DEFAULT NULL,
        `mean` decimal(10,5) DEFAULT NULL,
        PRIMARY KEY (`paperID`,`questionID`)
      ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['cache_paper_stats'] = <<<QUERY
      CREATE TABLE `cache_paper_stats` (
        `paperID` mediumint(8) unsigned NOT NULL,
        `cached` int(10) unsigned DEFAULT NULL,
        `max_mark` decimal(10,5) DEFAULT NULL,
        `max_percent` decimal(10,5) DEFAULT NULL,
        `min_mark` decimal(10,5) DEFAULT NULL,
        `min_percent` decimal(10,5) DEFAULT NULL,
        `q1` decimal(10,5) DEFAULT NULL,
        `q2` decimal(10,5) DEFAULT NULL,
        `q3` decimal(10,5) DEFAULT NULL,
        `mean_mark` decimal(10,5) DEFAULT NULL,
        `mean_percent` decimal(10,5) DEFAULT NULL,
        `stdev_mark` decimal(10,5) DEFAULT NULL,
        `stdev_percent` decimal(10,5) DEFAULT NULL,
        PRIMARY KEY (`paperID`)
      ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['cache_student_paper_marks'] = <<<QUERY
      CREATE TABLE `cache_student_paper_marks` (
        `paperID` mediumint(8) unsigned NOT NULL,
        `userID` int(10) unsigned NOT NULL DEFAULT '0',
        `mark` decimal(10,5) DEFAULT NULL,
        `percent` decimal(10,5) DEFAULT NULL,
        PRIMARY KEY (`paperID`,`userID`),
        KEY `idx_userID` (`userID`)
      ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;


    $this->tableList['class_totals_test_local'] = <<<QUERY
        CREATE TABLE `class_totals_test_local` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `user_id` int(10) unsigned DEFAULT NULL,
          `paper_id` mediumint(8) unsigned DEFAULT NULL,
          `status` enum('in_progress','success','failure') DEFAULT NULL,
          `errors` text,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['courses'] = <<<QUERY
        CREATE TABLE `courses` (
          `id` int(11) NOT NULL auto_increment,
          `name` varchar(255) default NULL,
          `description` varchar(255) default NULL,
          `deleted` datetime default NULL,
          `schoolid` int(11) default NULL,
          PRIMARY KEY (`id`),
          KEY `degree` (`name`),
          KEY `idx_courses_name` (`name`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['denied_log'] = <<<QUERY
      CREATE TABLE `denied_log` (
        `id` int(11) unsigned NOT NULL auto_increment,
        `userID` int(11) unsigned default NULL,
        `tried` datetime default NULL,
        `ipaddress` char(60) default NULL,
        `page` varchar(255) default NULL,
        `title` varchar(255) default NULL,
        `msg` text default NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['ebel'] = <<<QUERY
          CREATE TABLE `ebel` (
            `std_setID` int(10) unsigned NOT NULL,
            `category` char(3) default NULL,
            `percentage` float default NULL,
            PRIMARY KEY (`std_setID`,`category`)
          ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['ebel_grid_templates'] = <<<QUERY
          CREATE TABLE `ebel_grid_templates` (
            `id` int(11) NOT NULL auto_increment,
            `EE` tinyint(4) default NULL,
            `EI` tinyint(4) default NULL,
            `EN` tinyint(4) default NULL,
            `ME` tinyint(4) default NULL,
            `MI` tinyint(4) default NULL,
            `MN` tinyint(4) default NULL,
            `HE` tinyint(4) default NULL,
            `HI` tinyint(4) default NULL,
            `HN` tinyint(4) default NULL,
            `EE2` tinyint(4) default NULL,
            `EI2` tinyint(4) default NULL,
            `EN2` tinyint(4) default NULL,
            `ME2` tinyint(4) default NULL,
            `MI2` tinyint(4) default NULL,
            `MN2` tinyint(4) default NULL,
            `HE2` tinyint(4) default NULL,
            `HI2` tinyint(4) default NULL,
            `HN2` tinyint(4) default NULL,
            `name` varchar(255) default NULL,
            PRIMARY KEY  (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['exam_announcements'] = <<<QUERY
          CREATE TABLE `exam_announcements` (
            `paperID` mediumint(8) unsigned NOT NULL,
            `q_id` int(4) unsigned NOT NULL DEFAULT '0',
            `q_number` smallint(5) unsigned NOT NULL DEFAULT '0',
            `screen` tinyint(4) unsigned NOT NULL DEFAULT '0',
            `msg` text,
            `created` datetime,
            UNIQUE INDEX `idx_paperID_q_id` (`paperID`,`q_id`)
          ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['extra_cal_dates'] = <<<QUERY
          CREATE TABLE `extra_cal_dates` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `message` text,
            `thedate` datetime NOT NULL,
            `duration` int(11) NOT NULL,
            `bgcolor` varchar(16) NOT NULL,
            `deleted` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['faculty'] = <<<QUERY
          CREATE TABLE `faculty` (
            `id` int(11) NOT NULL auto_increment,
            `name` varchar(80) default NULL,
            `deleted` datetime default NULL,
            PRIMARY KEY  (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['feedback_release'] = <<<QUERY
        CREATE TABLE `feedback_release` (
          `idfeedback_release` int(11) NOT NULL auto_increment,
          `paper_id` mediumint(8) unsigned default NULL,
          `date` datetime NOT NULL,
          `type` enum('objectives','questions','cohort_performance','external_examiner') default NULL,
          PRIMARY KEY  (`idfeedback_release`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['folders'] = <<<QUERY
        CREATE TABLE `folders` (
          `id` int(4) NOT NULL auto_increment,
          `ownerID` int(10) unsigned default NULL,
          `name` text,
          `created` datetime default NULL,
          `color` enum('yellow','red','green','blue','grey') default NULL,
          `deleted` datetime default NULL,
          PRIMARY KEY  (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['folders_modules_staff'] = <<<QUERY
        CREATE TABLE `folders_modules_staff` (
          `folders_id` int(10) unsigned NOT NULL DEFAULT '0',
          `idMod` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY  (`folders_id`,`idMod`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['help_log'] = <<<QUERY
        CREATE TABLE `help_log` (
          `id` int(11) NOT NULL auto_increment,
          `type` enum('student','staff') default NULL,
          `userID` int(10) unsigned default NULL,
          `accessed` datetime default NULL,
          `pageID` int(11) default NULL,
          PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['help_searches'] = <<<QUERY
        CREATE TABLE `help_searches` (
          `id` int(11) NOT NULL auto_increment,
          `type` enum('student','staff') default NULL,
          `userID` int(10) unsigned default NULL,
          `searched` datetime default NULL,
          `searchstring` text,
          `hits` int(11) default NULL,
          PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['help_tutorial_log'] = <<<QUERY
        CREATE TABLE `help_tutorial_log` (
          `id` int(11) NOT NULL auto_increment,
          `type` enum('student','staff') default NULL,
          `userID` int(10) unsigned default NULL,
          `accessed` datetime default NULL,
          `tutorial` varchar(255) default NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['hofstee'] = <<<QUERY
        CREATE TABLE `hofstee` (
          `std_setID` int(10) unsigned NOT NULL,
          `whole_numbers` tinyint(4) DEFAULT NULL,
          `x1_pass` tinyint(4) DEFAULT NULL,
          `x2_pass` tinyint(4) DEFAULT NULL,
          `y1_pass` tinyint(4) DEFAULT NULL,
          `y2_pass` tinyint(4) DEFAULT NULL,
          `x1_distinction` tinyint(4) DEFAULT NULL,
          `x2_distinction` tinyint(4) DEFAULT NULL,
          `y1_distinction` tinyint(4) DEFAULT NULL,
          `y2_distinction` tinyint(4) DEFAULT NULL,
          `marking` tinyint(4) DEFAULT NULL,
           PRIMARY KEY (`std_setID`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['client_identifiers'] = <<<QUERY
        CREATE TABLE `client_identifiers` (
          `id` int(11) NOT NULL auto_increment,
          `lab` smallint(5) unsigned default NULL,
          `address` char(60) default NULL,
          `hostname` char(255) default NULL,
          `low_bandwidth` tinyint(4) default '0',
          PRIMARY KEY (`id`),
          KEY `lab` (`lab`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['keywords_question'] = <<<QUERY
        CREATE TABLE `keywords_question` (
          `q_id` int(11) default NULL,
          `keywordID` int(11) default NULL,
          PRIMARY KEY (`q_id`, `keywordID`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['keywords_user'] = <<<QUERY
        CREATE TABLE `keywords_user` (
          `id` int(11) NOT NULL auto_increment,
          `userID` int(10) unsigned default NULL,
          `keyword` char(255) default NULL,
          `keyword_type` enum('personal','team') default NULL,
          PRIMARY KEY (`id`),
          KEY `username` (`userID`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['killer_questions'] = <<<QUERY
        CREATE TABLE `killer_questions` (
          `id` int(4) unsigned NOT NULL auto_increment,
          `paperID` mediumint(8) unsigned NOT NULL,
          `q_id` int(4) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`),
          KEY `idx_paperID` (`paperID`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['labs'] = <<<QUERY
        CREATE TABLE `labs` (
          `id` smallint(5) unsigned NOT NULL auto_increment,
          `name` varchar(255) default NULL,
          `campus` varchar(255) default NULL,
          `building` varchar(255) default NULL,
          `room_no` varchar(255) default NULL,
          `timetabling` text,
          `it_support` text,
          `plagarism` text,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['log0'] = <<<QUERY
        CREATE TABLE `log0` (
          `id` int(8) NOT NULL auto_increment,
          `q_id` int(4) NOT NULL DEFAULT '0',
          `mark` float DEFAULT NULL,
          `adjmark` float DEFAULT NULL,
          `totalpos` tinyint(4) DEFAULT NULL,
          `user_answer` text,
          `errorstate` tinyint unsigned NOT NULL DEFAULT '0',
          `screen` tinyint(3) unsigned DEFAULT NULL,
          `duration` mediumint(9) DEFAULT NULL,
          `updated` datetime DEFAULT NULL,
          `dismiss` char(20) DEFAULT NULL,
          `option_order` varchar(255) DEFAULT NULL,
          `metadataID` int(11) unsigned DEFAULT NULL,
          PRIMARY KEY  (`id`),
          UNIQUE KEY `idx_metadataID_qid_screen` (`metadataID`,`q_id`,`screen`),
          KEY `q_id` (`q_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset} PACK_KEYS=1
QUERY;

    $this->tableList['log0_deleted'] = <<<QUERY
        CREATE TABLE `log0_deleted` (
          `id` int(8) NOT NULL UNIQUE,
          `q_id` int(4) NOT NULL DEFAULT '0',
          `mark` float DEFAULT NULL,
          `adjmark` float DEFAULT NULL,
          `totalpos` tinyint(4) DEFAULT NULL,
          `user_answer` text,
          `errorstate` tinyint unsigned NOT NULL DEFAULT '0',
          `screen` tinyint(3) unsigned DEFAULT NULL,
          `duration` mediumint(9) DEFAULT NULL,
          `updated` datetime DEFAULT NULL,
          `dismiss` char(20) DEFAULT NULL,
          `option_order` varchar(255) DEFAULT NULL,
          `metadataID` int(11) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['log1'] = <<<QUERY
        CREATE TABLE `log1` (
          `id` int(8) NOT NULL auto_increment,
          `q_id` int(4) NOT NULL DEFAULT '0',
          `mark` float DEFAULT NULL,
          `adjmark` float DEFAULT NULL,
          `totalpos` tinyint(4) DEFAULT NULL,
          `user_answer` text,
          `errorstate` tinyint unsigned NOT NULL DEFAULT '0',
          `screen` tinyint(3) unsigned DEFAULT NULL,
          `duration` mediumint(9) DEFAULT NULL,
          `updated` datetime DEFAULT NULL,
          `dismiss` char(20) DEFAULT NULL,
          `option_order` varchar(255) DEFAULT NULL,
          `metadataID` int(11) unsigned DEFAULT NULL,
          PRIMARY KEY  (`id`),
          UNIQUE KEY `idx_metadataID_qid_screen` (`metadataID`,`q_id`,`screen`),
          KEY `q_id` (`q_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset} PACK_KEYS=1
QUERY;

    $this->tableList['log1_deleted'] = <<<QUERY
        CREATE TABLE `log1_deleted` (
          `id` int(8) NOT NULL UNIQUE,
          `q_id` int(4) NOT NULL DEFAULT '0',
          `mark` float DEFAULT NULL,
          `adjmark` float DEFAULT NULL,
          `totalpos` tinyint(4) DEFAULT NULL,
          `user_answer` text,
          `errorstate` tinyint unsigned NOT NULL DEFAULT '0',
          `screen` tinyint(3) unsigned DEFAULT NULL,
          `duration` mediumint(9) DEFAULT NULL,
          `updated` datetime DEFAULT NULL,
          `dismiss` char(20) DEFAULT NULL,
          `option_order` varchar(255) DEFAULT NULL,
          `metadataID` int(11) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['log2'] = <<<QUERY
        CREATE TABLE `log2` (
          `id` int(8) NOT NULL auto_increment,
          `q_id` int(4) NOT NULL DEFAULT '0',
          `mark` float DEFAULT NULL,
          `adjmark` float DEFAULT NULL,
          `totalpos` tinyint(4) DEFAULT NULL,
          `user_answer` text,
          `errorstate` tinyint unsigned NOT NULL DEFAULT '0',
          `screen` tinyint(3) unsigned DEFAULT NULL,
          `duration` mediumint(9) DEFAULT NULL,
          `updated` datetime DEFAULT NULL,
          `dismiss` char(20) DEFAULT NULL,
          `option_order` varchar(255) DEFAULT NULL,
          `metadataID` int(11) unsigned DEFAULT NULL,
          PRIMARY KEY  (`id`),
          UNIQUE KEY `idx_metadataID_qid_screen` (`metadataID`,`q_id`,`screen`),
          KEY `q_id` (`q_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset} PACK_KEYS=1
QUERY;

    $this->tableList['log3'] = <<<QUERY
        CREATE TABLE `log3` (
          `id` int(8) NOT NULL auto_increment,
          `q_id` int(4) NOT NULL DEFAULT '0',
          `mark` float DEFAULT NULL,
          `adjmark` float DEFAULT NULL,
          `totalpos` tinyint(4) DEFAULT NULL,
          `user_answer` text,
          `errorstate` tinyint unsigned NOT NULL DEFAULT '0',
          `screen` tinyint(3) unsigned DEFAULT NULL,
          `duration` mediumint(9) DEFAULT NULL,
          `updated` datetime DEFAULT NULL,
          `dismiss` char(20) DEFAULT NULL,
          `option_order` varchar(255) DEFAULT NULL,
          `metadataID` int(11) unsigned DEFAULT NULL,
          PRIMARY KEY  (`id`),
          UNIQUE KEY `idx_metadataID_qid_screen` (`metadataID`,`q_id`,`screen`),
          KEY `q_id` (`q_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset} PACK_KEYS=1
QUERY;

    $this->tableList['log4'] = <<<QUERY
        CREATE TABLE `log4` (
          `id` int NOT NULL auto_increment,
          `q_id` int(11) DEFAULT NULL,
          `rating` text,
          `q_parts` varchar(50) DEFAULT NULL,
          `log4_overallID` int(11) unsigned DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `q_id` (`q_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['log4_overall'] = <<<QUERY
        CREATE TABLE `log4_overall` (
          `id` int(11) NOT NULL auto_increment,
          `userID` int(10) unsigned default NULL,
          `started` datetime default NULL,
          `q_paper` mediumint unsigned DEFAULT NULL,
          `overall_rating` text,
          `numeric_score` int(11) DEFAULT NULL,
          `feedback` text,
          `student_grade` char(25) DEFAULT NULL,
          `examinerID` mediumint(8) unsigned DEFAULT NULL,
          `osce_type` enum('electronic','paper') DEFAULT NULL,
          `year` tinyint(4) DEFAULT NULL,
          PRIMARY KEY  (`id`),
          KEY `q_paper` (`q_paper`),
          KEY `username` (`userID`),
          KEY `started` (`started`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['log5'] = <<<QUERY
        CREATE TABLE `log5` (
          `id` int(11) NOT NULL auto_increment,
          `q_id` int(11) DEFAULT NULL,
          `mark` float DEFAULT NULL,
          `adjmark` float DEFAULT NULL,
          `totalpos` tinyint(4) DEFAULT NULL,
          `metadataID` int(11) unsigned DEFAULT NULL,
          PRIMARY KEY  (`id`),
          UNIQUE KEY `idx_metadataID_qid` (`metadataID`,`q_id`),
          KEY `q_id` (`q_id`)
       ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['log6'] = <<<QUERY
        CREATE TABLE `log6` (
          `id` int(11) NOT NULL auto_increment,
          `paperID` mediumint(8) unsigned DEFAULT NULL,
          `reviewerID` int(10) unsigned default NULL,
          `peerID` int(10) unsigned default NULL,
          `started` datetime default NULL,
          `q_id` int(11) default NULL,
          `rating` tinyint(4) default NULL,
          PRIMARY KEY (`id`),
          KEY `started` (`started`),
          KEY `q_id` (`q_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['log_extra_time'] = <<<QUERY
        CREATE TABLE `log_extra_time` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `labID` smallint(5) unsigned NOT NULL,
          `paperID` mediumint(8) unsigned NOT NULL,
          `invigilatorID` int(10) unsigned NOT NULL,
          `userID` int(10) unsigned NOT NULL,
          `extra_time` int(10) unsigned NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `key_lab_id_paper_id_user_id` (`labID`,`paperID`,`userID`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['log_lab_end_time'] = <<<QUERY
        CREATE TABLE `log_lab_end_time` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `labID` smallint(5) unsigned NOT NULL,
          `paperID` mediumint(8) unsigned NOT NULL,
          `invigilatorID` int(10) unsigned NOT NULL,
          `start_time` int(10) unsigned DEFAULT NULL,
          `end_time` int(10) unsigned NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `key_lab_paper_invig_time` (`labID`,`paperID`,`invigilatorID`,`end_time`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['log_late'] = <<<QUERY
        CREATE TABLE `log_late` (
          `id` int(8) NOT NULL auto_increment,
          `q_id` int(4) NOT NULL default '0',
          `mark` float default NULL,
          `adjmark` float DEFAULT NULL,
          `totalpos` tinyint(4) default NULL,
          `user_answer` text,
          `errorstate` tinyint unsigned NOT NULL DEFAULT '0',
          `screen` tinyint(3) unsigned default NULL,
          `duration` mediumint(9) default NULL,
          `updated` datetime default NULL,
          `dismiss` char(20) default NULL,
          `option_order` varchar(255) default NULL,
          `metadataID` int(11) unsigned DEFAULT NULL,
          PRIMARY KEY  (`id`),
          UNIQUE KEY `idx_metadataID_qid_screen` (`metadataID`,`q_id`,`screen`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['log_metadata'] = <<<QUERY
        CREATE TABLE `log_metadata` (
          `id` int(11) unsigned NOT NULL auto_increment,
          `userID` int(10) unsigned default NULL,
          `paperID` mediumint(8) unsigned default NULL,
          `started` datetime default NULL,
          `ipaddress` varchar(100) default NULL,
          `student_grade` char(25) default NULL,
          `year` tinyint(4) default NULL,
          `attempt` tinyint(4) default NULL,
          `completed` datetime DEFAULT NULL,
          `lab_name` varchar(255) DEFAULT NULL,
          `highest_screen` tinyint(3) unsigned DEFAULT NULL,
          PRIMARY KEY  (`id`),
          KEY `userID` (`userID`,`paperID`,`started`),
          KEY `idx_log_metadata_student_grade` (`student_grade`),
          KEY `idx_log_metadata_paperID` (`paperID`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['log_metadata_deleted'] = <<<QUERY
        CREATE TABLE `log_metadata_deleted` (
          `id` int(11) unsigned NOT NULL UNIQUE,
          `userID` int(10) unsigned DEFAULT NULL,
          `paperID` mediumint(8) unsigned DEFAULT NULL,
          `started` datetime DEFAULT NULL,
          `ipaddress` varchar(100) DEFAULT NULL,
          `student_grade` char(25) DEFAULT NULL,
          `year` tinyint(4) DEFAULT NULL,
          `attempt` tinyint(4) DEFAULT NULL,
          `completed` datetime DEFAULT NULL,
          `lab_name` varchar(255) DEFAULT NULL,
          `highest_screen` tinyint(3) unsigned DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['lti_context'] = <<<QUERY
          CREATE TABLE IF NOT EXISTS `lti_context` (
          `lti_context_key` VARCHAR(255) NOT NULL,
          `c_internal_id` VARCHAR(255) NOT NULL,
          `updated_on` DATETIME NOT NULL,
          PRIMARY KEY (`lti_context_key`),
          KEY `c_internal_id` (`c_internal_id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['lti_keys'] = <<<QUERY
          CREATE TABLE IF NOT EXISTS `lti_keys` (
          `id` mediumint(9) NOT NULL AUTO_INCREMENT,
          `oauth_consumer_key` char(255) NOT NULL,
          `secret` char(255) DEFAULT NULL,
          `name` char(255) DEFAULT NULL,
          `context_id` char(255) DEFAULT NULL,
          `deleted` datetime,
          `updated_on` datetime,
          PRIMARY KEY (`id`),
          KEY `oauth_consumer_key` (`oauth_consumer_key`)
          ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['lti_resource'] = <<<QUERY
        CREATE TABLE IF NOT EXISTS `lti_resource` (
        `lti_resource_key` varchar(255) NOT NULL,
        `internal_id` varchar(255) DEFAULT NULL,
        `internal_type` varchar(255) NOT NULL,
        `updated_on` datetime,
        PRIMARY KEY (`lti_resource_key`),
        KEY `destination2` (`internal_type`),
        KEY `destination` (`internal_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['lti_user'] = <<<QUERY
          CREATE TABLE IF NOT EXISTS `lti_user` (
          `lti_user_key` varchar(255) NOT NULL,
          `lti_user_equ` int(10) unsigned,
          `updated_on` datetime NOT NULL,
          PRIMARY KEY (`lti_user_key`),
          KEY `lti_user_equ` (`lti_user_equ`)
         ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['marking_override'] = <<<QUERY
        CREATE TABLE `marking_override` (
          `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `log_id` INT(11) UNSIGNED NOT NULL,
          `log_type` TINYINT(4) UNSIGNED NOT NULL,
          `user_id` INT(10) UNSIGNED NOT NULL,
          `q_id` INT(4) UNSIGNED NOT NULL,
          `paper_id` MEDIUMINT(8) UNSIGNED NOT NULL,
          `marker_id` INT(10) UNSIGNED NOT NULL,
          `date_marked` DATETIME NOT NULL,
          `new_mark_type` ENUM('correct', 'partial', 'incorrect') NOT NULL,
          `reason` VARCHAR(255) NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `log_id` (`log_id`, `log_type`)
          ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['modules'] = <<<QUERY
        CREATE TABLE `modules` (
          `id` int(11) NOT NULL auto_increment,
          `moduleid` char(25) default NULL,
          `fullname` text,
          `active` tinyint(4) default NULL,
          `vle_api` varchar(255) default NULL,
          `checklist` varchar(255) default NULL,
          `sms` varchar(255) default NULL,
          `selfenroll` tinyint(4) default NULL,
          `schoolid` int(11) default NULL,
          `neg_marking` tinyint(1) default NULL,
          `ebel_grid_template` int(11) default NULL,
          `mod_deleted` datetime default NULL,
          `timed_exams` tinyint(4) default NULL,
          `exam_q_feedback` tinyint(4) default NULL,
          `add_team_members` tinyint(4) default NULL,
          `map_level` smallint(2) NOT NULL DEFAULT '0',
          `academic_year_start` char(5) NOT NULL,
          PRIMARY KEY (`id`),
          KEY `guideid` (`moduleid`),
          KEY `idx_moduleid_deleted` (`moduleid`,`mod_deleted`),
          KEY `idx_schoolid_deleted` (`schoolid`,`mod_deleted`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['modules_staff'] = <<<QUERY
        CREATE TABLE `modules_staff` (
          `groupID` int(4) NOT NULL auto_increment,
          `idMod` int(11) unsigned DEFAULT NULL,
          `memberID` int(10) unsigned DEFAULT NULL,
          `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`groupID`),
          KEY `name` (`idMod`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['modules_student'] = <<<QUERY
        CREATE TABLE `modules_student` (
          `id` int(11) NOT NULL auto_increment,
          `userID` int(10) unsigned DEFAULT NULL,
          `idMod` int(11) unsigned DEFAULT NULL,
          `calendar_year` enum('2008/09','2009/10','2010/11','2011/12','2012/13','2013/14','2014/15','2015/16','2016/17','2017/18','2018/19','2019/20') DEFAULT NULL,
          `attempt` tinyint(4) DEFAULT NULL,
          `auto_update` tinyint(4) DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `idx_userID` (`userID`),
          KEY `idx_mod_calyear` (`calendar_year`,`idMod`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['objectives'] = <<<QUERY
        CREATE TABLE `objectives` (
					`obj_id` int(11) NOT NULL,
					`objective` text NOT NULL,
					`idMod` int(11) unsigned NOT NULL DEFAULT '0',
					`identifier` bigint(20) unsigned NOT NULL,
					`calendar_year` enum('2008/09','2009/10','2010/11','2011/12','2012/13','2013/14','2014/15','2015/16','2016/17','2017/18','2018/19','2019/20') NOT NULL DEFAULT '2008/09',
					`sequence` int(11) DEFAULT NULL,
					PRIMARY KEY (`obj_id`,`idMod`,`calendar_year`),
					KEY `idx_identifier_calendar_year_sequence` (`identifier`,`calendar_year`,`sequence`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['options'] = <<<QUERY
        CREATE TABLE `options` (
          `o_id` int(4) NOT NULL default '0',
          `option_text` text,
          `o_media` varchar(255) default NULL,
          `o_media_width` varchar(4) default NULL,
          `o_media_height` varchar(4) default NULL,
          `feedback_right` text,
          `feedback_wrong` text,
          `correct` text,
          `id_num` int(11) NOT NULL auto_increment,
          `marks_correct` float default NULL,
          `marks_incorrect` float default NULL,
          `marks_partial` float default NULL,
          PRIMARY KEY (`id_num`),
          KEY `o_id` (`o_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['paper_feedback'] = <<<QUERY
        CREATE TABLE `paper_feedback` (
          `id` int(11) unsigned NOT NULL auto_increment,
          `paperID` mediumint(8) unsigned NOT NULL,
          `boundary` tinyint(3) unsigned NOT NULL,
          `msg` text,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['paper_metadata_security'] = <<<QUERY
        CREATE TABLE `paper_metadata_security` (
          `id` int(11) NOT NULL auto_increment,
          `paperID` mediumint(8) unsigned default NULL,
          `name` varchar(255) default NULL,
          `value` varchar(255) default NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['paper_notes'] = <<<QUERY
        CREATE TABLE `paper_notes` (
          `note_id` int(11) NOT NULL auto_increment,
          `note` text,
          `note_date` datetime default NULL,
          `paper_id` mediumint(8) unsigned default NULL,
          `note_authorID` int(10) unsigned default NULL,
          `note_workstation` char(100) default NULL,
          PRIMARY KEY (`note_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['papers'] = <<<QUERY
        CREATE TABLE `papers` (
          `p_id` int(4) NOT NULL auto_increment,
          `paper` mediumint(8) unsigned DEFAULT NULL,
          `question` int(4) unsigned NOT NULL default '0',
          `screen` tinyint(2) unsigned NOT NULL default '0',
          `display_pos` smallint(5) unsigned default NULL,
          PRIMARY KEY (`p_id`),
          KEY `paper` (`paper`),
          KEY `question_idx` (`question`),
          KEY `screen` (`screen`),
          KEY `paper_2` (`paper`,`display_pos`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['password_tokens'] = <<<QUERY
        CREATE TABLE `password_tokens` (
          `id` int(11) NOT NULL auto_increment,
          `user_id` int(11) unsigned DEFAULT NULL,
          `token` char(16) NOT NULL,
          `time` datetime NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['performance_details'] = <<<QUERY
          CREATE TABLE `performance_details` (
          `perform_id` int(11) DEFAULT NULL,
          `part_no` tinyint(4) DEFAULT NULL,
          `p` tinyint(4) DEFAULT NULL,
          `d` tinyint(4) DEFAULT NULL,
          KEY `idx_perform_id` (`perform_id`)
          ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['performance_main']  = <<<QUERY
          CREATE TABLE `performance_main` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `q_id` int(10) unsigned DEFAULT NULL,
          `paperID` int(10) unsigned DEFAULT NULL,
          `percentage` tinyint(4) DEFAULT NULL,
          `cohort_size` int(10) unsigned DEFAULT NULL,
          `taken` date DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `idx_q_id` (`q_id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['properties'] = <<<QUERY
        CREATE TABLE `properties` (
          `property_id` mediumint(8) unsigned NOT NULL auto_increment,
          `paper_title` varchar(255) default NULL,
          `start_date` datetime default NULL,
          `end_date` datetime default NULL,
          `timezone` varchar(255) default NULL,
          `paper_type` enum('0','1','2','3','4','5','6') default NULL,
          `paper_prologue` text,
          `paper_postscript` text,
          `bgcolor` varchar(20) default NULL,
          `fgcolor` varchar(20) default NULL,
          `themecolor` varchar(20) default NULL,
          `labelcolor` varchar(20) default NULL,
          `fullscreen` enum('0','1') NOT NULL default '0',
          `marking` char(60) default NULL,
          `bidirectional` enum('0','1') NOT NULL default '0',
          `pass_mark` tinyint(4) default NULL,
          `distinction_mark` tinyint(4) default NULL,
          `paper_ownerID` int(10) unsigned default NULL,
          `folder` varchar(255) default NULL,
          `labs` text,
          `rubric` text,
          `calculator` tinyint(4) default NULL,
          `exam_duration` smallint(6) default NULL,
          `deleted` datetime default NULL,
          `created` datetime default NULL,
          `random_mark` float default NULL,
          `total_mark` mediumint(9) default NULL,
          `display_correct_answer` enum('0','1') default NULL,
          `display_question_mark` enum('0','1') default NULL,
          `display_students_response` enum('0','1') default NULL,
          `display_feedback` enum('0','1') default NULL,
          `hide_if_unanswered` enum('0','1') default NULL,
          `calendar_year` enum('2002/03','2003/04','2004/05','2005/06','2006/07','2007/08','2008/09','2009/10','2010/11','2011/12','2012/13','2013/14','2014/15','2015/16','2016/17','2017/18','2018/19','2019/20') default NULL,
          `external_review_deadline` date default NULL,
          `internal_review_deadline` date default NULL,
          `sound_demo` enum('0','1') default NULL,
          `latex_needed` tinyint(4) default '0',
          `password` char(20) default NULL,
          `retired` datetime default NULL,
          `crypt_name` varchar(32) default NULL,
          `recache_marks` tinyint(3) unsigned DEFAULT '0',
          PRIMARY KEY (`property_id`),
          KEY `paper_title` (`paper_title`),
          KEY `paper_owner` (`paper_ownerID`),
          KEY `question_type` (`paper_type`),
          KEY `crypt_name_idx` (`crypt_name`),
          KEY `idx_owner_deleted` (`paper_ownerID`,`deleted`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['properties_modules'] = <<<QUERY
        CREATE TABLE `properties_modules` (
          `property_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
          `idMod` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`property_id`,`idMod`),
          KEY `idx_idmod` (`idMod`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['properties_reviewers'] = <<<QUERY
         CREATE TABLE `properties_reviewers` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `paperID` mediumint(8) unsigned DEFAULT NULL,
          `reviewerID` int(11) unsigned DEFAULT NULL,
          `type` enum('internal','external') DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `idx_paperID` (`paperID`),
          KEY `idx_type` (`type`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['question_exclude'] = <<<QUERY
        CREATE TABLE `question_exclude` (
          `id` int(11) NOT NULL auto_increment,
          `q_paper` int(11) default NULL,
          `q_id` int(11) default NULL,
          `parts` varchar(255) default NULL,
          `userID` int unsigned default NULL,
          `date` datetime default NULL,
          `reason` text,
          KEY `idx_q_id` (`q_id`),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['question_statuses'] = <<<QUERY
        CREATE TABLE `question_statuses` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `exclude_marking` tinyint(4) NOT NULL DEFAULT '0',
          `retired` tinyint(3) NOT NULL,
          `is_default` tinyint(4) NOT NULL DEFAULT '0',
          `change_locked` tinyint(3) NOT NULL DEFAULT '1',
          `validate` tinyint(3) NOT NULL DEFAULT '1',
          `display_warning` tinyint(3) DEFAULT '0',
          `colour` char(7) DEFAULT '#000000',
          `display_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['questions'] = <<<QUERY
        CREATE TABLE `questions` (
          `q_id` int(4) NOT NULL auto_increment,
          `q_type` enum('blank','calculation','dichotomous','flash','hotspot','labelling','likert','matrix','mcq','mrq','rank','textbox','info','extmatch','random','sct','keyword_based','true_false','area','enhancedcalc') default NULL,
          `theme` text,
          `scenario` text,
          `leadin` text,
          `correct_fback` text,
          `incorrect_fback` text,
          `display_method` text,
          `notes` text,
          `ownerID` int(11) default NULL,
          `q_media` text,
          `q_media_width` varchar(100) default NULL,
          `q_media_height` varchar(100) default NULL,
          `creation_date` datetime default NULL,
          `last_edited` datetime default NULL,
          `bloom` enum('Knowledge','Comprehension','Application','Analysis','Synthesis','Evaluation') default NULL,
          `scenario_plain` text,
          `leadin_plain` text,
          `checkout_time` datetime default NULL,
          `checkout_authorID` int(10) unsigned default NULL,
          `deleted` datetime default NULL,
          `locked` datetime default NULL,
          `std` varchar(100) default NULL,
          `status` tinyint(3) NOT NULL,
          `q_option_order` enum('display order','alphabetic','random') default NULL,
          `score_method` enum('Mark per Question','Mark per Option','Allow partial Marks','Bonus Mark') default NULL,
          `settings` text,
          `guid` char(40),
          PRIMARY KEY (`q_id`),
          KEY `idx_owner_deleted` (`ownerID`,`deleted`),
          KEY `idx_deleted` (`deleted`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

$this->tableList['questions_metadata'] = <<<QUERY
        CREATE TABLE `questions_metadata` (
          `id` int(11) NOT NULL auto_increment,
          `questionID` int(11) default NULL,
          `type` varchar(255) default NULL,
          `value` varchar(255) default NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

$this->tableList['questions_modules'] = <<<QUERY
        CREATE TABLE `questions_modules` (
          `q_id` int(4) unsigned NOT NULL DEFAULT '0',
          `idMod` int(11) unsigned NOT NULL DEFAULT '0',
          KEY `idx_idmod` (`idMod`),
          PRIMARY KEY (`q_id`,`idMod`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['recent_papers'] = <<<QUERY
        CREATE TABLE `recent_papers` (
          `userID` int(10) unsigned NOT NULL default '0',
          `paperID` mediumint(8) unsigned NOT NULL default '0',
          `accessed` datetime default NULL,
          PRIMARY KEY  (`userID`,`paperID`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['reference_material'] = <<<QUERY
        CREATE TABLE `reference_material` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `title` varchar(255) DEFAULT NULL,
          `content` text,
          `width` smallint(5) unsigned DEFAULT NULL,
          `created` datetime DEFAULT NULL,
          `deleted` datetime DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['reference_modules'] = <<<QUERY
        CREATE TABLE `reference_modules` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `refID` mediumint(8) unsigned DEFAULT NULL,
          `idMod` int(11) unsigned DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['reference_papers'] = <<<QUERY
        CREATE TABLE `reference_papers` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `paperID` mediumint(8) unsigned DEFAULT NULL,
          `refID` mediumint(9) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['relationships'] = <<<QUERY
        CREATE TABLE `relationships` (
          `rel_id` int(11) NOT NULL auto_increment,
          `idMod` int(11) unsigned DEFAULT NULL,
          `paper_id` mediumint(8) unsigned DEFAULT NULL,
          `question_id` int(11) NOT NULL,
          `obj_id` int(11) NOT NULL,
          `calendar_year` enum('2006/07','2007/08','2008/09','2009/10','2010/11','2011/12','2012/13','2013/14','2014/15','2015/16','2016/17','2017/18','2018/19','2019/20') DEFAULT NULL,
          `vle_api` varchar(255) NOT NULL DEFAULT '',
          `map_level` smallint(2) NOT NULL DEFAULT '0',
          PRIMARY KEY (`rel_id`),
          KEY `module_id_idx` (`idMod`),
          KEY `paper_id_idx` (`paper_id`),
          KEY `calendar_year` (`calendar_year`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['review_comments'] = <<<QUERY
        CREATE TABLE `review_comments` (
          `id` int(11) NOT NULL auto_increment,
          `q_id` int(11) default NULL,
          `category` tinyint(4) default NULL,
          `comment` text,
          `action` enum('Not actioned','Read - disagree','Read - actioned') default NULL,
          `response` text,
          `duration` mediumint(9) default NULL,
          `screen` tinyint(4) default NULL,
          `metadataID` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['review_metadata'] = <<<QUERY
        CREATE TABLE `review_metadata` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `reviewerID` int(10) unsigned NOT NULL,
          `paperID` mediumint(8) unsigned NOT NULL,
          `started` datetime DEFAULT NULL,
          `complete` datetime DEFAULT NULL,
          `review_type` enum('External','Internal') DEFAULT NULL,
          `ipaddress` varchar(100) DEFAULT NULL,
          `paper_comment` text,
          PRIMARY KEY (`id`),
          KEY `idx_paperID` (`paperID`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['save_fail_log'] = <<<QUERY
          CREATE TABLE `save_fail_log` (
          `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
          `userID` int(10) unsigned NOT NULL,
          `paperID` mediumint(8) unsigned NOT NULL DEFAULT '0',
          `screen` tinyint(2) unsigned NOT NULL DEFAULT '0',
          `ipaddress` varchar(100) DEFAULT NULL,
          `failed` int(4) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`),
          KEY `idx_paperID` (`paperID`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['scheduling'] = <<<QUERY
          CREATE TABLE `scheduling` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `paperID` mediumint(8) unsigned DEFAULT NULL,
          `period` varchar(255) DEFAULT NULL,
          `barriers_needed` tinyint(4) DEFAULT NULL,
          `cohort_size` varchar(20) DEFAULT NULL,
          `notes` text,
          `sittings` tinyint(4) DEFAULT NULL,
          `campus` varchar(255) DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `idx_paperID` (`paperID`)
           ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['schools'] = <<<QUERY
        CREATE TABLE `schools` (
          `id` int(11) NOT NULL auto_increment,
          `school` char(255) default NULL,
          `facultyID` int(11) default NULL,
          `deleted` datetime default NULL,
          PRIMARY KEY (`id`),
          KEY `idx_facultyID` (`facultyID`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['sct_reviews'] = <<<QUERY
        CREATE TABLE `sct_reviews` (
          `id` int(11) NOT NULL auto_increment,
          `reviewer_name` text,
          `reviewer_email` text,
          `paperID` mediumint(8) unsigned default NULL,
          `q_id` int(4) default NULL,
          `answer` tinyint(4) default NULL,
          `reason` text,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['sessions'] = <<<QUERY
        CREATE TABLE `sessions` (
          `sess_id` int(11) NOT NULL auto_increment,
          `identifier` bigint(20) unsigned NOT NULL,
          `idMod` int(11) unsigned NOT NULL DEFAULT '0',
          `title` text NOT NULL,
          `source_url` text,
          `calendar_year` enum('2008/09','2009/10','2010/11','2011/12','2012/13','2013/14','2014/15','2015/16','2016/17','2017/18','2018/19','2019/20') NOT NULL DEFAULT '2008/09',
          `occurrence` datetime default NULL,
          PRIMARY KEY (`identifier`,`idMod`,`calendar_year`),
          KEY `sess_id` (`sess_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['sid'] = <<<QUERY
        CREATE TABLE `sid` (
          `student_id` char(15) default NULL,
          `userID` int(10) unsigned NOT NULL default 0,
          PRIMARY KEY  (`userID`,`student_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['sms_imports'] = <<<QUERY
        CREATE TABLE `sms_imports` (
          `id` int(11) NOT NULL auto_increment,
          `updated` date default NULL,
          `idMod` int(11) unsigned default NULL,
          `enrolements` int(11) default NULL,
          `enrolement_details` text,
          `deletions` int(11) default NULL,
          `deletion_details` text,
          `import_type` varchar(255) default NULL,
          `academic_year` enum('2002/03','2003/04','2004/05','2005/06','2006/07','2007/08','2008/09','2009/10','2010/11','2011/12','2012/13','2013/14','2014/15','2015/16','2016/17','2017/18','2018/19','2019/20') DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['special_needs'] = <<<QUERY
        CREATE TABLE `special_needs` (
          `special_id` int(11) NOT NULL auto_increment,
          `userID` int(10) unsigned default NULL,
          `background` varchar(20) default NULL,
          `foreground` varchar(20) default NULL,
          `textsize` int(11) default NULL,
          `extra_time` tinyint(4) default NULL,
          `marks_color` varchar(20) default NULL,
          `themecolor` varchar(20) default NULL,
          `labelcolor` varchar(20) default NULL,
          `font` varchar(50) default NULL,
          `unanswered` varchar(20) default NULL,
					`dismiss` varchar(20) default NULL,
					`medical` text,
					`breaks` text,
					PRIMARY KEY (`special_id`),
          UNIQUE KEY `idx_userID` (`userID`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['staff_help'] = <<<QUERY
        CREATE TABLE `staff_help` (
          `id` smallint(6) NOT NULL auto_increment,
          `title` mediumtext,
          `body` mediumtext,
          `body_plain` mediumtext,
          `type` enum('page','pointer') default NULL,
          `checkout_time` datetime default NULL,
          `checkout_authorID` int(10) unsigned default NULL,
          `roles` enum('SysAdmin','Admin','Staff') default NULL,
          `deleted` datetime default NULL,
          `language` char(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
          `articleid` smallint(6) unsigned NOT NULL,
          `lastupdated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY  (`id`),
          KEY `language` (`language`),
          KEY `articleid` (`articleid`),            
          FULLTEXT KEY `title` (`title`,`body_plain`)
        ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
QUERY;

    $this->tableList['std_set'] = <<<QUERY
        CREATE TABLE `std_set` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `setterID` int(10) unsigned NOT NULL,
          `paperID` mediumint(8) unsigned NOT NULL,
          `std_set` datetime DEFAULT NULL,
          `method` enum('Modified Angoff','Angoff (Yes/No)','Ebel','Hofstee') DEFAULT NULL,
          `group_review` text,
          `pass_score` decimal(10,6) DEFAULT NULL,
          `distinction_score` decimal(10,6) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['std_set_questions'] = <<<QUERY
        CREATE TABLE `std_set_questions` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `std_setID` int(10) unsigned NOT NULL,
          `questionID` int(11) unsigned NOT NULL,
          `rating` text,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['state'] = <<<QUERY
        CREATE TABLE `state` (
          `userID` int(10) unsigned DEFAULT NULL,
          `state_name` varchar(255) DEFAULT NULL,
          `content` varchar(255) DEFAULT NULL,
          `page` varchar(255) DEFAULT NULL,
          UNIQUE KEY `idx_user_state` (`userID`,`state_name`,`page`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['student_help'] = <<<QUERY
        CREATE TABLE `student_help` (
          `id` smallint(6) NOT NULL auto_increment,
          `title` mediumtext,
          `body` mediumtext,
          `body_plain` mediumtext,
          `type` enum('page','pointer') default NULL,
          `checkout_time` datetime default NULL,
          `checkout_authorID` int(10) unsigned default NULL,
          `deleted` datetime default NULL,
          `language` char(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
          `articleid` smallint(6) unsigned NOT NULL,
          `lastupdated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `language` (`language`),
          KEY `articleid` (`articleid`),
          FULLTEXT KEY `title` (`title`,`body_plain`)
        ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
QUERY;

    $this->tableList['student_notes'] = <<<QUERY
        CREATE TABLE `student_notes` (
          `note_id` int(11) NOT NULL auto_increment,
          `userID` int(10) unsigned default NULL,
          `note` text,
          `note_date` datetime default NULL,
          `paper_id` mediumint(8) unsigned DEFAULT NULL,
          `note_authorID` int unsigned default NULL,
          PRIMARY KEY (`note_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['sys_errors'] = <<<QUERY
        CREATE TABLE `sys_errors` (
          `id` int(11) NOT NULL auto_increment,
          `occurred` datetime default NULL,
          `userID` int(11) unsigned default NULL,
          `auth_user` varchar(45) default NULL,
          `errtype` enum('Notice','Warning','Fatal Error','Unknown','Application Warning','Application Error') DEFAULT NULL,
          `errstr` text,
          `errfile` text,
          `errline` int(11) default NULL,
          `fixed` datetime default NULL,
          `php_self` text,
          `query_string` text,
          `request_method` enum('GET','HEAD','POST','PUT','DELETE') default NULL,
          `paperID` mediumint unsigned default NULL,
          `post_data` text,
          `variables` longtext,
          `backtrace` longtext,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['sys_updates'] = <<<QUERY
        CREATE TABLE `sys_updates` (
          `name` varchar(255) DEFAULT NULL,
          `updated` datetime NOT NULL,
          KEY `name` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['temp_users'] = <<<QUERY
        CREATE TABLE `temp_users` (
          `id` int(11) NOT NULL auto_increment,
          `first_names` char(60) default NULL,
          `surname` char(50) default NULL,
          `title` enum('Dr','Miss','Mr','Mrs','Ms','Professor') default NULL,
          `student_id` char(10) default NULL,
          `assigned_account` char(10) default NULL,
          `reserved` datetime default NULL,
          PRIMARY KEY  (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['textbox_marking'] = <<<QUERY
				CREATE TABLE `textbox_marking` (
					`id` int(11) NOT NULL auto_increment,
					`paperID` mediumint(8) unsigned default NULL,
					`q_id` int(11) default NULL,
					`answer_id` int(11) default NULL,
					`markerID` int(10) unsigned default NULL,
					`mark` float default NULL,
					`comments` text,
					`date` datetime default NULL,
					`phase` tinyint(4) default NULL,
					`logtype` tinyint(4) default NULL,
					`student_userID` int(10) unsigned default NULL,
          `reminders` VARCHAR(255) NULL,
					PRIMARY KEY (`id`),
					UNIQUE KEY `idx_unique` (`phase`,`answer_id`,`logtype`),
					KEY `paperID` (`paperID`),
					KEY `q_id` (`q_id`)
					) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}        
QUERY;

    $this->tableList['textbox_remark'] = <<<QUERY
        CREATE TABLE `textbox_remark` (
          `id` int(11) NOT NULL auto_increment,
          `paperID` mediumint(8) unsigned default NULL,
          `userID` int(10) unsigned default NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['toilet_breaks'] = <<<QUERY
        CREATE TABLE `toilet_breaks` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `userID` int(10) unsigned NOT NULL,
          `paperID` mediumint(8) unsigned NOT NULL,
          `break_taken` datetime NOT NULL,
          PRIMARY KEY (`id`),
          KEY `paperID` (`paperID`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['track_changes'] = <<<QUERY
        CREATE TABLE `track_changes` (
          `id` int(4) NOT NULL auto_increment,
          `type` varchar(40) default NULL,
          `typeID` int(4) default NULL,
          `editor` int(10) unsigned default NULL,
          `old` text,
          `new` text,
          `changed` datetime default NULL,
          `part` text,
          PRIMARY KEY (`id`),
          KEY `typeID` (`typeID`),
          KEY `type` (`type`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['users'] = <<<QUERY
        CREATE TABLE `users` (
          `password` char(90) NOT NULL,
          `grade` char(30) default NULL,
          `surname` char(35) NOT NULL,
          `initials` char(10) default NULL,
          `title` varchar(30) default NULL,
          `username` char(60) NOT NULL,
          `email` char(65) default NULL,
          `roles` char(40) default NULL,
          `id` int(10) unsigned NOT NULL auto_increment,
          `first_names` char(60) default NULL,
          `gender` enum('Male','Female') default NULL,
          `special_needs` tinyint(4) default '0',
          `yearofstudy` tinyint(4) default NULL,
          `user_deleted` datetime default NULL,
          `password_expire` int(11) unsigned default NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `username_index` (`username`),
          KEY `idx_roles` (`roles`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET={$charset}
QUERY;

    $this->tableList['users_metadata'] = <<<QUERY
        CREATE TABLE `users_metadata` (
          `userID` int(10) unsigned default NULL,
          `idMod` int(11) unsigned default NULL,
          `type` varchar(255) default NULL,
          `value` varchar(255) default NULL,
          `calendar_year` enum('2010/11','2011/12','2012/13','2013/14','2014/15','2015/16','2016/17','2017/18','2018/19','2019/20') default NULL,
          UNIQUE KEY `idx_users_metadata` (`userID`,`idMod`,`type`,`calendar_year`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
QUERY;

  }

  function next() {
    if (count($this->tableList) > 0) {
      return array_pop($this->tableList);
    } else {
      return false;
    }
  }
}

?>
