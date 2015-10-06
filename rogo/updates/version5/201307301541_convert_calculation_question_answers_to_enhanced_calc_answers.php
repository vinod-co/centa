<?php

// Your code here

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

require_once $cfg_web_root . 'classes/stringutils.class.php';
$mysqli->autocommit(false);

//error_reporting(E_ALL);
//ini_set('display_errors', 1);


if (!$updater_utils->has_updated('convert_calc_ans_done')) {
//if (!file_exists("./stopfile_convert_calc_ans_done.txt")) {

  $configObj = Config::get_instance();


  $root = $configObj->get('root');

  $enhancedcalcType = $configObj->get('enhancedcalc_type');
  if (!is_null($enhancedcalcType)) {
    require_once $root . '/plugins/questions/enhancedcalc/' . $enhancedcalcType . '.php';
    $name = 'enhancedcalc_' . $enhancedcalcType;
    $enhancedcalcObj = new $name($configObj->getbyref('enhancedcalculation'));
  } else {
    require_once $root . '/plugins/questions/enhancedcalc/Rrserve.php';
    $enhancedcalcObj = new EnhancedCalc_Rrserve($configObj->getbyref('enhancedcalculation'));
  }


    echo "<li>Converting Calculation answers to enhanced calculation answers</li>";

    set_time_limit(0);
    $LOG = '0';
    $logarray = array('0', '1', '2', '3', '0_deleted', '1_deleted', '_late');

    foreach ($logarray as $LOG) {
        $loop = 0;

        $sql = "select questions.q_id,id,user_answer,settings from questions,log$LOG where q_type='enhancedcalc' and questions.q_id=log$LOG.q_id;";

        $result = $mysqli->prepare("$sql");
        $result->execute();
        $result->store_result();
        $result->bind_result($qid, $uid, $user_answer, $settings);

        $vars = array('$A', '$B', '$C', '$D', '$E', '$F', '$G', '$H', '$I', '$J', '$K', '$L');
        
        //perpair the update once!
        $sql = "UPDATE log$LOG set user_answer=? where id=?";
        $update = $mysqli->prepare("$sql");
                
        while ($result->fetch()) {
            if (strpos($user_answer, '{') === false) {
                print '.';
                unset($statusdata);
                unset($ansdata);
                unset($tmp_answer);
                unset($new_user_answer);
                unset($variable_array);
                unset($varsdata);
                unset($jsoned);
                
                $settings = json_decode($settings, true);

                $tmp_answer = explode('|', $user_answer);


                //[0] is user answer, [1] is correct answer, [2] is array variables
                if (!isset($settings['answers'][0]['units'])) {
                    $settings['answers'][0]['units'] = '';
                }
                $new_user_answer['uans'] = $tmp_answer[0] . ' ' . $settings['answers'][0]['units'];
                $new_user_answer['uansunit'] = $settings['answers'][0]['units'];
                $new_user_answer['uansnumb'] = $tmp_answer[0];
                if (!isset($tmp_answer[1])) {
                    $tmp_answer[1] = '';
                }
                $new_user_answer['cans'] = $tmp_answer[1];
                $ansdata['units_used'] = $settings['answers'][0]['units'];
                $ansdata['guessedunits'] = $settings['answers'][0]['units'];

                $tolerance_full = $settings['tolerance_full'];
                if ($settings['fulltoltyp'] == '%') {
                    $tolerance_perc = rtrim($tolerance_full, '%');
                    $tolerance_full = abs(round($tmp_answer[1] * ($tolerance_perc / 100), 12));
                }
                $tolerance_partial = $settings['tolerance_partial'];
                if ($settings['parttoltyp'] == '%') {
                    $tolerance_perc = rtrim($tolerance_partial, '%');
                    $tolerance_partial = abs(round($tmp_answer[1] * ($tolerance_perc / 100), 12));
                }

                $ansdata['tolerance_full'] = $tolerance_full;
                $ansdata['tolerance_partial'] = $tolerance_partial;

                if ($tmp_answer[1] < 0) {
                    $ansdata['tolerance_fullans'] = $tmp_answer[1] - $tolerance_full;
                    $ansdata['tolerance_fullansneg'] = $tmp_answer[1] + $tolerance_full;
                    $ansdata['tolerance_partialans'] = $tmp_answer[1] - $tolerance_partial;
                    $ansdata['tolerance_partialansneg'] = $tmp_answer[1] + $tolerance_partial;
                } else {
                    $ansdata['tolerance_fullans'] = $tmp_answer[1] + $tolerance_full;
                    $ansdata['tolerance_fullansneg'] = $tmp_answer[1] - $tolerance_full;
                    $ansdata['tolerance_partialans'] = $tmp_answer[1] + $tolerance_partial;
                    $ansdata['tolerance_partialansneg'] = $tmp_answer[1] - $tolerance_partial;
                }

                $new_user_answer['ans'] = $ansdata;

                $statusdata['units'] = true;

                $saved_response = $tmp_answer[0];

                $saved_response_clean = preg_replace('([^0-9\.\-])', '', $saved_response);

                if ($tmp_answer[0] == '') {

                    $new_user_answer['uansnumb'] = '';
                    $new_user_answer['uans'] = '';
                    $new_user_answer['uansunit'] = '';
                } else {
                    echo '<td>';


                    if (isset($tmp_answer[1])) {
                        $difference = round(abs($saved_response_clean - $tmp_answer[1]), 12);

                        if ($saved_response_clean == $tmp_answer[1]) {
                            $statusdata['overall'] = 1;
                            $statusdata['exact'] = true;
                        } elseif ($difference > 0 and $difference <= $tolerance_full and $tolerance_full > 0) {
                            $statusdata['overall'] = 2;
                            $statusdata['exact'] = false;
                            $statusdata['tolerance_full'] = true;
                        } elseif ($difference > 0 and $difference <= $tolerance_partial and $tolerance_partial > 0) {
                            $statusdata['overall'] = 3;
                            $statusdata['exact'] = false;
                            $statusdata['tolerance_full'] = false;
                            $statusdata['tolerance_partial'] = true;
                        } else {
                            $statusdata['overall'] = 0;
                            $statusdata['exact'] = false;
                            $statusdata['tolerance_full'] = false;
                            $statusdata['tolerance_partial'] = false;
                        }

                    }
                }

                $new_user_answer['status'] = $statusdata;

                if (isset($tmp_answer[2])) {
                    if ($tmp_answer[2] == '') {
                        $variable_array = array('error', 'error', 'error', 'error', 'error', 'error', 'error', 'error', 'error');
                    } else {
                        $variable_array = explode(',', $tmp_answer[2]);
                    }
                } else {
                    $variable_array = array();
                }
                $varno = 0;
                foreach ($variable_array as $individual_variable) {
                    if ($individual_variable != '') {
                        $varsdata[$vars[$varno]] = $individual_variable;
                    }
                    $varno++;
                }
                if (isset($varsdata)) {
                    $new_user_answer['vars'] = $varsdata;
                }
                $new_user_answer['original'] = $user_answer;


              // Calculate distance from correct if needed
                $new_user_answer['cans_dist'] = $enhancedcalcObj->distance_from_correct_answer($new_user_answer['uansnumb'], $new_user_answer['cans']);
                $jsoned = json_encode($new_user_answer);

                $update->bind_param('si', $jsoned, $uid);
                $update->execute();

                $loop++;
                if ($loop % 200 == 0) {
                    $mysqli->commit();
                    echo '<br>';
                    @ob_flush();
                }
            }


        }
        $mysqli->commit();
        $update->close();
        $result->close();
    }

    //touch("./stopfile_convert_calc_ans_done.txt");
    $updater_utils->record_update('convert_calc_ans_done');
}

