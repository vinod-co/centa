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
* Rogō caculation question unit tests for phpeval.
* 
* @author Anthony Brown
* @version 1.0
* @copyright Copyright (c) 2012 The University of Nottingham
* @package
*/

global $cfg_web_root;
require $cfg_web_root . 'plugins/questions/enhancedcalc/phpEval.php';

class phpEvalTests extends \Enhance\TestFixture
{

  private $target;    

  // SetUp
  public function setUp() {
    $this->target = \Enhance\Core::getCodeCoverageWrapper('EnhancedCalc_phpEval', array(array()));
  }

  // TearDown
  public function tearDown() {
  
  }
  
  #
  # connect
  #
  public function connect() {
      //$enhancedcalculation = array('host' => 'suivarro.nottingham.ac.uk', 'port'=>6311,'timeout'=>5);
      //$obj = new enhancedcalc_rserve($enhancedcalculation);
      
      //$enhancedcalculation = array('host' => 'suivarro.nottingham.ac.uk', 'port'=>8080,'timeout'=>5);
      //$enhancedcalculation = array('host' => 'this.will.not.work.com', 'port'=>6311,'timeout'=>5);
  }
  
  public function test_calc_dp() {
      $res = $this->target->calc_dp('0');
      \Enhance\Assert::areIdentical(0, $res);
      
      $res = $this->target->calc_dp('10');
      \Enhance\Assert::areIdentical(0, $res);
      
      $res = $this->target->calc_dp('10000');
      \Enhance\Assert::areIdentical(0, $res);
      
      $res = $this->target->calc_dp('0.1');
      \Enhance\Assert::areIdentical(1, $res);
      
      $res = $this->target->calc_dp('10.1');
      \Enhance\Assert::areIdentical(1, $res);
      
      $res = $this->target->calc_dp('10000.1');
      \Enhance\Assert::areIdentical(1, $res);
      
      
      $res = $this->target->calc_dp('0.1000');
      \Enhance\Assert::areIdentical(4, $res);
      
      $res = $this->target->calc_dp('10.1000');
      \Enhance\Assert::areIdentical(4, $res);
      
      $res = $this->target->calc_dp('10000.1000');
      \Enhance\Assert::areIdentical(4, $res);
      
      
      $res = $this->target->calc_dp('1.13e22');
      \Enhance\Assert::areIdentical(2, $res);
      
      $res = $this->target->calc_dp('10.13e-22');
      \Enhance\Assert::areIdentical(2, $res);
      
      $res = $this->target->calc_dp('1000.13e22');
      \Enhance\Assert::areIdentical(2, $res);
  }
  
  public function test_calc_sf() {
      $res = $this->target->calc_sf('0');
      \Enhance\Assert::areIdentical(1, $res);
      
      $res = $this->target->calc_sf('100');
      \Enhance\Assert::areIdentical(3, $res);
      
      $res = $this->target->calc_sf('100.0001');
      \Enhance\Assert::areIdentical(7, $res);
      
      $res = $this->target->calc_sf('1e24');
      \Enhance\Assert::areIdentical(1, $res);
      
      $res = $this->target->calc_sf('1.2e24');
      \Enhance\Assert::areIdentical(2, $res);
      
      $res = $this->target->calc_sf('1.221345243e24');
      \Enhance\Assert::areIdentical(10, $res);
      
      $res = $this->target->calc_sf('1.221345243e-24');
      \Enhance\Assert::areIdentical(10, $res);
  }
  
