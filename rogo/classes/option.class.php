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
 * An option for a question
 *
 * @author brzab3
 */
class Option {

  public $id = -1;
  protected $question_id = null;
  protected $text = '';
  protected $media = '';
  protected $media_width = '';
  protected $media_height = '';
  protected $correct_fback = '';
  protected $incorrect_fback = '';
  protected $correct = '';
  protected $marks_correct = 1;
  protected $marks_incorrect = 0;
  protected $marks_partial = 0;

}

?>
