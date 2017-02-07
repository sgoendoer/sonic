<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use PHPUnit\Framework\TestCase;

use sgoendoer\Sonic\Date\XSDDateTime;

date_default_timezone_set('Europe/Berlin');

class XSDDateTimeUnitTest extends TestCase
{
	// valid
	public $validXSD1 = "2017-02-07T12:13:14+01:00"; 
	public $validXSD2 = "2017-02-07T12:13:14Z";
	
	// invalid
	public $invalidXSD1 = "2017-02-07T25:13:14+01:00";
	public $invalidXSD2 = "42017-02-07T12:13:14+01:00";
	public $invalidXSD3 = "2017-02-07T12:13:61+01:00";
	public $invalidXSD4 = "2017-02-07Tl2:13:14+01:00";
	
	// conversion
	public $time = 1449479356;
	public $datetime = '2015-12-07T10:09:16+01:00';
	
	public function testXSDDateTime()
	{
		$this->assertEquals($datetime, XSDDateTime::getXSDDateTime($time));
		$this->assertEquals($time, XSDDateTime::getUnixTimestamp($datetime));
	}
	
	public function testXSDDateTimeValidation()
	{
		assertTrue(XSDDateTime::validateXSDDateTime($validXSD1));
		assertTrue(XSDDateTime::validateXSDDateTime($validXSD2));
		
		assertFalse(XSDDateTime::validateXSDDateTime($invalidXSD1));
		assertFalse(XSDDateTime::validateXSDDateTime($invalidXSD2));
		assertFalse(XSDDateTime::validateXSDDateTime($invalidXSD3));
		assertFalse(XSDDateTime::validateXSDDateTime($invalidXSD4));
	}
}

?>