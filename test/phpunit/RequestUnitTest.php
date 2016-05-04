<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use sgoendoer\Sonic\Request\URL;

date_default_timezone_set('Europe/Berlin');

class RequestUnitTest extends PHPUnit_Framework_TestCase
{
	public function testURL()
	{
		$urls = array(
			array('http://abc.domain.com:123/path/subfolder', 'http', 'abc.domain.com:123', 123, '/path/subfolder'),
			array('ftp://domain.de/path/subfolder', 'ftp', 'domain.de', 80, '/path/subfolder'),
			array('http://domain.com:123/a/b/c', 'http', 'domain.com:123', 123, '/a/b/c'),
			array('https://sub.domain.com', 'https', 'sub.domain.com', 80, '/'),
			array('http://domain.com:123/', 'http', 'domain.com:123', 123, '/')
		);
		
		foreach($urls as $url)
		{
			$this->assertEquals($url[1], URL::getProtocolFromURL($url[0]));
			$this->assertEquals($url[2], URL::getDomainFromURL($url[0]));
			$this->assertEquals($url[3], URL::getPortFromURL($url[0]));
			$this->assertEquals($url[4], URL::getPathFromURL($url[0]));
		}
	}
}

?>