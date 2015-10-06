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
 * R based maths functions for Calculation questions
 *
 * @author Simon Atack, Anthony Brown
 * @version 1.0
 * @copyright Copyright (c) 2013 The University of Nottingham
 * @package
 */

require_once('rserve/Connection.php');

class EnhancedCalc_Rrserve {

  protected $impliments_api_calc_version = 1;
  static protected $cnx = false;

  protected $config;
  protected $toStrDefined;
  protected $powDefined;
  
  public $error = false;
  public $error_msg = '';

  function __construct($config) {
    $this->config = $config;
    $this->toStrDefined = false;
    $this->powDefined = false;
  }


  function error_handling($context = null) {
    return error_handling($this);
  }

  function connect() {
   
    $this->reset_error();
    
    if (is_null(self::$cnx)) {
      // Connection failed!
      $this->set_error("Can Not Connect"); 
      return false;
    }
    
    if (self::$cnx === false) {
      try {
        // if the box isnt on this timeout is ignored and is likely to be different
        if (!isset($this->config['timeout'])) {
          $timeoutarray = array('seconds' => 5, 'milliseconds' => 1);
        } else {
          $timeoutarray = array('seconds' => $this->config['timeout'], 'milliseconds' => 1);
        }
        self::$cnx = @new Rserve_Connection($this->config['host'], $this->config['port'], $timeoutarray);
      } catch (exception $except) {
        self::$cnx = null;
        $this->set_error('Can Not Connect');
        return false;
      }

      $this->setup_R();
      return true;
    } else {
      // We are connected
      return true;
    }
  }
  
  function setup_R() {
		self::$cnx->evalString('options(digits=15); 1==1;');
		self::$cnx->evalString('toStr <- function(V) { return(paste(capture.output(print(V)),collapse=\'\n\')) }');
		self::$cnx->evalString('POW <- pow <- function(a,b) { return(a^b) }');
		self::$cnx->evalString('POW <- pow <- function(a,b) { return(a^b) }');
		self::$cnx->evalString('excel_round <- function(x, digits) round(x*(1+1e-15), digits)');
  }
  
  function calculate_correct_ans($vars, $formula) {
    
    if (!$this->connect()) {
      throw new Exception('Cannot Connect');
			return false;
		}
		
		if (!is_array($vars)) {
      throw new Exception('No variables');
			return false;
		}
    
    $varname = array_keys($vars);
    $varvalue = array_values($vars);
    $formula_vars_subed = str_replace($varname, $varvalue, $formula);
    
    $correctanswer = $this->eval_string($formula_vars_subed);
   
    return $correctanswer;
  }
  
  function is_useranswer_correct($useranswer, $correctanswer, $round_to_stundent_precision) {
    
    if ($useranswer == '') {
      return false;
    }
    
    if ($round_to_stundent_precision) {
			if ($this->is_engineering_format($useranswer)) {
				$stundent_precision = $this->calc_sf($useranswer);
				$calc = "signif($correctanswer,$stundent_precision) == $useranswer";
			} else {
				$stundent_precision = $this->calc_dp($useranswer);
				$calc = "excel_round($correctanswer,$stundent_precision) == $useranswer";
			}
    } else {
       $calc = "$correctanswer == $useranswer";
    }
    
    $status = $this->eval_string($calc);
    if ($status === true) {
      return true;
    } else {
      return false;
    }
    
  }
  
  function distance_from_correct_answer($useranswer, $correctanswer) {
    
    if ($useranswer == '') {
      return 'ERROR';
    }

    try {
      $res = $this->eval_string("abs(excel_round((abs($useranswer - $correctanswer)/$correctanswer * 100),3))");
    } catch(Exception $e) {
      // There is an error it can't be correct
      return 'ERROR';
    }

    return $res;
  }
  
  function calculate_tolerance_percent($correctanswer,$percentage) {
    $cmd[] = "$correctanswer * (" . $percentage . "/100)";
    $cmd[] = "$correctanswer * (1 + (" . $percentage . "/100))";
    $cmd[] = "$correctanswer * (1 - (" . $percentage . "/100))";
    
    $result = $this->eval_string_multi($cmd);
    $res['tolerance'] = $result[0];
    
    //
    // Make sure the min and max are correct tolerances on negative numbers causes problems 
    //
    if ($result[1] > $result[2]) {
			$res['tolerance_ans'] = $result[1];
			$res['tolerance_ansneg'] = $result[2];
    } else {
			$res['tolerance_ans'] = $result[2];
			$res['tolerance_ansneg'] = $result[1];
    }
    return $res;
  }
  
  function calculate_tolerance_absolute($correctanswer,$value) {
    
    $cmd[] = "$correctanswer + $value";
    $cmd[] = "$correctanswer - $value";

    $result = $this->eval_string_multi($cmd);

    $res['tolerance'] = $value;
    $res['tolerance_ans'] = $result[0];
    $res['tolerance_ansneg'] = $result[1];
  
    return $res;
  }
  
