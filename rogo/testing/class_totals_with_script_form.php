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
 * @author Simon Wilkinson
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */

require '../include/sysadmin_auth.inc';
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>"/>

  <title>Testing: Class Totals</title>

  <link rel="stylesheet" type="text/css" href="../css/body.css"/>
  <link rel="stylesheet" type="text/css" href="../css/header.css"/>
  <style type="text/css">
    body {
      font-size: 90%;
    }

    dt {
      font-weight: bold;
    }
    dd {
      padding-bottom: 16px;
    }
    .error {
      color: red;
      font-weight: bold;
    }
  </style>
</head>
<body>
<h1>Class Totals between Servers</h1>


<div id="form">
  <form id="the_form" action="class_totals_with_script_ajax.php" method="post">
  <dl class="form">
    <dt>Username:</dt>
    <dd><input type="text" id="username" name="username" class="required" /></dd>
    <dt>Password:</dt>
    <dd><input type="password" id="passwd" name="passwd" class="required" /></dd>
  </dl>
    <input type="submit" name="submit" value="Start Analysis" />
    <input type="hidden" name="period" value="month" />
  </form>
</div>

</body>
</html>
