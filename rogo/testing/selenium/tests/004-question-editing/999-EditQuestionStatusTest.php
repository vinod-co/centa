<?php
require_once 'shared.inc.php';

class EditQuestionStatusTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected $install_type;
  protected $page_root;

  protected function setUp() {
    $this->install_type = get_install_type();
    $this->page_root = get_root_url();

    $this->setBrowser("*firefox");
    $this->setBrowserUrl($this->page_root . '/');
  }

  public function testShouldDisplayStatuses() {
    do_staff_login($this);

    $this->open("/paper/details.php?paperID=1&folder=&module=3");
    $this->click("css=td.l");
    $this->click("link=Edit Question");
    $this->waitForPageToLoad("30000");

    $this->assertElementPresent('id=status_1');
    $val = $this->getAttribute('id=status_1@value');
    $this->assertEquals($val, '1');
    $this->assertText('css=#status_list li:first-child label', 'Normal');

    $this->assertElementPresent('id=status_2');
    $val = $this->getAttribute('id=status_2@value');
    $this->assertEquals($val, '2');
    $this->assertText('css=#status_list li:nth-child(2) label', 'Retired');

    $this->assertElementPresent('id=status_3');
    $val = $this->getAttribute('id=status_3@value');
    $this->assertEquals($val, '3');
    $this->assertText('css=#status_list li:nth-child(3) label', 'Incomplete');

    $this->assertElementPresent('id=status_4');
    $val = $this->getAttribute('id=status_4@value');
    $this->assertEquals($val, '4');
    $this->assertText('css=#status_list li:nth-child(4) label', 'Experimental');

    $this->assertElementPresent('id=status_5');
    $val = $this->getAttribute('id=status_5@value');
    $this->assertEquals($val, '5');
    $this->assertText('css=#status_list li:nth-child(5) label', 'Beta');

    $this->click("id=submit-cancel");
    $this->waitForPageToLoad("30000");
  }

  public function testCorrectStatusSouldBeSelected() {
    do_staff_login($this);

    $this->open("/paper/details.php?paperID=1&folder=&module=3");
    $this->click("css=td.l");
    $this->click("link=Edit Question");
    $this->waitForPageToLoad("30000");

    $this->assertElementValueContains('id=status_1', 'on');
    $this->assertElementValueContains('id=status_2', 'off');
    $this->assertElementValueContains('id=status_3', 'off');
    $this->assertElementValueContains('id=status_4', 'off');
    $this->assertElementValueContains('id=status_5', 'off');

    $this->click("id=submit-cancel");
    $this->waitForPageToLoad("30000");
  }

  public function testCanChangeStatus() {
    do_staff_login($this);

    $this->open("/paper/details.php?paperID=1&folder=&module=3");
    $this->click("css=td.l");
    $this->click("link=Edit Question");
    $this->waitForPageToLoad("30000");

    $this->assertElementValueContains('id=status_1', 'on');
    $this->assertElementValueContains('id=status_2', 'off');
    $this->assertElementValueContains('id=status_3', 'off');
    $this->assertElementValueContains('id=status_4', 'off');
    $this->assertElementValueContains('id=status_5', 'off');

    $this->click("id=status_2");
    $this->click("id=submit-save");
    $this->waitForPageToLoad("30000");
    $this->click("css=td.l");
    $this->click("link=Edit Question");
    $this->waitForPageToLoad("30000");

    $this->assertElementValueContains('id=status_1', 'off');
    $this->assertElementValueContains('id=status_2', 'on');
    $this->assertElementValueContains('id=status_3', 'off');
    $this->assertElementValueContains('id=status_4', 'off');
    $this->assertElementValueContains('id=status_5', 'off');

    $this->click("id=status_4");
    $this->click("id=submit-save");
    $this->waitForPageToLoad("30000");
    $this->click("css=td.l");
    $this->click("link=Edit Question");
    $this->waitForPageToLoad("30000");

    $this->assertElementValueContains('id=status_1', 'off');
    $this->assertElementValueContains('id=status_2', 'off');
    $this->assertElementValueContains('id=status_3', 'off');
    $this->assertElementValueContains('id=status_4', 'on');
    $this->assertElementValueContains('id=status_5', 'off');

    $this->click("id=status_1");
    $this->click("id=submit-save");
    $this->waitForPageToLoad("30000");
    $this->click("css=td.l");
    $this->click("link=Edit Question");
    $this->waitForPageToLoad("30000");

    $this->assertElementValueContains('id=status_1', 'on');
    $this->assertElementValueContains('id=status_2', 'off');
    $this->assertElementValueContains('id=status_3', 'off');
    $this->assertElementValueContains('id=status_4', 'off');
    $this->assertElementValueContains('id=status_5', 'off');

    $this->click("id=submit-cancel");
    $this->waitForPageToLoad("30000");
  }

  public function testNewQuestionShouldSHowDefaultStatus() {
    do_staff_login($this);

    $this->open("/paper/details.php?paperID=1&module=&folder=&scrOfY=0");
    $this->click("link=Create new Question");
    $this->click("id=4_13");
    $this->waitForPageToLoad("30000");

    $this->assertElementValueContains('id=status_1', 'on');
    $this->assertElementValueContains('id=status_2', 'off');
    $this->assertElementValueContains('id=status_3', 'off');
    $this->assertElementValueContains('id=status_4', 'off');
    $this->assertElementValueContains('id=status_5', 'off');

    $this->click("id=submit-cancel");
    $this->waitForPageToLoad("30000");
  }
}