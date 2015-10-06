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
* @author Rob Ingram
* @version 1.0
* @copyright Copyright (c) 2013 The University of Nottingham
* @package
*/

$num_options = count($question->options);
$disabled = ($dis_class != '') ? ' disabled="disabled"' : '';
?>
				<table id="q-details" class="form" summary="<?php echo $string['qeditsummary'] ?>">
					<tbody>
<?php require_once 'details_common.php' ?>
            <tr>
              <th><label for="option_order"><?php echo $string['optionorder'] ?></label></th>
              <td>
                <select id="option_order" name="option_order"<?php echo $disabled ?>>
<?php
echo ViewHelper::render_options($question->get_option_orders(), $question->get_option_order(), 3);
?>
                </select>
              </td>
            </tr>
					</tbody>
				</table>

<?php
require_once 'detail_parts/details_marking.php';
$label_correct = $string['fbcorrect'] . '<br /><span class="note warning-severe">' . $string['fbcorrectmsg'] . '</span>';
$show_incorrect = true;
require_once 'detail_parts/details_general_feedback.php';
?>

        <div class="form">
          <h2 class="midblue_header"><?php echo $string['options'] ?></h2><div class="note"><?php echo $string['optionsmsg'] ?></div>
        </div>

        <table id="q-options" class="form" summary="<?php echo $string['oeditsummary'] ?>">
          <thead>
            <tr>
              <th colspan="2">&nbsp;</th>
              <th class="small align-centre"><?php echo $string['answer'] ?></th>
            </tr>
          </thead>
<?php
$index = 1;
foreach ($question->options as $o_id => $option) {
  include 'options/opt_rank.php';
  $index++;
}

for ($index = $num_options + 1; $index <= $question->max_options; $index++) {
  $option = OptionEdit::option_factory($mysqli, $userObject->get_user_ID(), $question, $index, $string);
  include 'options/opt_rank.php';
}

if ($question->get_locked() == '') {
?>
          <tbody class="add-option-holder">
            <tr>
              <th>&nbsp;</th>
              <td colspan="2">
                <input class="next-option" value="<?php echo $string['addoptions'] ?>" type="button" />
              </td>
            </tr>
          </tbody>
<?php
}
?>
        </table>

