<?php
require_once 'shared.inc.php';

class URLTamperingNonExistentTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected $install_type;
  protected $page_root;

  protected function setUp() {
    $this->install_type = get_install_type();
    $this->page_root = get_root_url();

    $this->setBrowser("*firefox");
    $this->setBrowserUrl($this->page_root . '/');
  }

  public function testPersonalCohortNonExistentPaper() {
    do_staff_login($this);

    $this->open("reports/personal_cohort_performance.php?paperID=88888888&userID=104");
    $this->assertTextPresent('Page not Found');
  }

  public function testPersonalCohortNonExistentUser() {
    do_staff_login($this);

    $this->open("reports/personal_cohort_performance.php?paperID=7&userID=88888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testAddToTeamNonExistent() {
    do_staff_login($this);

    $this->open("/folder/edit_team_popup.php?module=888207&calling=paper_list&folder=");
    $this->assertTextPresent('Page not Found');
  }

  public function testCheckLogLateNonExistentDate() {
    do_staff_login($this);

    $this->open("/reports/check_reassign_log_late.php?userID=105&paperID=7&metadataID=88888888&log_type=2");
    $this->assertTextPresent('Page not Found');
  }

  public function testCheckLogLateNonExistentPaper() {
    do_staff_login($this);

    $this->open("/reports/check_reassign_log_late.php?userID=105&paperID=88888888&metadataID=34&log_type=2");
    $this->assertTextPresent('Page not Found');
  }

  public function testLogLateNonExistentUser() {
    do_staff_login($this);

    $this->open("/reports/check_reassign_log_late.php?userID=88888888&paperID=7&metadataID=34&log_type=2");
    $this->assertTextPresent('Page not Found');
  }

  public function testRetirePaperNonExistent() {
    do_staff_login($this);

    $this->open("paper/check_retire_paper.php?paperID=8888888888&module=3&folder=");
    $this->assertTextPresent('Page not Found');
  }

  public function testDeleteLTIKeyNonExistent() {
    do_admin_login($this);

    $this->open("delete/check_delete_LTIkeys.php?LTIkeysID=888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testDeleteSchoolNonExistent() {
    do_admin_login($this);

    $this->open("delete/check_delete_school.php?schoolID=888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testDeleteAnnouncementNonExistent() {
    do_admin_login($this);

    $this->open("delete/check_delete_announcement.php?announcementID=8888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testDeleteEbelGridNonExistent() {
    do_admin_login($this);

    $this->open("delete/check_delete_ebel_template.php?gridID=888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testDeleteFacultyNonExistent() {
    do_admin_login($this);

    $this->open("delete/check_delete_faculty.php?facultyID=8888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testDeleteFolderNonExistent() {
    do_staff_login($this);

    $this->open("delete/check_delete_folder.php?folderID=888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testDeleteKeywordNonExistent() {
    do_staff_login($this);

    $this->open("delete/check_delete_team_keyword.php?keywordID=,88888888&module=");
    $this->assertTextPresent('Page not Found');
  }

  public function testDeleteLabNonExistent() {
    do_admin_login($this);

    $this->open("delete/check_delete_lab.php?labID=888888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testDeleteModuleNonExistent() {
    do_admin_login($this);

    $this->open("delete/check_delete_module.php?idMod=8888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testDeleteReferenceNonExistent() {
    do_staff_login($this);

    $this->open("delete/check_delete_ref_material.php?refID=888888888888&module=3");
    $this->assertTextPresent('Page not Found');
  }

  public function testDeleteUserNonExistent() {
    do_admin_login($this);

    $this->open("delete/check_delete_user.php?id=,3,4,8888888,5");
    $this->assertTextPresent('Page not Found');
  }

  public function testClassTotalsNonExistent() {
    do_staff_login($this);

    $this->open("reports/class_totals.php?paperID=885380&startdate=20120717100000&enddate=20120717140000&repmodule=&repcourse=%&sortby=name&module=240&folder=&percent=100&absent=0&studentsonly=1&ordering=asc");
    $this->assertTextPresent('Page not Found');
  }

  public function testCopyOntoPaperNonExistent() {
    do_staff_login($this);

    $this->open("question/copy_onto_paper.php?q_id=,888870970");
    $this->assertTextPresent('Page not Found');
  }

  public function testDeletePaperNonExistent() {
    do_staff_login($this);

    $this->open("delete/check_delete_paper.php?paperID=88883011&module=3&folder=");
    $this->assertTextPresent('Page not Found');
  }

  public function testEditEbelGridNonExistent() {
    do_admin_login($this);

    $this->open("admin/edit_ebel_grid.php?id=88888");
    $this->assertTextPresent('Page not Found');
  }

  public function testEditAnnouncementNonExistent() {
    do_admin_login($this);

    $this->open("admin/edit_announcement.php?announcementid=888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testEditCourseNonExistent() {
    do_admin_login($this);

    $this->open("admin/edit_course.php?courseID=88888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testEditFacultyNonExistent() {
    do_admin_login($this);

    $this->open("admin/edit_faculty.php?facultyID=888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testEditLabNonExistent() {
    do_admin_login($this);

    $this->open("admin/edit_lab.php?labID=888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testEditModuleNonExistent() {
    do_admin_login($this);

    $this->open("admin/edit_module.php?moduleid=8888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testEditReferenceNonExistent() {
    do_staff_login($this);

    $this->open("folder/edit_ref_material.php?refID=88888888&module=3");
    $this->assertTextPresent('Page not Found');
  }

  public function testEditSchoolNonExistent() {
    do_admin_login($this);

    $this->open("admin/edit_school.php?schoolid=8888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testFolderDetailsNonExistent() {
    do_staff_login($this);

    $this->open("folder/details.php?folder=888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testFolderPropsNonExistent() {
    do_staff_login($this);

    $this->open("folder/properties.php?folder=888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testHelpStaffEditNonExistent() {
    do_admin_login($this);

    $this->open("help/staff/edit_page.php?id=888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testHelpStudentEditNonExistent() {
    do_admin_login($this);

    $this->open("help/student/edit_page.php?id=888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testLabDetailsNonExistent() {
    do_admin_login($this);

    $this->open("admin/lab_details.php?labID=888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testManageObjectivesNonExistent() {
    do_staff_login($this);

    $this->open("mapping/sessions_list.php?module=888240");
    $this->assertTextPresent('Page not Found');
  }

  public function testCreateSessionNonExistent() {
    do_staff_login($this);

    $this->open("mapping/add_session.php?module=888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testMidExamClarificationNonExistentPaper() {
    do_admin_login($this);

    $this->open("question/exam_clarification.php?paperID=88888888888&q_id=3&questionNo=3&screenNo=2");
    $this->assertTextPresent('Page not Found');
  }

  public function testMidExamClarificationNonExistentQuestion() {
    do_admin_login($this);

    $this->open("question/exam_clarification.php?paperID=1&q_id=8888888888888&questionNo=3&screenNo=2");
    $this->assertTextPresent('Page not Found');
  }

  public function testModuleContentNonExistent() {
    do_staff_login($this);

    $this->open("folder/details.php?module=888207");
    $this->assertTextPresent('Page not Found');
  }

   public function testOsceNonExistentPaper() {
     do_staff_login($this);

     $this->open("osce/form.php?id=8888888888&userID=104");
     $this->assertTextPresent('Page not Found');
   }

   public function testOsceNonExistentUser() {
     do_staff_login($this);

     $this->open("osce/form.php?id=81377097998102&userID=88888888");
     $this->assertTextPresent('Page not Found');
   }

   public function testOscePrintNonExistent() {
     do_staff_login($this);

     $this->open("osce/print.php?paperID=88888888");
     $this->assertTextPresent('Page not Found');
   }

   public function testMappingByQuestionNonExistent() {
     do_staff_login($this);

     $this->open("mapping/paper_by_question.php?paperID=88884096&folder=&module=3");
     $this->assertTextPresent('Page not Found');
   }

   public function testMappingBySessionNonExistent() {
     do_staff_login($this);

     $this->open("mapping/paper_by_session.php?paperID=88884096&module=3&folder=");
     $this->assertTextPresent('Page not Found');
   }

  public function testPaperPropertiesNonExistent() {
    do_staff_login($this);

    $this->open("paper/properties.php?paperID=88883011&caller=details&module=3&folder=");
    $this->assertTextPresent('Page not Found');
  }

  public function testPaperBlueScreenNonExistent() {
    do_student_login($this, 'teststudent10', 'jgl!34Z^');

    $this->open("/paper/user_index.php?id=2607128816754214438");
    $this->assertTextPresent('Page not Found');
  }

  public function testPaperDetailsNonExistent() {
    do_staff_login($this);

    $this->open("paper/details.php?paperID=6085859&module=&folder=");
    $this->assertTextPresent('Page not Found');
  }

  public function testPaperFinishNonExistent() {
    do_student_login($this, 'teststudent10', 'jgl!34Z^');

    $this->open("paper/finish.php?id=6184135298420215248&previous=20121125233045&log_type=1");
    $this->assertTextPresent('Page not Found');
  }

  public function testForcePWResetNonExistent() {
    do_admin_login($this);

    $this->open("users/reset_pwd.php?userID=8888888817184");
    $this->assertTextPresent('Page not Found');
  }

  public function testPeerReviewNonExistent() {
    do_staff_login($this);

    $this->open("peer_review/form.php?id=88888888888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testQuestionBankNonExistentModule() {
    do_staff_login($this);

    $this->open("question/list.php?type=%&module=888840");
    $this->assertTextPresent('Page not Found');
  }

  public function testReassignToUserNonExistentPaper() {
    do_staff_login($this);

    $this->open("reports/check_reassign_script.php?userID=3&paperID=88888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testReassignToUserNonExistentUser() {
    do_staff_login($this);

    $this->open("reports/check_reassign_script.php?userID=88888888&paperID=7");
    $this->assertTextPresent('Page not Found');
  }

  public function testReferenceNonExistentModule() {
    do_staff_login($this);

    $this->open("folder/list_ref_material.php?module=888240");
    $this->assertTextPresent('Page not Found');
  }

  public function testSSEbelNonExistentPaper() {
    do_staff_login($this);

    $this->open("std_setting/individual_review.php?paperID=8888888888&method=ebel&module=3&folder=");
    $this->assertTextPresent('Page not Found');
  }

  public function testSSGroupNonExistentPaper() {
    do_staff_login($this);

    $this->open("std_setting/get_group.php?paperID=88888888888&module=69&folder=&method=modified_angoff");
    $this->assertTextPresent('Page not Found');
  }

  public function testSSHofsteeNonExistentPaper() {
    do_staff_login($this);

    $this->open("std_setting/hofstee.php?paperID=88888888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testSSModAngoffNonExistentPaper() {
    do_staff_login($this);

    $this->open("std_setting/individual_review.php?paperID=8888888888&method=modified_angoff&module=3&folder=");
    $this->assertTextPresent('Page not Found');
  }

  public function testSSModDeleteNonExistent() {
    do_staff_login($this);

    $this->open("delete/check_delete_review.php?std_setID=8888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testSSNonExistentPaper() {
    do_staff_login($this);

    $this->open("std_setting/index.php?paperID=88888888888&module=3&folder=");
    $this->assertTextPresent('Page not Found');
  }

  public function testUserDetailsNonExistent() {
    do_staff_login($this);

    $this->open("users/details.php?search_surname=&search_username=&student_id=&moduleID=&calendar_year=2012/13&students=on&submit=Search&userID=88888888&email=&tmp_surname=&tmp_courseID=&tmp_yearID=");
    $this->assertTextPresent('Page not Found');
  }

  public function testSystemErrorDetailsNonExistent() {
    do_admin_login($this);

    $this->open("admin/sys_error_details.php?errorID=8888888888");
    $this->assertTextPresent('Page not Found');
  }

  public function testTeamDialogueNonExistentModule() {
    do_staff_login($this);

    $this->open("folder/edit_team_popup.php?module=888240&calling=paper_list&folder=");
    $this->assertTextPresent('Page not Found');
  }

  public function testTextboxMarkFinaliseNonExistentPaper() {
    do_staff_login($this);

    $this->open("reports/textbox_finalise_marks.php?ws=1&q_id=131&qNo=1&paperID=88888888&startdate=20130822000000&enddate=20130822160000&folder=&module=3&repcourse=%");
    $this->assertTextPresent('Page not Found');
  }

  public function testTextboxMarkFinalise2NonExistentPaper() {
    do_staff_login($this);

    $this->open("reports/textbox_finalise_marks.php?ws=1&q_id=88888888&qNo=1&paperID=9&startdate=20130822000000&enddate=20130822160000&folder=&module=3&repcourse=%");
    $this->assertTextPresent('Page not Found');
  }

  public function testTextboxMarkPaneNonExistentQuestion() {
    do_staff_login($this);

    $this->open("reports/textbox_marking.php?ws=1&q_id=88888888&qNo=1&paperID=9&startdate=20130822000000&enddate=20130822160000&folder=&module=3&repcourse=%&phase=1");
    $this->assertTextPresent('Page not Found');
  }

  public function testTextboxMarkPaneNonExistentPaper() {
    do_staff_login($this);

    $this->open("reports/textbox_marking.php?ws=1&q_id=131&qNo=1&paperID=88888888&startdate=20130822000000&enddate=20130822160000&folder=&module=3&repcourse=%&phase=1");
    $this->assertTextPresent('Page not Found');
  }

  public function testTextboxMarkQnSelectionNonExistentPaper() {
    do_staff_login($this);

    $this->open("reports/textbox_select_q.php?action=finalise&paperID=88888888&startdate=20130822000000&enddate=20130822160000&repmodule=&repcourse=%&sortby=name&module=3&folder=&percent=100&absent=0&studentsonly=1&ordering=asc");
    $this->assertTextPresent('Page not Found');
  }

  public function testUploadMarksNonExistentPaper() {
    do_admin_login($this);

    $this->open("import/offline_marks.php?paperID=88888888888&module=3&folder=");
    $this->assertTextPresent('Page not Found');
  }

  public function testUserModulesExistentUser() {
    do_staff_login($this);

    $this->open("users/edit_modules_popup.php?userID=888812423&session=2012/13&grade=S100");
    $this->assertTextPresent('Page not Found');
  }
}
?>