<?php
require_once 'shared.inc.php';

class ExamScrtiptMarksCalculationTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected $install_type;
  protected $page_root;

  protected function setUp() {
    $this->install_type = get_install_type();
    $this->page_root = get_root_url();

    $this->setBrowser("*firefox");
    $this->setBrowserUrl($this->page_root . '/');
  }

  public function testUnanswered() {
    do_staff_login($this);

    $this->open("/reports/class_totals.php?paperID=6&startdate=20130101000000&enddate=20530115120000&repmodule=&repcourse=%&sortby=name&module=3&folder=&percent=100&absent=0&studentsonly=1&ordering=asc");
    $this->click("css=tr#res1 td.greyln img");
    $this->click("id=item1b");
    $this->waitForPopUp("paper", "30000");
    $this->selectWindow("name=paper");

    // Individual Question Marks
    $this->assertElementContainsText('//table[2]/tbody/tr[2]/td[2]/p[4]/span', '0 out of 1');
    $this->assertElementContainsText('//table[2]/tbody/tr[4]/td[2]/p/span', '0 out of 2');
    $this->assertElementContainsText('//table[2]/tbody/tr[7]/td[2]/p[4]/span', '0 out of 1');
    $this->assertElementContainsText('//table[3]/tbody/tr/td[2]/p/span', '0 out of 2');
    $this->assertElementContainsText('//table[3]/tbody/tr[4]/td[2]/p[4]/span', '0 out of 1');
    $this->assertElementContainsText('//table[3]/tbody/tr[6]/td[2]/p/span', '0 out of 2');
    $this->assertElementContainsText('//table[4]/tbody/tr[2]/td[2]/p[4]/span', '0 out of 1');
    $this->assertElementContainsText('//table[4]/tbody/tr[4]/td[2]/p/span', '0 out of 2');
    $this->assertElementContainsText('//table[4]/tbody/tr[7]/td[2]/p[4]/span', '0 out of 1');
    $this->assertElementContainsText('//table[5]/tbody/tr/td[2]/p/span', '0 out of 2');
    $this->assertElementContainsText('//table[5]/tbody/tr[4]/td[2]/p[4]/span', '0 out of 1');
    $this->assertElementContainsText('//table[5]/tbody/tr[6]/td[2]/p/span', '0 out of 2');

    // Overall Marks
    $this->assertElementContainsText('//div[5]/table/tbody/tr[2]/td[2]', '0 out of 18');
    $this->assertElementContainsText('//div[5]/table/tbody/tr[3]/td[2]', '40%');
    $this->assertElementContainsText('//div[5]/table/tbody/tr[4]/td[2]', '0.00%');
  }

  public function testAllCorrect() {
    do_staff_login($this);

    $this->open("/reports/class_totals.php?paperID=6&startdate=20130101000000&enddate=20530115120000&repmodule=&repcourse=%&sortby=name&module=3&folder=&percent=100&absent=0&studentsonly=1&ordering=asc");
    $this->click("css=tr#res2 td.greyln img");
    $this->click("id=item1b");
    $this->waitForPopUp("paper", "30000");
    $this->selectWindow("name=paper");

    // Individual Question Marks
    $this->assertElementContainsText('//table[2]/tbody/tr[2]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[2]/tbody/tr[4]/td[2]/p/span', '2 out of 2');
    $this->assertElementContainsText('//table[2]/tbody/tr[7]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[3]/tbody/tr/td[2]/p/span', '2 out of 2');
    $this->assertElementContainsText('//table[3]/tbody/tr[4]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[3]/tbody/tr[6]/td[2]/p/span', '2 out of 2');
    $this->assertElementContainsText('//table[4]/tbody/tr[2]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[4]/tbody/tr[4]/td[2]/p/span', '2 out of 2');
    $this->assertElementContainsText('//table[4]/tbody/tr[7]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[5]/tbody/tr/td[2]/p/span', '2 out of 2');
    $this->assertElementContainsText('//table[5]/tbody/tr[4]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[5]/tbody/tr[6]/td[2]/p/span', '2 out of 2');

    // Overall Marks
    $this->assertElementContainsText('//div[5]/table/tbody/tr[2]/td[2]', '18 out of 18');
    $this->assertElementContainsText('//div[5]/table/tbody/tr[3]/td[2]', '40%');
    $this->assertElementContainsText('//div[5]/table/tbody/tr[4]/td[2]', '100.00%');
  }

  public function testAllCorrectWithTolerance() {
    do_staff_login($this);

    $this->open("/reports/class_totals.php?paperID=6&startdate=20130101000000&enddate=20530115120000&repmodule=&repcourse=%&sortby=name&module=3&folder=&percent=100&absent=0&studentsonly=1&ordering=asc");
    $this->click("css=tr#res3 td.greyln img");
    $this->click("id=item1b");
    $this->waitForPopUp("paper", "30000");
    $this->selectWindow("name=paper");

    // Individual Question Marks
    $this->assertElementContainsText('//table[2]/tbody/tr[2]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[2]/tbody/tr[4]/td[2]/p/span', '2 out of 2');
    $this->assertElementContainsText('//table[2]/tbody/tr[7]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[3]/tbody/tr/td[2]/p/span', '2 out of 2');
    $text = $this->getText('//table[2]/tbody/tr[4]/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/ cm\)/', $text);
    $this->assertRegExp('/with a tolerance of 1$/', $text);
    $this->assertElementContainsText('//table[3]/tbody/tr[4]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[3]/tbody/tr[6]/td[2]/p/span', '2 out of 2');
    $text = $this->getText('//table[3]/tbody/tr[6]/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/ cm\)/', $text);
    $this->assertRegExp('/with a tolerance of 1$/', $text);
    $this->assertElementContainsText('//table[4]/tbody/tr[2]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[4]/tbody/tr[4]/td[2]/p/span', '2 out of 2');
    $text = $this->getText('//table[4]/tbody/tr[4]/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/ cm\)/', $text);
    $this->assertRegExp('/with a tolerance of 1$/', $text);
    $this->assertElementContainsText('//table[4]/tbody/tr[7]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[5]/tbody/tr/td[2]/p/span', '2 out of 2');
    $text = $this->getText('//table[5]/tbody/tr/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/ cm\)/', $text);
    $this->assertElementContainsText('//table[5]/tbody/tr/td[2]/table/tbody/tr/td[2]', 'with a tolerance of 5%');
    $this->assertElementContainsText('//table[5]/tbody/tr[4]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[5]/tbody/tr[6]/td[2]/p/span', '2 out of 2');
    $text = $this->getText('//table[5]/tbody/tr[6]/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/ cm\)/', $text);
    $this->assertElementContainsText('//table[5]/tbody/tr[6]/td[2]/table/tbody/tr/td[2]', 'with a tolerance of 5%');

    // Overall Marks
    $this->assertElementContainsText('//div[5]/table/tbody/tr[2]/td[2]', '18 out of 18');
    $this->assertElementContainsText('//div[5]/table/tbody/tr[3]/td[2]', '40%');
    $this->assertElementContainsText('//div[5]/table/tbody/tr[4]/td[2]', '100.00%');
  }

  public function testPartial() {
    do_staff_login($this);

    $this->open("/reports/class_totals.php?paperID=6&startdate=20130101000000&enddate=20530115120000&repmodule=&repcourse=%&sortby=name&module=3&folder=&percent=100&absent=0&studentsonly=1&ordering=asc");
    $this->click("css=tr#res4 td.greyln img");
    $this->click("id=item1b");
    $this->waitForPopUp("paper", "30000");
    $this->selectWindow("name=paper");

    // Individual Question Marks
    $this->assertElementContainsText('//table[2]/tbody/tr[2]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[2]/tbody/tr[4]/td[2]/p/span', '2 out of 2');
    $this->assertElementContainsText('//table[2]/tbody/tr[7]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[3]/tbody/tr/td[2]/p/span', '2 out of 2');
    $this->assertElementContainsText('//table[3]/tbody/tr[4]/td[2]/p[4]/span', '0.5 out of 1');
    $text = $this->getText('//table[3]/tbody/tr[4]/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/with a tolerance of 1$/', $text);
    $this->assertElementContainsText('//table[3]/tbody/tr[6]/td[2]/p/span', '1 out of 2');
    $text = $this->getText('//table[3]/tbody/tr[6]/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/ cm\)/', $text);
    $this->assertRegExp('/with a tolerance of 1.5$/', $text);
    $this->assertElementContainsText('//table[4]/tbody/tr[2]/td[2]/p[4]/span', '0.5 out of 1');
    $text = $this->getText('//table[4]/tbody/tr[2]/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/with a tolerance of 1$/', $text);
    $this->assertElementContainsText('//table[4]/tbody/tr[4]/td[2]/p/span', '1 out of 2');
    $text = $this->getText('//table[4]/tbody/tr[4]/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/ cm\)/', $text);
    $this->assertRegExp('/with a tolerance of 1.5$/', $text);
    $this->assertElementContainsText('//table[4]/tbody/tr[7]/td[2]/p[4]/span', '0.5 out of 1');
    $this->assertElementContainsText('//table[4]/tbody/tr[7]/td[2]/table/tbody/tr/td[2]', 'with a tolerance of 5%');
    $this->assertElementContainsText('//table[5]/tbody/tr/td[2]/p/span', '1 out of 2');
    $text = $this->getText('//table[5]/tbody/tr/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/ cm\)/', $text);
    $this->assertElementContainsText('//table[5]/tbody/tr/td[2]/table/tbody/tr/td[2]', 'with a tolerance of 8%');
    $this->assertElementContainsText('//table[5]/tbody/tr[4]/td[2]/p[4]/span', '0.5 out of 1');
    $this->assertElementContainsText('//table[5]/tbody/tr[4]/td[2]/table/tbody/tr/td[2]', 'with a tolerance of 5%');
    $this->assertElementContainsText('//table[5]/tbody/tr[6]/td[2]/p/span', '1 out of 2');
    $text = $this->getText('//table[5]/tbody/tr[6]/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/ cm\)/', $text);
    $this->assertElementContainsText('//table[5]/tbody/tr[6]/td[2]/table/tbody/tr/td[2]', 'with a tolerance of 8%');

    // Overall Marks
    $this->assertElementContainsText('//div[5]/table/tbody/tr[2]/td[2]', '12 out of 18');
    $this->assertElementContainsText('//div[5]/table/tbody/tr[3]/td[2]', '40%');
    $this->assertElementContainsText('//div[5]/table/tbody/tr[4]/td[2]', '66.67%');
  }

  public function testAllIncorrect() {
    do_staff_login($this);

    $this->open("/reports/class_totals.php?paperID=6&startdate=20130101000000&enddate=20530115120000&repmodule=&repcourse=%&sortby=name&module=3&folder=&percent=100&absent=0&studentsonly=1&ordering=asc");
    $this->click("css=tr#res5 td.greyln img");
    $this->click("id=item1b");
    $this->waitForPopUp("paper", "30000");
    $this->selectWindow("name=paper");

    // Individual Question Marks
    $this->assertElementContainsText('//table[2]/tbody/tr[2]/td[2]/p[4]/span', '0 out of 1');
    $this->assertElementContainsText('//table[2]/tbody/tr[4]/td[2]/p/span', '0 out of 2');
    $this->assertElementContainsText('//table[2]/tbody/tr[7]/td[2]/p[4]/span', '-0.5 out of 1');
    $this->assertElementContainsText('//table[3]/tbody/tr/td[2]/p/span', '-1 out of 2');
    $this->assertElementContainsText('//table[3]/tbody/tr[4]/td[2]/p[4]/span', '0 out of 1');
    $this->assertElementContainsText('//table[3]/tbody/tr[6]/td[2]/p/span', '0 out of 2');
    $this->assertElementContainsText('//table[4]/tbody/tr[2]/td[2]/p[4]/span', '-0.5 out of 1');
    $this->assertElementContainsText('//table[4]/tbody/tr[4]/td[2]/p/span', '-1 out of 2');
    $this->assertElementContainsText('//table[4]/tbody/tr[7]/td[2]/p[4]/span', '0 out of 1');
    $this->assertElementContainsText('//table[5]/tbody/tr/td[2]/p/span', '0 out of 2');
    $this->assertElementContainsText('//table[5]/tbody/tr[4]/td[2]/p[4]/span', '-0.5 out of 1');
    $this->assertElementContainsText('//table[5]/tbody/tr[6]/td[2]/p/span', '-1 out of 2');

    // Overall Marks
    $this->assertElementContainsText('//div[5]/table/tbody/tr[2]/td[2]', '-4.5 out of 18');
    $this->assertElementContainsText('//div[5]/table/tbody/tr[3]/td[2]', '40%');
    $this->assertElementContainsText('//div[5]/table/tbody/tr[4]/td[2]', '-25.00%');
  }

  public function testMixed() {
    do_staff_login($this);

    $this->open("/reports/class_totals.php?paperID=6&startdate=20130101000000&enddate=20530115120000&repmodule=&repcourse=%&sortby=name&module=3&folder=&percent=100&absent=0&studentsonly=1&ordering=asc");
    $this->click("css=tr#res6 td.greyln img");
    $this->click("id=item1b");
    $this->waitForPopUp("paper", "30000");
    $this->selectWindow("name=paper");

    // Individual Question Marks
    $this->assertElementContainsText('//table[2]/tbody/tr[2]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[2]/tbody/tr[4]/td[2]/p/span', '0 out of 2');
    $this->assertElementContainsText('//table[2]/tbody/tr[7]/td[2]/p[4]/span', '-0.5 out of 1');
    $this->assertElementContainsText('//table[3]/tbody/tr/td[2]/p/span', '2 out of 2');
    $text = $this->getText('//table[3]/tbody/tr/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/ cm\)/', $text);
    $this->assertRegExp('/with a tolerance of 1$/', $text);
    $this->assertElementContainsText('//table[3]/tbody/tr[4]/td[2]/p[4]/span', '0.5 out of 1');
    $text = $this->getText('//table[3]/tbody/tr[4]/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/with a tolerance of 1$/', $text);
    $this->assertElementContainsText('//table[3]/tbody/tr[6]/td[2]/p/span', '0 out of 2');
    $this->assertElementContainsText('//table[4]/tbody/tr[2]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[4]/tbody/tr[4]/td[2]/p/span', '2 out of 2');
    $text = $this->getText('//table[4]/tbody/tr[4]/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/ cm\)/', $text);
    $this->assertRegExp('/with a tolerance of 1$/', $text);
    $this->assertElementContainsText('//table[4]/tbody/tr[7]/td[2]/p[4]/span', '0 out of 1');
    $this->assertElementContainsText('//table[5]/tbody/tr/td[2]/p/span', '1 out of 2');
    $text = $this->getText('//table[5]/tbody/tr/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/ cm\)/', $text);
    $this->assertElementContainsText('//table[5]/tbody/tr/td[2]/table/tbody/tr/td[2]', 'with a tolerance of 8%');
    $this->assertElementContainsText('//table[5]/tbody/tr[4]/td[2]/p[4]/span', '1 out of 1');
    $this->assertElementContainsText('//table[5]/tbody/tr[6]/td[2]/p/span', '1 out of 2');
    $text = $this->getText('//table[5]/tbody/tr[6]/td[2]/table/tbody/tr/td[2]');
    $this->assertRegExp('/ cm\)/', $text);
    $this->assertElementContainsText('//table[5]/tbody/tr[6]/td[2]/table/tbody/tr/td[2]', 'with a tolerance of 8%');

    // Overall Marks
    $this->assertElementContainsText('//div[5]/table/tbody/tr[2]/td[2]', '9 out of 18');
    $this->assertElementContainsText('//div[5]/table/tbody/tr[3]/td[2]', '40%');
    $this->assertElementContainsText('//div[5]/table/tbody/tr[4]/td[2]', '50.00%');
  }
}
?>