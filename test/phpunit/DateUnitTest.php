<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use sgoendoer\Sonic\Date\XSDDateTime;

date_default_timezone_set('Europe/Berlin');

class XSDDateTimeUnitTest extends PHPUnit_Framework_TestCase
{
	public function testXSDDateTime()
	{
		$time = 1449479356;
		$datetime = '2015-12-07T10:09:16+01:00';
		
		//$this->assertEquals(false, XSDDateTime::validateXSDDateTime($impossible));
		$this->assertEquals(true, XSDDateTime::validateXSDDateTime($datetime));
		$this->assertEquals($datetime, XSDDateTime::getXSDDateTime($time));
		$this->assertEquals($time, XSDDateTime::getUnixTimestamp($datetime));
	}
}

?>