  #
  # calculate_correct_ans
  #
  public function test_calculate_correct_ans() {
      $var = array('$A'=>'1','$B'=>'1');
      $formula = '$A + $B';
      $res = $this->target->calculate_correct_ans($var, $formula);
      \Enhance\Assert::areIdentical("2", $res);
      
      $formula = '$A - $B';
      $res = $this->target->calculate_correct_ans($var, $formula);
      \Enhance\Assert::areIdentical("0", $res);
      
      $formula = '$A * $B';
      $res = $this->target->calculate_correct_ans($var, $formula);
      \Enhance\Assert::areIdentical("1", $res);
      
      //small numbers
      $var = array('$A'=>'1.5e-34','$B'=>'1.5e-34');
      $formula = '$A + $B';
      $res = $this->target->calculate_correct_ans($var, $formula);
      \Enhance\Assert::areIdentical("3e-34", $res);
      
      $formula = '$A - $B';
      $res = $this->target->calculate_correct_ans($var, $formula);
      \Enhance\Assert::areIdentical("0", $res);
      
      $formula = '$A * $B';
      $res = $this->target->calculate_correct_ans($var, $formula);
      \Enhance\Assert::areIdentical("2.25e-68", $res);
      
      $formula = '$A / $B';
      $res = $this->target->calculate_correct_ans($var, $formula);
      \Enhance\Assert::areIdentical("1", $res);
      
      //large number
      $var = array('$A'=>'1.5e34','$B'=>'1.5e34');
      $formula = '$A + $B';
      $res = $this->target->calculate_correct_ans($var, $formula);
      \Enhance\Assert::areIdentical("3e+34", $res);
      
      $formula = '$A - $B';
      $res = $this->target->calculate_correct_ans($var, $formula);
      \Enhance\Assert::areIdentical("0", $res);
      
      $formula = '$A * $B';
      $res = $this->target->calculate_correct_ans($var, $formula);
      \Enhance\Assert::areIdentical("2.25e+68", $res);
      
      $formula = '$A / $B';
      $res = $this->target->calculate_correct_ans($var, $formula);
      \Enhance\Assert::areIdentical("1", $res);
      
      //test POW function 
      $var = array('$A'=>'1.5e34');
      $formula = 'pow($A,2)';
      $res = $this->target->calculate_correct_ans($var, $formula);
      \Enhance\Assert::areIdentical("2.25e+68", $res);
      $var = array('$A'=>'1.5e-34');
      $formula = 'POW($A,2)';
      $res = $this->target->calculate_correct_ans($var, $formula);
      \Enhance\Assert::areIdentical("2.25e-68", $res);
      
      //lage number with dp
      $var = array('$A'=>'0.0032', '$B' => '234324234' );
      $formula = '$A + $B';
      $res = $this->target->calculate_correct_ans($var, $formula);
      \Enhance\Assert::areIdentical("234324234.0032", $res);
      
      //Invalid awnser
      $error = '';
      try {
        $var = array('$A'=>'1.5e--i34', '$B'=>'1.5e-34');
        $formula = '$A - $B';
        $res = $this->target->calculate_correct_ans($var, $formula);
      } catch(Exception $e) {
        $error = $e->getMessage();
      }
      \Enhance\Assert::areIdentical("unable to evaluate", $error);
      
      //devision by zero
      /*$error = '';
      try {
        $var = array('$A'=>'1.5e-34', '$B'=>'0');
        $formula = '$A / $B';
        $res = $this->target->calculate_correct_ans($var, $formula);
      } catch(Exception $e) {
        $error = $e->getMessage();
        echo $error;
      }
      \Enhance\Assert::areIdentical("unable to evaluate", $error);*/
  }
  
