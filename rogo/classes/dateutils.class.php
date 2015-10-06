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
* Utility class for date related functionality
* 
* @author Rob Ingram
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

Class date_utils {
	// Start of academic year (mm/dd)
	public static $academic_year_start = '07/01';
	
	/**
	 * Get the current academic year in the format 'yyyy/yy', e.g. '2013/14'
	 * @return string
	 */
	static function get_current_academic_year($specific_year_start = '')	{
		return date_utils::get_academic_year(date('Y/m/d'), $specific_year_start);
	}

	static function get_next_academic_year($specific_year_start = '')	{
    $session = date_utils::get_academic_year(date('Y/m/d'), $specific_year_start);
    
    $parts = explode('/', $session);
   
    $next_session = ($parts[0] + 1) . '/' . ($parts[1] + 1);
    
		return $next_session;
	}

  static function inc_academic_year($year) {
    $first_part = substr($year, 0, 4);
    $first_part++;
    $second_part = substr($year, 5, 2);
    $second_part++;
    
    $next_year = $first_part . '/' . $second_part;
    
    return $next_year;
  }

	/**
	 * Get the academic year for the given date in the format 'yyyy/yy', e.g. '2013/14'
	 * @param string $date - A date in a format that can be accepted by strtotime
	 *
	 * @return string - The current academic year.
	 */
	static function get_academic_year($date, $specific_year_start) {
    global $configObject;
    
		$date_as_time = strtotime($date);
    if ($specific_year_start != '') {
      $start_this_year = strtotime(date('Y') . '/' . $specific_year_start);
    } elseif ($configObject->get('cfg_academic_year_start') != '') {
      $start_this_year = strtotime(date('Y') . '/' .  $configObject->get('cfg_academic_year_start'));
    } else {
      $start_this_year = strtotime(date('Y') . '/' . self::$academic_year_start);
    }
		if ($date_as_time < $start_this_year) {
			$session = (date('Y') - 1) . '/' . date('y');
		} else {
			$session = date('Y') . '/' . (date('y') + 1);
		}

		return $session;
	}
  
	/**
	 * Creates HTML dropdown menus to select day, month, year and hour (in half hour increments).
	 * @param string $prefix 			- Prefix string to make the name of the selector.
	 * @param string $input_date	- Default time/date to populate the selector.
	 * @param bool $split_time    - False = one dropdown for hours & minutes, True = two separate dropdowns for hours and minutes.
	 * @param int $start_year     - Start year for the year dropdown (e.g. 2001).
	 * @param int $end_year       - End year for the year dropdown (e.g. 2015).
	 *
	 * @return string - The HTML of the time/date selector.
	 */
	static function timedate_select($prefix, $imput_date, $split_time, $start_year, $end_year, $string) {
    $split_year = substr($imput_date,0,4);
    $split_month = substr($imput_date,4,2);
    $split_day = substr($imput_date,6,2);
    $split_hour = substr($imput_date,8,2);
    $split_minute = substr($imput_date,10,2);
    
    $html = '';

    // Day
    $html .= "<select name=\"" . $prefix . "day\" id=\"" . $prefix . "day\">\n";
    for ($i = 1; $i < 32; $i++) {
      if ($i < 10) {
        if ($i == $split_day) {
          $html .= "<option value=\"0$i\" selected>";
        } else {
          $html .= "<option value=\"0$i\">";
        }
      } else {
        if ($i == $split_day) {
          $html .= "<option value=\"$i\" selected>";
        } else {
          $html .= "<option value=\"$i\">";
        }
      }
      if ($i < 10) $html .= '0';
      $html .= "$i</option>\n";
    }
    $html .= "</select>";
    
    // Month
    $html .= "<select name=\"" . $prefix . "month\" id=\"" . $prefix . "month\">\n";
    $months = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
    for ($i=0; $i<12; $i++) {
      $trans_month = mb_substr($string[$months[$i]],0,3,'UTF-8');
      if (($split_month-1) == $i) {
        if ($i < 9) {
          $html .= "<option value=\"0" . ($i+1) . "\" selected>$trans_month</option>\n";
        } else {
          $html .= "<option value=\"" . ($i+1) . "\" selected>$trans_month</option>\n";
        }
      } else {
        if ($i < 9) {
          $html .= "<option value=\"0" . ($i+1) . "\">$trans_month</option>\n";
        } else {
          $html .= "<option value=\"" . ($i+1) . "\">$trans_month</option>\n";
        }
      }
    }
    $html .= "</select>";
    
    // Year
    $html .= "<select name=\"" . $prefix . "year\" id=\"" . $prefix . "year\">";
    for ($i = $start_year; $i <= $end_year; $i++) {
      if ($i == $split_year) {
        $html .= "<option value=\"$i\" selected>$i</option>\n";
      } else {
        $html .= "<option value=\"$i\">$i</option>\n";
      }
    }
    $html .= "</select>";
    
    if ($split_time) {
      $html .= "<select name=\"" . $prefix . "hour\" id=\"" . $prefix . "hour\">\n";
      for ($key=0; $key<24; $key++) {
        if ($key < 10) {
          $key = '0' . $key;
        }
        if ($key == $split_hour) {
          $html .= "<option value=\"" . $key . "\" selected>" . $key . "</option>\n";
        } else {
          $html .= "<option value=\"" . $key . "\">" . $key . "</option>\n";
        }
      }
      $html .= "</select>";
      
      $html .= "<select name=\"" . $prefix . "minute\" id=\"" . $prefix . "minute\">\n";
      for ($key=0; $key<60; $key++) {
        if ($key < 10) {
          $key = '0' . $key;
        }
        if ($key == $split_minute) {
          $html .= "<option value=\"" . $key . "\" selected>" . $key . "</option>\n";
        } else {
          $html .= "<option value=\"" . $key . "\">" . $key . "</option>\n";
        }
      }
      $html .= "</select>";      
      
    } else {
      // Time
      $times = array('000000'=>'00:00','003000'=>'00:30','010000'=>'01:00','013000'=>'01:30','020000'=>'02:00','023000'=>'02:30','030000'=>'03:00','033000'=>'03:30','040000'=>'04:00','043000'=>'04:30','050000'=>'05:00','053000'=>'05:30','060000'=>'06:00','063000'=>'06:30','070000'=>'07:00','073000'=>'07:30','080000'=>'08:00','083000'=>'08:30','090000'=>'09:00','093000'=>'09:30','100000'=>'10:00','103000'=>'10:30','110000'=>'11:00','113000'=>'11:30','120000'=>'12:00','123000'=>'12:30','130000'=>'13:00','133000'=>'13:30','140000'=>'14:00','143000'=>'14:30','150000'=>'15:00','153000'=>'15:30','160000'=>'16:00','163000'=>'16:30','170000'=>'17:00','173000'=>'17:30','180000'=>'18:00','183000'=>'18:30','190000'=>'19:00','193000'=>'19:30','200000'=>'20:00','203000'=>'20:30','210000'=>'21:00','213000'=>'21:30','220000'=>'22:00','223000'=>'22:30','230000'=>'23:00','233000'=>'23:30');
      $html .= "<select name=\"" . $prefix . "time\" id=\"" . $prefix . "time\">\n";
      foreach ($times as $key => $value) {
        if ($key == $split_hour . $split_minute . '00') {
          $html .= "<option value=\"" . $key . "\" selected>" . $value . "</option>\n";
        } else {
          $html .= "<option value=\"" . $key . "\">" . $value . "</option>\n";
        }
      }
      $html .= "</select>";
    }
    
    return $html;
  }

  /**
   * Get the first academic year for which there are papers
   * @param  mysqli $db DB connection
   * @return string   The first academic year or current year if no records found
   */
  public static function get_start_year($db) {
    $result = $db->prepare("SELECT min(calendar_year) FROM properties WHERE calendar_year IS NOT NULL AND calendar_year != ''");
    $result->execute();
    $result->bind_result($start_year);
    $result->store_result();
    $result->fetch();
    if ($result->num_rows == 0) {
      $start_year = self::get_current_academic_year();
    }
    $result->close();

    return $start_year;
  }

  public static function get_all_academic_years($db) {
    $start_ac_year = self::get_start_year($db);
    $end_ac_year = self::get_current_academic_year();

    if ($start_ac_year == $end_ac_year) {
      return array('x' . $start_ac_year);
    }

    $year_parts = explode('/', $start_ac_year);
    $start_year = $year_parts[0];
    $year_sub = $year_parts[1];

    $end_year_parts = explode('/', $end_ac_year);
    $end_year = $end_year_parts[0];

    $years = array();

    do {
      $years[] = $start_year . '/' . sprintf('%02d', $year_sub);
      $start_year++;
      $year_sub = ($year_sub + 1) % 100;
    } while ($start_year < $end_year);

    return $years;
  }
}

?>