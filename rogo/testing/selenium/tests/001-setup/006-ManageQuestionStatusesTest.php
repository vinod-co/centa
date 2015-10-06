<?php
require_once 'shared.inc.php';

class ManageQuestionStatusesTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected $install_type;
  protected $page_root;

  protected function setUp() {
    $this->install_type = get_install_type();
    $this->page_root = get_root_url();

    $this->setBrowser("*firefox");
    $this->setBrowserUrl($this->page_root . '/');
  }

  public function testCreateStatus() {
    do_admin_login($this);

    $this->open("/admin/index.php");
    $this->click("css=#list_statuses > div.container > img.icon");
    $this->waitForPageToLoad("30000");
    $this->assertTitle('Question Statuses' . $this->install_type);

    $this->click("link=Create new Status");
    $this->waitForPageToLoad("30000");
    $this->assertTitle('Add Status' . $this->install_type);

    $this->type('id=name', 'Short Lived Status');
    $this->click('id=exclude_marking');
    $this->click('id=retired');
    $this->click('id=is_default');
    $this->click('id=display_warning');
    $this->click('name=submit');
    $this->waitForPageToLoad('30000');
    $this->assertTextPresent('Short Lived Status');
    $this->assertCssCount('css=.selectable', 6);
  }

  public function testCantCreateStatusWithoutName() {
    do_admin_login($this);

    $this->open("/admin/list_statuses.php");
    $this->waitForPageToLoad("30000");
    $this->click("link=Create new Status");
    $this->waitForPageToLoad("30000");
    $this->assertTitle('Add Status' . $this->install_type);

    $this->click('id=exclude_marking');
    $this->click('id=retired');
    $this->click('id=is_default');
    $this->click('name=submit');
    $this->assertLocation($this->page_root . '/admin/edit_status.php');

    $this->type("id=name", "");
    $this->click("name=submit");
    $this->assertLocation($this->page_root . '/admin/edit_status.php');
  }

  /**
   * @depends testCreateStatus
   */
  public function testEditStatus() {
    do_admin_login($this);

    $this->open("/admin/list_statuses.php");
    $this->click("css=#status_6");
    $this->click("link=Edit Status");
    $this->waitForPageToLoad("30000");
    $this->assertTitle('Edit Status' . $this->install_type);

    $this->assertElementValueContains("id=name", "Short Lived Status");
    $this->assertCssCount('css=input[@type="checkbox"]:checked', 6);

    $this->type("id=name", "Short Lived Status2");
    $this->click('id=exclude_marking');
    $this->click('id=retired');
    $this->click('id=is_default');
    $this->click('id=change_locked');
    $this->click('id=validate');
    $this->click('id=display_warning');
    $this->click("name=submit");
    $this->waitForPageToLoad("30000");

    $this->assertTextPresent('Short Lived Status2');

    $this->doubleClick("css=#status_6");
    $this->waitForPageToLoad("30000");

    $this->assertElementValueContains("id=name", "Short Lived Status2");
    $this->assertCssCount('css=input[@type="checkbox"]:checked', 0);

    $this->type("id=name", "Short Lived Status3");
    $this->click("name=submit");
    $this->waitForPageToLoad("30000");

    $this->assertTextPresent('Short Lived Status3');
  }

  public function testShouldShowDefault() {
    do_admin_login($this);

    $this->open("/admin/list_statuses.php");
    $this->waitForPageToLoad("30000");
    sleep(5);
    $this->assertElementPresent('css=.selectable.default');
  }

  public function testShouldChangeDefault() {
    do_admin_login($this);

    $this->open("/admin/list_statuses.php");
    $this->click("css=#status_2");
    $this->click("link=Edit Status");
    $this->waitForPageToLoad("30000");

    $this->click('id=is_default');
    $this->click("name=submit");
    $this->waitForPageToLoad("30000");
    $this->assertElementPresent('css=#status_2.default');

    $this->doubleClick("css=#status_1");
    $this->waitForPageToLoad("30000");
    $this->click('id=is_default');
    $this->click("name=submit");
    $this->waitForPageToLoad("30000");
    $this->assertElementPresent('css=#status_1.default');
  }

  public function testShouldBeOnlyOneDefault() {
    do_admin_login($this);

    $this->open("/admin/list_statuses.php");
    $this->click("css=#status_2");
    $this->click("link=Edit Status");
    $this->waitForPageToLoad("30000");

    $this->click('id=is_default');
    $this->click("name=submit");
    $this->waitForPageToLoad("30000");
    $this->assertCssCount('css=.selectable.default', 1);

    $this->doubleClick("css=#status_1");
    $this->waitForPageToLoad("30000");
    $this->click('id=is_default');
    $this->click("name=submit");
    $this->waitForPageToLoad("30000");
    $this->assertCssCount('css=.selectable.default', 1);
  }

  public function testShouldPerformReorder() {
    do_admin_login($this);

    $this->open("/admin/list_statuses.php");

    $this->mouseDownAt('css=#status_2', '5,8');
    $this->mouseMoveAt('css=#status_1', '5,8');
    $this->mouseUpAt('css=#status_1', '');

    usleep(200000);

    $this->open("/admin/list_statuses.php");
    $this->waitForPageToLoad("30000");
    $this->assertText('css=#statuses li:first-child', 'Retired');

    $this->mouseDownAt('css=#status_1', '5,8');
    $this->mouseMoveAt('css=#status_2', '5,8');
    $this->mouseUpAt('css=#status_2', '');

    usleep(200000);

    $this->open("/admin/list_statuses.php");
    $this->waitForPageToLoad("30000");
    $this->assertText('css=#statuses li:first-child', 'Normal');
  }

  /**
   * @depends testCreateStatus
   */
  public function testDeleteStatus() {
    do_admin_login($this);

    $this->open("/admin/list_statuses.php");
    $this->waitForPageToLoad("30000");
    $this->click("css=#status_6");
    $this->click("link=Delete Status");
    $this->waitForPopUp("deleteitem", "30000");
    $this->selectWindow("name=deleteitem");
    $this->click("name=submit");
    $this->selectWindow('null');

    $this->open("/admin/list_statuses.php");
    $this->waitForPageToLoad("30000");
    $this->assertTextNotPresent('Short Lived Status3');
  }

  public function testDeleteReassignsDefault() {
    do_admin_login($this);

    $this->open("/admin/list_statuses.php");
    $this->waitForPageToLoad("30000");
    $this->click("link=Create new Status");
    $this->waitForPageToLoad("30000");

    $this->type('id=name', 'Short Lived Status');
    $this->click('id=is_default');
    $this->click('name=submit');
    $this->waitForPageToLoad('30000');
    $this->assertTextPresent('Short Lived Status');
    $this->assertCssCount('css=.selectable', 6);

    $this->click("css=#status_7");
    $this->click("link=Delete Status");
    $this->waitForPopUp("deleteitem", "30000");
    $this->selectWindow("name=deleteitem");
    $this->click("name=submit");
    $this->selectWindow('null');

    $this->open("/admin/list_statuses.php");
    $this->waitForPageToLoad("30000");
    $this->assertElementPresent('css=#statuses li:first-child.default');
  }

  public function testCannotDeleteAssignedStatus() {
    do_admin_login($this);

    $this->open("/admin/list_statuses.php");
    $this->waitForPageToLoad("30000");
    $this->click("css=#status_1");
    $this->click("link=Delete Status");
    $this->waitForPopUp("deleteitem", "30000");
    $this->selectWindow("name=deleteitem");
    $this->assertTextPresent('You cannot delete a question status to which questions are assigned');
  }

  public function testCantCreateDuplicateStatus() {
    do_admin_login($this);

    $this->open("/admin/list_statuses.php");
    $this->waitForPageToLoad("30000");
    $this->click("link=Create new Status");
    $this->waitForPageToLoad("30000");
    $this->assertTitle('Add Status' . $this->install_type);

    $this->type('id=name', 'Normal');
    $this->click('name=submit');
    $this->waitForPageToLoad('30000');
    $this->assertTextPresent('Status names must be unique');
    $this->assertLocation($this->page_root . '/admin/edit_status.php');
  }
}
?>