  public function test_is_useranswer_correct() {
      
      //correct
      $res = $this->target->is_useranswer_correct('1','1', false);
      \Enhance\Assert::areIdentical(true, $res);
      $res = $this->target->is_useranswer_correct('1.3e55','1.3e55', false);
      \Enhance\Assert::areIdentical(true, $res);
      $res = $this->target->is_useranswer_correct('1.000','1.0', false);
      \Enhance\Assert::areIdentical(true, $res);
      $res = $this->target->is_useranswer_correct('1.999999','1.999999000', false);
      \Enhance\Assert::areIdentical(true, $res);
      $res = $this->target->is_useranswer_correct('23E-24','23e-24', false);
      \Enhance\Assert::areIdentical(true, $res);
      
      //incorrect
      $res = $this->target->is_useranswer_correct('1','2', false);
      \Enhance\Assert::areIdentical(false, $res);
      $res = $this->target->is_useranswer_correct('1.3e55','1.4e55', false);
      \Enhance\Assert::areIdentical(false, $res);
      $res = $this->target->is_useranswer_correct('1.0001','1.0', false);
      \Enhance\Assert::areIdentical(false, $res);
      $res = $this->target->is_useranswer_correct('1.9999991','1.999999000', false);
      \Enhance\Assert::areIdentical(false, $res);
      $res = $this->target->is_useranswer_correct('23E-22','23e-24', false);
      \Enhance\Assert::areIdentical(false, $res);
      
      //invlaid input
      $res = $this->target->is_useranswer_correct('23E- 22','23e-24', false);
      \Enhance\Assert::areIdentical(false, $res);
      $res = $this->target->is_useranswer_correct('sdfd','23e-24', false);
      \Enhance\Assert::areIdentical(false, $res);
      $res = $this->target->is_useranswer_correct('','23e-24', false);
      \Enhance\Assert::areIdentical(false, $res);
  }
  
  
  public function test_is_useranswer_correct_with_round_to_stundent_precision() {
      
      //correct
      $res = $this->target->is_useranswer_correct('1','1.1', true);
      \Enhance\Assert::areIdentical(true, $res);
      
      $res = $this->target->is_useranswer_correct('1.11','1.1', true);
      \Enhance\Assert::areIdentical(false, $res);
      
      $res = $this->target->is_useranswer_correct('1','1.6', true);
      \Enhance\Assert::areIdentical(false, $res);
      
      $res = $this->target->is_useranswer_correct('1','1.4', true);
      \Enhance\Assert::areIdentical(true, $res);
      
      $res = $this->target->is_useranswer_correct('1.400','1.4', true);
      \Enhance\Assert::areIdentical(true, $res);
      
      $res = $this->target->is_useranswer_correct('0.33333','0.333333333333333', true);
      \Enhance\Assert::areIdentical(true, $res);
      
      $res = $this->target->is_useranswer_correct('0.33334','0.333339933333333', true);
      \Enhance\Assert::areIdentical(true, $res);
      
      $res = $this->target->is_useranswer_correct('0.3333399e34','0.333339912312312e34', true);
      \Enhance\Assert::areIdentical(true, $res);
      
      $res = $this->target->is_useranswer_correct('0.33334e35','0.333339933333333e34', true);
      \Enhance\Assert::areIdentical(false, $res);
      
      $res = $this->target->is_useranswer_correct('3.3334e3','3333.44456', true);
      \Enhance\Assert::areIdentical(true, $res);
      
      $res = $this->target->is_useranswer_correct('3.3334e3','3333.999', true);
      \Enhance\Assert::areIdentical(false, $res);
      
      /*$res = $this->target->is_useranswer_correct('1.3e55','1.3e55', true);
      \Enhance\Assert::areIdentical(true, $res);
      $res = $this->target->is_useranswer_correct('1.000','1.0', true);
      \Enhance\Assert::areIdentical(true, $res);
      $res = $this->target->is_useranswer_correct('1.999999','1.999999000', true);
      \Enhance\Assert::areIdentical(true, $res);
      $res = $this->target->is_useranswer_correct('23E-24','23e-24', true);
      \Enhance\Assert::areIdentical(true, $res);
      
      //incorrect
      $res = $this->target->is_useranswer_correct('1','2', true);
      \Enhance\Assert::areIdentical(false, $res);
      $res = $this->target->is_useranswer_correct('1.3e55','1.4e55', true);
      \Enhance\Assert::areIdentical(false, $res);
      $res = $this->target->is_useranswer_correct('1.0001','1.0', true);
      \Enhance\Assert::areIdentical(false, $res);
      $res = $this->target->is_useranswer_correct('1.9999991','1.999999000', true);
      \Enhance\Assert::areIdentical(false, $res);
      $res = $this->target->is_useranswer_correct('23E-22','23e-24', true);
      \Enhance\Assert::areIdentical(false, $res);
      
      //invlaid input
      $res = $this->target->is_useranswer_correct('23E- 22','23e-24', true);
      \Enhance\Assert::areIdentical(false, $res);
      $res = $this->target->is_useranswer_correct('sdfd','23e-24', true);
      \Enhance\Assert::areIdentical(false, $res);
      $res = $this->target->is_useranswer_correct('','23e-24', true);
      \Enhance\Assert::areIdentical(false, $res);*/
  }
  
