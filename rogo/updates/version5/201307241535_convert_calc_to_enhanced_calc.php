<?php

// Your code here
function add_dollar_to_linked_var($var) {
    return str_replace('var', 'var$', $var);
}
if ($updater_utils->count_rows("SELECT q_id from questions where q_type='calculation'") > 0) {
    $loop = 0;
    echo "<li>Converting Calculation questions to enhanced calculation questions</li>";
    $result = $mysqli->prepare("SELECT q_id,settings from  questions WHERE questions.q_type = 'calculation';");
    $result->execute();
    $result->store_result();
    $result->bind_result($qid, $settings);
    while ($result->fetch()) {
        $qids[$qid] = $settings;
    }
    if (!isset($qids) or count($qids) == 0) {
        exit();
    }
    $vars = array('$A', '$B', '$C', '$D', '$E', '$F', '$G', '$H', '$I', '$J', '$K', '$L');
    
    $select = $mysqli->prepare("SELECT option_text,correct,id_num,marks_correct,marks_incorrect,marks_partial from  options WHERE o_id=? order by id_num;");

    $sql = "DELETE from options where id_num=?";
    $delete = $mysqli->prepare($sql);
    
    $sql = "UPDATE questions set settings=?,q_type='enhancedcalc' where q_id=?";
    $update = $mysqli->prepare($sql);
        
    foreach ($qids as $qid => $settings) {

        $select->bind_param('i', $qid);
        $select->execute();
        $select->store_result();
        $select->bind_result($optiontext, $correct, $id_num, $marks_correct, $marks_incorrect, $marks_partial);
        $settings = json_decode($settings, true);
        $changed = false;
        $loc = 0;
        unset($optionids);
        $optionids = array();

        while ($select->fetch()) {
            $optionids[] = $id_num;
            $changed = true;
            $opts = explode(',', $optiontext);
            $settings['vars'][$vars[$loc]]['min'] = add_dollar_to_linked_var($opts[0]);
            $settings['vars'][$vars[$loc]]['max'] = add_dollar_to_linked_var($opts[1]);
            $settings['vars'][$vars[$loc]]['inc'] = $opts[2];
            $settings['vars'][$vars[$loc]]['dec'] = $opts[3];
            $ansdat['formula'] = str_ireplace('pi()', 'pi', $correct);
            $settings['marks_correct'] = $marks_correct;
            $settings['marks_incorrect'] = $marks_incorrect;
            $settings['marks_partial'] = $marks_partial;

            $ansdat['units'] = $settings['units'];

            $delete->bind_param('i', $id_num);
            $delete->execute();
            $loc++;
        }
        
        if (!isset($settings['dp'])) {
            if (isset($settings['answer_decimals'])) {
                $settings['dp'] = $settings['answer_decimals'];
                unset($settings['answer_decimals']);
            }
        }

        if (!isset($settings['strictdisplay'])) {
            if (isset($settings['dp'])) {
              $settings['strictdisplay'] = true;
            } else {
              $settings['strictdisplay'] = false;
            }
        }
        if (!isset($settings['strictzeros'])) {
            $settings['strictzeros'] = false;
        }
        
        if (!isset($settings['fulltoltyp'])) {
            $rep = '#';
            if (strpos($settings['tolerance_full'], '%') !== false) {
                $settings['tolerance_full'] = substr($settings['tolerance_full'], 0, strpos($settings['tolerance_full'], '%'));
                $rep = '%';
            }
            $settings['fulltoltyp'] = $rep;

        }
        if (!isset($settings['parttoltyp'])) {
            $rep = '#';
            if (strpos($settings['tolerance_partial'], '%') !== false) {
                $settings['tolerance_partial'] = substr($settings['tolerance_partial'], 0, strpos($settings['tolerance_partial'], '%'));
                $rep = '%';
            }
            $settings['parttoltyp'] = $rep;
        }

        if (!isset($settings['marks_unit'])) {
            $settings['marks_unit'] = 0;
        }

        if (!isset($settings['show_units'])) {
            $settings['show_units'] = true;
        }

        $settings['answers'][] = $ansdat;
        unset($settings['units']);

        
        $settings = json_encode($settings);
        $update->bind_param('si', $settings, $qid);
        $update->execute();
        echo '.';
        $loop++;
        if ($loop % 200 == 0) {
            echo '<br>';
            @ob_flush();
        }
    }
    
    $select->close();
    $delete->close();
    $update->close();
}

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */
