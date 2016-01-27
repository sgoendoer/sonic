<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use sgoendoer\Sonic\Request\URL;

class RequestUnitTest extends PHPUnit_Framework_TestCase
{
	public function testURL()
	{
		$url = 'http://domain.com:123/a/b/c';
		
		$this->assertEquals('http', URL::getProtocolFromURL($url));
		$this->assertEquals(123, URL::getPortFromURL($url));
		$this->assertEquals('/a/b/c', URL::getPathFromURL($url));
		$this->assertEquals('domain.com:123', URL::getDomainFromURL($url));
		
		$url = 'https://sub.domain.com';
		
		$this->assertEquals('https', URL::getProtocolFromURL($url));
		$this->assertEquals(80, URL::getPortFromURL($url));
		$this->assertEquals('/', URL::getPathFromURL($url));
		$this->assertEquals('sub.domain.com', URL::getDomainFromURL($url));
		
		$url = 'http://domain.com:123/';
		
		$this->assertEquals('http', URL::getProtocolFromURL($url));
		$this->assertEquals(123, URL::getPortFromURL($url));
		$this->assertEquals('/', URL::getPathFromURL($url));
		$this->assertEquals('domain.com:123', URL::getDomainFromURL($url));
	}
}

?>