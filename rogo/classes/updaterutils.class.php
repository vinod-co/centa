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
* Utility class for updater related functionality
*
* @author Ben Parish, Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

Class UpdaterUtils {

  private $mysqli;
  private $db_name;

  public function __construct($mysqli, $db_name) {
    $this->mysqli  = $mysqli;
    $this->db_name = $db_name;
  }
  
  /**
   * Records a fix in the sys_updates table. This is the new system
   * instead of the old stop files.
   * @param string $name - The name of update to be inserted.
   */
  public function record_update($name) {
    $result  = $this->mysqli->prepare('INSERT INTO sys_updates VALUES (?, NOW())');
    $result->bind_param('s', $name);
    $result->execute();
    $result->close();
  }

  /**
   * Determines if an update has already been applied to the system.
   *
   * @param string $name - The name of update to be tested.
   * @return bool - True = fix has been applied, False = it hasn't.
   */
  public function has_updated($name) {
    $result  = $this->mysqli->prepare('SELECT name FROM sys_updates WHERE name = ?');
    $result->bind_param('s', $name);
    $result->execute();
    $result->store_result();
    $num_rows =  $result->num_rows;
    $result->close();

    if ($num_rows < 1) {
      return false;
    }

    return true;
  }

  public function count_rows($sql) {
    $result  = $this->mysqli->prepare($sql);
    $result->execute();
    $result->store_result();
    $num_rows = $result->num_rows;

    return $num_rows;
  }
  
  /**
   * Determines if a table exists in the database.
   *
   * @param string $table_name - The name of the table to be tested.
   *
   * @return bool - True = table exists, False = table does not exist.
   */
  public function does_table_exist($table_name) {
    $result  = $this->mysqli->prepare('SELECT table_name FROM information_schema.tables WHERE table_schema = ? AND table_name = ?');
    $result->bind_param('ss', $this->db_name, $table_name);
    $result->execute();
    $result->store_result();
    $num_rows =  $result->num_rows;

    $result->close();

    if ($num_rows < 1) {
      return false;
    }

    return true;
  }

  /**
   * Determines if a table, field and field type all exist in the database.
   *
   * @param string $table_name  			- The name of the table to be tested.
   * @param string $column_name 			- The name of the field to be tested.
   * @param string $column_type_value - The type of the field to be tested.
   *
   * @return bool - True = the table, field and field type are all match in the database.
   */
	 public function does_column_type_value_exist($table_name, $column_name, $column_type_value) {
    $result = $this->mysqli->prepare('SELECT column_type FROM information_schema.columns WHERE table_schema = ? AND table_name = ? AND column_name = ? AND column_type = ?');
    $result->bind_param('ssss', $this->db_name, $table_name, $column_name, $column_type_value);
    $result->execute();
    $result->store_result();
    $num_rows =  $result->num_rows;

    $result->close();

    if ($num_rows < 1) {
      return false;
    }

    return true;
  }
  
  public function is_column_nullable($table_name, $column_name) {
    $result = $this->mysqli->prepare('SELECT IS_NULLABLE FROM information_schema.columns WHERE table_schema = ? AND table_name = ? AND column_name = ?');
    $result->bind_param('sss', $this->db_name, $table_name, $column_name);
    $result->execute();
    $result->store_result();
    $result->bind_result($is_nullable);
    $result->close();

    if ($is_nullable == 'NO') {
      return false;
    } else {
      return true;
    }
  }

  /**
   * Determines if a table and field exist in the database.
   *
   * @param string $table_name  - The name of the table to be tested.
   * @param string $column_name - The name of the field to be tested.
   *
   * @return bool - True = the table/field exists in the database.
   */
	 public function does_column_exist($table_name, $column_name) {
    $result = $this->mysqli->prepare('SELECT column_name FROM information_schema.columns WHERE table_schema = ? AND table_name = ? AND column_name = ?');
    $result->bind_param('sss', $this->db_name, $table_name, $column_name);
    $result->execute();
    $result->store_result();
    $num_rows =  $result->num_rows;

    $result->close();

    if ($num_rows < 1) {
      return false;
    }

    return true;
  }

  /**
   * Determines if an index exists for a given table.
   *
   * @param string $table_name - The name of the table to be tested.
   * @param string $index_name - The name of the index to be tested.
   *
   * @return bool - True = the index exists.
   */
  public function does_index_exist($table_name, $index_name) {
    $result = $this->mysqli->prepare("SHOW INDEXES IN $table_name WHERE key_name = ?");
    $result->bind_param('s', $index_name);
    $result->execute();
    $result->store_result();
    $num_rows =  $result->num_rows;

    $result->close();

    if ($num_rows < 1) {
      return false;
    }

    return true;
  }

  /**
   * Determines if an index exists for a given table.
   *
   * @param string $table_name   - The name of the table to be tested.
   * @param string $index_name   - The name of the index to be tested.
   * @param string $index_column - Name of the column being indexed.
   * @param int $index_sequence  - The column sequence number in the index (optional).
   *
   * @return bool - True = the index exists.
   */
	public function does_index_column_exist($table_name, $index_name, $index_column, $index_sequence = NULL) {
		if (!is_null($index_sequence)) {
			$result = $this->mysqli->prepare("SHOW INDEXES IN $table_name WHERE key_name = ? AND column_name = ? and seq_in_index = ?");
			$result->bind_param('sss', $index_name, $index_column, $index_sequence);
		} else {
			$result = $this->mysqli->prepare("SHOW INDEXES IN $table_name WHERE key_name = ? AND column_name = ?");
			$result->bind_param('ss', $index_name, $index_column);
		}
		$result->execute();
		$result->store_result();
		$num_rows =  $result->num_rows;

		$result->close();

		if ($num_rows < 1) {
			return false;
		}

		return true;
	}

  /**
   * Checks if a particular DB user has a grant on a named table.
   *
   * @param string $user  - The database user.
   * @param string $grant - The grant to be tested.
   * @param string $table - The database table.
   * @param string $host  - The database host name.
   *
   * @return bool - True = the grant exists for that user on the specified table.
   */
	 public function has_grant($user, $grant, $table, $host) {
    $found_grant = '';

    $result = $this->mysqli->query("SHOW GRANTS FOR '$user'@'$host'");
    echo $this->mysqli->error;

    if (!is_object($result)) {
      return false;
    }
    while ($existing_grant = $result->fetch_array()) {
      if (stripos($existing_grant[0], ".`$table` TO") !== false) {
        $found_grant = $existing_grant[0];
      }
    }
    $result->close();

    if ($found_grant != '') {
      $parts = explode(' ON ', $found_grant);
      $found_grant = $parts[0];
      $found_grant = str_replace('GRANT ', '', $found_grant);
    }

    if ($found_grant == $grant) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Runs an SQL statement against the database.
   *
   * @param string $sql  					- The SQL statement to run.
   * @param bool $update_display 	- If true then echo the SQL to the screen.
   */
  public function execute_query($sql, $update_display) {
    $insertID = false;
    
    if ($update_display) {
      echo "<li>$sql&hellip;";
      ob_flush();
      flush();
    }

    $this->mysqli->query($sql);

    if ($this->mysqli->errno == 0) {
      $insertID = $this->mysqli->insert_id;
      if ($update_display) {
        echo "Done</li>\n";
      }
    } elseif ($this->mysqli->warning_count > 0) {
      if ($update_display) echo '</li>';
      echo '<li class="warning">WARNING: ' . $sql;
      $e = $this->mysqli->get_warnings();
      do {
        echo "<br />Warning No: $e->errno: - $e->message\n";
      } while ($e->next());
      echo "</li>\n";
    } else {
      if ($update_display) echo '</li>';
      echo '<li class="error">ERROR: ' . $sql;
      if ($this->mysqli->error) {
        try {
          $err = $this->mysqli->error;
          $mess = $this->mysqli->errno;
          throw new Exception("MySQL error $err", $mess);
        } catch (Exception $e) {
          echo "<br />Error No: " . $e->getCode() . " - " . $e->getMessage();
        }
      }
      echo "</li>\n";
    }

    ob_flush();
    flush();
    
    return $insertID;
  }

  /**
   * Changes the version number in /config/config.inc.php.
   *
   * @param string $version - The new version number to set.
   * @param string $string 	- Language translations.
   * @param string $cfg_web_root - Path to the root of Rogo.
   *
   * @return bool - True = the config file is written correctly, False = file could not be written.
   */
	 public function update_version($version, $string, $cfg_web_root) {
    $cfg_new = array();
    $cfg = file($cfg_web_root . 'config/config.inc.php');
    foreach ($cfg as $line) {
      if (strpos($line, 'rogo_version') !== false) {
        $cfg_new[] = "\$rogo_version = '$version';\n";
      } else {
        $cfg_new[] = $line;
      }
    }
    if (file_put_contents($cfg_web_root . 'config/config.inc.php', $cfg_new) === false) {
      return $string['couldnotwrite'];
    } else {
      return true;
    }
  }

  /**
   * Adds a new line to /config/config.inc.php if not already there.
   *
   * @param string $string 				- Language translations.
   * @param string $search  			- A string to look for to see if the new lines already exist
   * @param array $new_lines 		- An array of new lines to insert.
   * @param int $default_line 		- Default line number to add to if no $target_line is found
   * @param string $cfg_web_root 	- Path to the root of Rogo.
   * @param string $target_line 	- A string to find on a target line to act as a location for the new lines
   * @param int $offset 					- A plus or negative offset from $target_line to insert the new lines
   */
  public function add_line($string, $search, $new_lines, $default_line, $cfg_web_root, $target_line = '', $offset = 1) {
    $file_path = $cfg_web_root . 'config/config.inc.php';
    $cfg = file($file_path);
    $found = false;
    $line_no = 0;
    foreach ($cfg as $line) {
      if (strpos($line, $search) !== false) {
        $found = true;
      }
      if ($target_line != '' and strpos($line, $target_line) !== false) {
        $default_line = $line_no + $offset;
      }
      $line_no++;
    }

    if (!$found) {
      array_splice($cfg, $default_line, 0, $new_lines);

      if (file_put_contents($cfg_web_root . 'config/config.inc.php', $cfg) === false) {
        echo "<li class=\"error\">" . $string['couldnotwrite'] . "</li>";
      }
      echo "<li>" . sprintf($string['addinglines'], $file_path) . "<br />\n";
      foreach ($new_lines as $new_line) {
        echo highlight_string($new_line, true) . "\n";
      }
      echo "</li>\n";
      ob_flush();
      flush();
    }
  }

  /**
   * replaces a line in /config/config.inc.php if found.
   *
   * @param string $string 				- Language translations.
   * @param string $replace 			- A string to replace
   * @param string $new_line   		- A  new line to insert.
   * @param string $cfg_web_root 	- Path to the root of Rogo.
   */
  public function replace_line($string, $replace, $new_line, $cfg_web_root) {
    $file_path = $cfg_web_root . 'config/config.inc.php';
    $cfg = file($file_path);
    $found = false;
    $line_no = 0;
    foreach ($cfg as $key=>$line) {
      if (strpos($line, $replace) !== false) {
        $found = true;
        $founndloc=$line_no;
      }
      $line_no++;
    }

    if ($found) {
      $cfg[$founndloc]=$new_line;

      if (file_put_contents($cfg_web_root . 'config/config.inc.php', $cfg) === false) {
        echo "<li class=\"error\">" . $string['couldnotwrite'] . "</li>";
      }
      echo "<li>" . sprintf($string['replacinglines'], $file_path) . "<br />\n";


      echo highlight_string("$replace\r\n with\r\n$new_line", true) . "\n";

      echo "</li>\n";
      ob_flush();
      flush();
    }
  }

  /**
   * Takes a backup of the configuration file.
   *
   * @param string $cfg_web_root	- Path to the root of Rogo.
   * @param string $old_version		- Uses the old version of Rogo to make the backup filename.
   */
	 public function backup_file($cfg_web_root, $old_version) {
    if (file_exists($cfg_web_root . 'config/config.inc.php')) {
      copy ($cfg_web_root . 'config/config.inc.php', $cfg_web_root . 'config/config.inc.' . $old_version . '.php');
    }
  }

}































