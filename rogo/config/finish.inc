<?php
  if (isset($low_bandwidth) and $low_bandwidth == 1) {
    // Lowbandwidth
    
    //enable output compression
    ob_start('ob_gzhandler');
    
    $top_table_html = '<table cellpadding="4" cellspacing="0" border="0" style="width:100%; background-color:#5590CF">';
    $logo_html = '<td></td></tr>';
    $bottom_html = '<br /><table cellpadding="2" cellspacing="0" border="0" style="width:100%; background-color:#5590CF">';
  } else {
    // Full-fat
    $top_table_html = '<table cellpadding="4" cellspacing="0" border="0" style="width:100%; background-color:#5590CF">';
    $logo_html = '<td width="160" style="vertical_align:bottom"><img src="../config/logo.png" width="160" height="67" alt="Logo" /></td></tr>';
    $bottom_html = '<br /><table cellpadding="2" cellspacing="0" border="0" style="width:100%; background-color:#5590CF">';
  }
  $leaving_rules = $string['msg2'];
?>