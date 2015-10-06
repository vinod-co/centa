<?php
require_once 'shared.inc.php';

class URLTamperingMissingParamsTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected $install_type;
  protected $page_root;

  protected function setUp() {
    $this->install_type = get_install_type();
    $this->page_root = get_root_url();

    $this->setBrowser("*firefox");
    $this->setBrowserUrl($this->page_root . '/');
  }

  public function testAddQuestionsByPaperMissingParams() {
    do_admin_login($this);

    // Check date in denied log to within an hour - very slim chance of this generating a false failure
    // Also, ensure that your php.ini for CLI contains the same timezone setting as the web php.ini
    $now = date('d/m/Y H');
    $this->open("question/add/add_questions_by_paper.php?question_paper=");
    $this->assertTextPresent('A mandatory GET variable is missing');

    // Check the denied log
    $this->open("admin/view_access_denied.php");
    $this->assertElementContainsText('css=#denied1 td:nth-child(2)', $now);
    $this->assertElementContainsText('css=#denied1 td:nth-child(3)', 'Mr S Testing');
    $this->assertElementContainsText('css=#denied1 td:nth-child(4)', '/question/add/add_questions_by_paper.php?question_paper=');
    $this->assertElementContainsText('css=#denied1 td:nth-child(5)', 'A mandatory GET variable is missing.');

  }
}
?>