  public function distance_from_correct_answer() {
      
      $res = $this->target->distance_from_correct_answer('99','100');
      \Enhance\Assert::areIdentical('1', $res);
      $res = $this->target->distance_from_correct_answer('1','100');
      \Enhance\Assert::areIdentical('99', $res);
      
      //large
      $res = $this->target->distance_from_correct_answer('23.2e33','23.1e33');
      \Enhance\Assert::areIdentical('0.433', $res);
      $res = $this->target->distance_from_correct_answer('23.1e33','23.34e33');
      \Enhance\Assert::areIdentical('1.028', $res);
      
      //small
      $res = $this->target->distance_from_correct_answer('23.2e-33','23.1e-33');
      \Enhance\Assert::areIdentical('0.433', $res);
      $res = $this->target->distance_from_correct_answer('23.1e-33','23.34e-33');
      \Enhance\Assert::areIdentical('1.028', $res);
      
      //invalid
       $res = $this->target->distance_from_correct_answer('','100');
      \Enhance\Assert::areIdentical('ERROR', $res);
      $res = $this->target->distance_from_correct_answer('1e--3','100');
      \Enhance\Assert::areIdentical('ERROR', $res);
  }
  
  public function test_calculate_tolerance_percent() {
      
     $res = $this->target->calculate_tolerance_percent('100','1');
     \Enhance\Assert::areIdentical('1', $res['tolerance']);
     \Enhance\Assert::areIdentical('101', $res['tolerance_ans']);
     \Enhance\Assert::areIdentical('99', $res['tolerance_ansneg']);
     
     $res = $this->target->calculate_tolerance_percent('200','10');
     \Enhance\Assert::areIdentical('20', $res['tolerance']);
     \Enhance\Assert::areIdentical('220', $res['tolerance_ans']);
     \Enhance\Assert::areIdentical('180', $res['tolerance_ansneg']);
     
     $res = $this->target->calculate_tolerance_percent('1.1314e-34','1');
     \Enhance\Assert::areIdentical('1.1314e-36', $res['tolerance']);
     \Enhance\Assert::areIdentical('1.142714e-34', $res['tolerance_ans']);
     \Enhance\Assert::areIdentical('1.120086e-34', $res['tolerance_ansneg']);
     
     $res = $this->target->calculate_tolerance_percent('1.1314e34','1');
     \Enhance\Assert::areIdentical('1.1314e+32', $res['tolerance']);
     \Enhance\Assert::areIdentical('1.142714e+34', $res['tolerance_ans']);
     \Enhance\Assert::areIdentical('1.120086e+34', $res['tolerance_ansneg']);
  }
  
  public function test_calculate_tolerance_absolute() {
      
      $res = $this->target->calculate_tolerance_absolute('1000','1');
      \Enhance\Assert::areIdentical('1', $res['tolerance']);
      \Enhance\Assert::areIdentical('1001', $res['tolerance_ans']);
      \Enhance\Assert::areIdentical('999', $res['tolerance_ansneg']);
      
      $res = $this->target->calculate_tolerance_absolute('1000','10');
      \Enhance\Assert::areIdentical('10', $res['tolerance']);
      \Enhance\Assert::areIdentical('1010', $res['tolerance_ans']);
      \Enhance\Assert::areIdentical('990', $res['tolerance_ansneg']);
      
      $res = $this->target->calculate_tolerance_absolute('1e34','1e4');
      \Enhance\Assert::areIdentical('1e4', $res['tolerance']);
      \Enhance\Assert::areIdentical('1e+34', $res['tolerance_ans']);
      \Enhance\Assert::areIdentical('1e+34', $res['tolerance_ansneg']);
      
  }
  