  function is_useranswer_within_tolerance($useranswer, $min, $max) {
    
    if ($useranswer == '') {
      return false;
    }
    
    try {
      $status = $this->eval_string("$useranswer <= $max & $useranswer >= $min");
    } catch(Exception $e) {
      // There is an error it can't be correct
      return false;
    }
    
    
    if ($status === true) {
      // Correct
      return true;
    } else {
      return false;
    }
  }
  
  function is_useranswer_within_significant_figures($useranswer, $sf) {
    
    if ($useranswer == '') {
      return false;
    }
    
    $status = $this->eval_string("signif($useranswer," . $sf . ") ==  $useranswer");
    if ($status === true) {
      // Correct
      return true;
    } else {
      return false;
    }
  }
  
  function is_useranswer_correct_decimal_places($useranswer, $dp) {
    
    if ($useranswer == '') {
      return false;
    }
    
    $status = $this->eval_string("excel_round($useranswer," . $dp . ") == $useranswer");
    if ($status === true) {
      return true;
    } else {
      return false;
    }
  }
  
  function is_useranswer_correct_decimal_places_strictzeros($useranswer, $dp) {
    
    if ($useranswer == '') {
      return false;
    }
    
    $status = $this->is_useranswer_correct_decimal_places($useranswer, $dp);
    
    if (!$status) {
      return false;
    }
    
    $dps = $this->calc_dp($useranswer);

    if ($dps == $dp) {
      return true;
    } else {
      return false;
    }
    
  }
  
  function calc_dp($num) {
    $dotpos = strpos($num, '.');
    if ($dotpos === false) {
      return 0;
    }
    
    $epos = strpos($num, 'e');
    if ($epos !== false) {
      $end = $epos;
    } else {
      $end = strlen($num);
    }
   
    return $end - ($dotpos + 1);
  }
  
  function calc_sf($num) {
        
    $epos = strpos($num, 'e');
    if ($epos === false) {
      $epos = strlen($num);
    } 
    
    if (strpos($num, '0.') === 0) {
       $epos = $epos - 2;
    } elseif (strpos($num, '.') !== false) {
      $epos = $epos - 1;
    }
   
    return $epos;
  }
  
  function is_engineering_format($num) {
		$epos = stripos($num, 'e');
		if ($epos !== false) {
			return true;
		}
		return false;
  }
  
  function format_number_dp($num,$dp) {
    return $this->eval_string('excel_round(' . $num . ',' . $dp . ')');
  }
  
  function format_number_dp_strict_zeros($num,$dp) {
    return $this->eval_string('format(excel_round(' . $num . ',' . $dp . '), nsmall = ' . $dp . ')');
  }
  
  function format_number_sf($num,$sf) {
    return $this->eval_string('signif(' . $num . ',' . $sf . ')');
  }
  
  function format_number_to_precision_of_other_number($roundme, $likethisone) {
    if ($this->is_engineering_format($likethisone)) {
      $precision = $this->calc_sf($likethisone);
      return $this->format_number_sf($roundme,$precision);
    } else {
      $precision = $this->calc_dp($likethisone);
      return $this->format_number_dp($roundme,$precision);
    }
  }
  
  private function eval_string($val) {
    if (!$this->connect()) {
      return false;
    }
    return $this->extract_value(self::$cnx->evalString('toStr(' . $val . ')'));
  }
  
  private function eval_string_multi($val) {
    if (!$this->connect()) {
      return false;
    }
    $cmd = 'c(';
    foreach ($val as $v) {
      $cmd .= 'toStr(' . $v . '),';
    }
    $cmd = rtrim($cmd, ',');
    $cmd .= ')';
    return $this->extract_value(self::$cnx->evalString($cmd));
  }
  
  private function extract_value($R_rreturn) {
    
    if (!is_array($R_rreturn)) {
      $R_rreturn = explode("\n",$R_rreturn);
    }
    
    $ret = array();
    foreach ($R_rreturn as $key => $val) {
      $val = trim($val);
      if ($val == '') {
        continue;
      } 
      if ($val == '[1] TRUE') {
        $ret[] = true;
      } else if ($val == '[1] FALSE') {
        $ret[] =  false;
      } else {
        $val = str_replace('"', '', $val);
        $pos = strpos($val, ' ');
        $ret[] = substr($val, $pos + 1);
      }
    }
    
    if (count($ret) == 1) {
      return $ret[0];
    } else {
      return $ret;
    }
  }

  private function set_error($msg) {
    $this->error = true;
    $this->error_msg = $msg;
  }
  
  private function reset_error() {
    $this->error = false;
    $this->error_msg = '';
  }
	
	public function get_error() {
    return $this->error_msg;
  }
	
}
