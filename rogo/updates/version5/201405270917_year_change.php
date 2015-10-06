<?php
if (!$updater_utils->does_column_exist('modules', 'academic_year_start')) {
  $sql = "ALTER TABLE `modules` ADD COLUMN `academic_year_start` CHAR(5) NOT NULL";
  $updater_utils->execute_query($sql, true);

  $sql = "UPDATE modules SET academic_year_start = '" . $configObject->get('cfg_academic_year_start') . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->does_column_exist('sms_imports', 'academic_year')) {
  $sql = "ALTER TABLE `sms_imports` ADD COLUMN `academic_year` enum('2002/03','2003/04','2004/05','2005/06','2006/07','2007/08','2008/09','2009/10','2010/11','2011/12','2012/13','2013/14','2014/15','2015/16','2016/17','2017/18','2018/19','2019/20')";
  $updater_utils->execute_query($sql, true);
  
  $sql = "UPDATE sms_imports SET academic_year = '2013/14' WHERE updated >= 20130701 AND updated < 20140701";
  $updater_utils->execute_query($sql, true);
  
  $sql = "UPDATE sms_imports SET academic_year = '2012/13' WHERE updated >= 20120701 AND updated < 20130701";
  $updater_utils->execute_query($sql, true);
  
  $sql = "UPDATE sms_imports SET academic_year = '2011/12' WHERE updated >= 20110701 AND updated < 20120701";
  $updater_utils->execute_query($sql, true);
  
  $sql = "UPDATE sms_imports SET academic_year = '2010/11' WHERE updated >= 20100701 AND updated < 20110701";
  $updater_utils->execute_query($sql, true);
  
}

?>