  public function test_is_useranswer_within_tolerance() {
      
      $ans = '1';
      $min = '0';
      $max = '2';
      $res = $this->target->is_useranswer_within_tolerance($ans, $min, $max);
      \Enhance\Assert::areIdentical(true,$res);
      
      $ans = '0';
      $min = '0';
      $max = '2';
      $res = $this->target->is_useranswer_within_tolerance($ans, $min, $max);
      \Enhance\Assert::areIdentical(true,$res);
      
      $ans = '-1';
      $min = '0';
      $max = '2';
      $res = $this->target->is_useranswer_within_tolerance($ans, $min, $max);
      \Enhance\Assert::areIdentical(false,$res);
      
      $ans = '1000001';
      $min = '1000000.999999';
      $max = '1000001.000001';
      $res = $this->target->is_useranswer_within_tolerance($ans, $min, $max);
      \Enhance\Assert::areIdentical(true,$res);
      
      $ans = '1.5e34';
      $min = '1.4e34';
      $max = '1e35';
      $res = $this->target->is_useranswer_within_tolerance($ans, $min, $max);
      \Enhance\Assert::areIdentical(true,$res);
      
      $ans = '1e-34';
      $min = '1.9e34';
      $max = '1e35';
      $res = $this->target->is_useranswer_within_tolerance($ans, $min, $max);
      \Enhance\Assert::areIdentical(false,$res);
      
      $ans = '-4.1';
      $min = '-12';
      $max = '-4';
      $res = $this->target->is_useranswer_within_tolerance($ans, $min, $max);
      \Enhance\Assert::areIdentical(true,$res);
      
  }
  
  public function test_is_useranswer_within_significant_figures() {
      $answer = '1000';
      $sf = '3';
      $res = $this->target->is_useranswer_within_significant_figures($answer, $sf);
      \Enhance\Assert::areIdentical(true,$res);
      
      $answer = '1001';
      $sf = '3';
      $res = $this->target->is_useranswer_within_significant_figures($answer, $sf);
      \Enhance\Assert::areIdentical(false,$res);
      
      $answer = '10001';
      $sf = '3';
      $res = $this->target->is_useranswer_within_significant_figures($answer, $sf);
      \Enhance\Assert::areIdentical(false,$res);
      
      $answer = '12340';
      $sf = '3';
      $res = $this->target->is_useranswer_within_significant_figures($answer, $sf);
      \Enhance\Assert::areIdentical(false,$res);
      
      $answer = '-12340';
      $sf = '3';
      $res = $this->target->is_useranswer_within_significant_figures($answer, $sf);
      \Enhance\Assert::areIdentical(false,$res);
      
      $answer = '12300';
      $sf = '3';
      $res = $this->target->is_useranswer_within_significant_figures($answer, $sf);
      \Enhance\Assert::areIdentical(true,$res);
      
      $answer = '-12300';
      $sf = '3';
      $res = $this->target->is_useranswer_within_significant_figures($answer, $sf);
      \Enhance\Assert::areIdentical(true,$res);
  }
  
  public function test_is_useranswer_correct_decimal_places() {
      
      $useranswer = '1.1314';
      $dp = '4';
      $res = $this->target->is_useranswer_correct_decimal_places($useranswer, $dp);
      \Enhance\Assert::areIdentical(true,$res);
      
      $useranswer = '1.13140000000';
      $dp = '4';
      $res = $this->target->is_useranswer_correct_decimal_places($useranswer, $dp);
      \Enhance\Assert::areIdentical(true,$res);
      
      $useranswer = '1.13';
      $dp = '4';
      $res = $this->target->is_useranswer_correct_decimal_places($useranswer, $dp);
      \Enhance\Assert::areIdentical(true,$res);
      
      $useranswer = '-1.13';
      $dp = '1';
      $res = $this->target->is_useranswer_correct_decimal_places($useranswer, $dp);
      \Enhance\Assert::areIdentical(false,$res);
      
      //$useranswer = '1.13333e34';
      //$dp = '2';
      //$res = $this->target->is_useranswer_correct_decimal_places($useranswer, $dp);
      //\Enhance\Assert::areIdentical(true,$res);
      
      //$useranswer = '1.13e34';
      //$dp = '2';
      //$res = $this->target->is_useranswer_correct_decimal_places($useranswer, $dp);
      //\Enhance\Assert::areIdentical(true,$res);
      
      //$useranswer = '1.13232345e3';
      //$dp = '2';
      //$res = $this->target->is_useranswer_correct_decimal_places($useranswer, $dp);
      //\Enhance\Assert::areIdentical(false,$res);
  }
  
