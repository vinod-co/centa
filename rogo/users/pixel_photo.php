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
* This pixelates a student photo to protect the student's identity. Useful
* when Rogo is in demo mode.
*
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

header('Content-Type: image/jpeg');

$filename = '../users/photos/' . $_GET['username'] . '.jpg';

$im = imagecreatefromjpeg($filename);
imagefilter($im, IMG_FILTER_PIXELATE, 7, true);

imagejpeg($im);

imagedestroy($im);
