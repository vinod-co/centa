<?php
require_once 'shared.inc.php';

class URLTamperingNotAllowedTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected $install_type;
  protected $page_root;

  protected function setUp() {
    $this->install_type = get_install_type();
    $this->page_root = get_root_url();

    $this->setBrowser("*firefox");
    $this->setBrowserUrl($this->page_root . '/');
  }

  public function testAddToTeamNotAllowed() {
    do_staff_login($this);

    $this->open("folder/edit_team_popup.php?module=4&calling=paper_list&folder=");
    $this->assertTextPresent('Page not Found');
  }

  public function testCheckDeleteFolderNotAllowed() {
    do_staff_login($this);

    $this->open("delete/check_delete_folder.php?folderID=1");
    $this->assertTextPresent('Page not Found');
  }

  public function testCheckDeleteReferenceNotAllowed() {
    do_staff_login($this);

    $this->open("delete/check_delete_ref_material.php?refID=1&module=4");
    $this->assertTextPresent('Page not Found');
  }

  public function testEditReferenceNotAllowed() {
    do_staff_login($this);

    $this->open("folder/edit_ref_material.php?refID=1&module=4");
    $this->assertTextPresent('Page not Found');
  }

  public function testFolderPropertiesNotAllowed() {
    do_staff_login($this);

    $this->open("folder/properties.php?folder=1");
    $this->assertTextPresent('Page not Found');
  }

  public function testFormativePaperViewNotAllowed() {
    do_student_login($this, 'teststudent10', 'jgl!34Z^');

    $this->open("paper/finish.php?id=11355244387102&userID=104&metadataID=11&log_type=0&percent=0.00");
    $this->assertTextPresent('Page not Found');
  }

  public function testPaperDetailsNotAllowed() {
    do_staff_login($this);

    $this->open("paper/details.php?paperID=10&module=4");
    $this->assertTextPresent('Page not Found');
  }

  public function testObjectivesFeedbackNotAllowed() {
    do_student_login($this, 'teststudent2', 'nrt%52YQ');

    $this->open("students/objectives_feedback.php?id=71377089276102&userID=104");
    $this->assertTextPresent('Page not Found');
  }

  public function testQuestionFeedbackNotAllowed() {
    do_student_login($this, 'teststudent2', 'nrt%52YQ');

    $this->open("paper/feedback.php?id=71377089276102&userid=104");
    $this->assertTextPresent('Access Denied');
  }
}
?>