  public function test_is_useranswer_correct_decimal_places_strictzeros() {
      
      $useranswer = '1';
      $dp = '0';
      $res = $this->target->is_useranswer_correct_decimal_places_strictzeros($useranswer, $dp);
      \Enhance\Assert::areIdentical(true,$res);
      
      $useranswer = '1.00';
      $dp = '0';
      $res = $this->target->is_useranswer_correct_decimal_places_strictzeros($useranswer, $dp);
      \Enhance\Assert::areIdentical(false,$res);
      
      $useranswer = '1.000';
      $dp = '3';
      $res = $this->target->is_useranswer_correct_decimal_places_strictzeros($useranswer, $dp);
      \Enhance\Assert::areIdentical(true,$res);
      
      $useranswer = '1.00';
      $dp = '3';
      $res = $this->target->is_useranswer_correct_decimal_places_strictzeros($useranswer, $dp);
      \Enhance\Assert::areIdentical(false,$res);
      
      $useranswer = '1.000e-25';
      $dp = '0';
      $res = $this->target->is_useranswer_correct_decimal_places_strictzeros($useranswer, $dp);
      \Enhance\Assert::areIdentical(false,$res);
      
      $useranswer = '1.000000e3';
      $dp = '3';
      $res = $this->target->is_useranswer_correct_decimal_places_strictzeros($useranswer, $dp);
      \Enhance\Assert::areIdentical(false,$res);
     
  }
  
  function test_is_engineering_format() {
      $useranswer = '1.000000e3';
      $res = $this->target->is_engineering_format($useranswer);
      \Enhance\Assert::areIdentical(true,$res);
      
      $useranswer = '2300e-4';
      $res = $this->target->is_engineering_format($useranswer);
      \Enhance\Assert::areIdentical(true,$res);
      
      $useranswer = '0.00001e3';
      $res = $this->target->is_engineering_format($useranswer);
      \Enhance\Assert::areIdentical(true,$res);
      
      $useranswer = '0.00001';
      $res = $this->target->is_engineering_format($useranswer);
      \Enhance\Assert::areIdentical(false,$res);
      
      $useranswer = '12434.09239';
      $res = $this->target->is_engineering_format($useranswer);
      \Enhance\Assert::areIdentical(false,$res);
      
      $useranswer = '1';
      $res = $this->target->is_engineering_format($useranswer);
      \Enhance\Assert::areIdentical(false,$res);
      
      $useranswer = '0';
      $res = $this->target->is_engineering_format($useranswer);
      \Enhance\Assert::areIdentical(false,$res);
  }
  
  function test_format_number_dp () {
      $useranswer = '1.3432';
      $dp = '0';
      $res = $this->target->format_number_dp($useranswer,$dp);
      \Enhance\Assert::areIdentical('1',$res);
      
      $useranswer = '1.343200000';
      $dp = '2';
      $res = $this->target->format_number_dp($useranswer,$dp);
      \Enhance\Assert::areIdentical('1.34',$res);
      
      $useranswer = '1.3492';
      $dp = '2';
      $res = $this->target->format_number_dp($useranswer,$dp);
      \Enhance\Assert::areIdentical('1.35',$res);
  }
  
  function test_format_number_dp_strict_zeros () {
      $useranswer = '1.3';
      $dp = '3';
      $res = $this->target->format_number_dp_strict_zeros($useranswer,$dp);
      \Enhance\Assert::areIdentical('1.300',$res);
      
      $useranswer = '1.34';
      $dp = '2';
      $res = $this->target->format_number_dp_strict_zeros($useranswer,$dp);
      \Enhance\Assert::areIdentical('1.34',$res);
      
      $useranswer = '1.3492';
      $dp = '2';
      $res = $this->target->format_number_dp_strict_zeros($useranswer,$dp);
      \Enhance\Assert::areIdentical('1.35',$res);
      
      $useranswer = '1.349000';
      $dp = '4';
      $res = $this->target->format_number_dp_strict_zeros($useranswer,$dp);
      \Enhance\Assert::areIdentical('1.3490',$res);
  }
  
  function test_format_number_sf () {
      
  }
}
?>
