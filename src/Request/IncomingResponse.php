<?php namespace sgoendoer\Sonic\Request;

use sgoendoer\Sonic\Request\AbstractResponse;

/**
 * IncomingResponse
 * version 20160104
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class IncomingResponse extends AbstractResponse
{
	public function __construct($response = NULL, $expectedGID = NULL)
	{
		$this->expectedGID = $expectedGID;
		
		// read data from response
		$response['headers'] = explode("\r\n", $response['headers']);
		
		// get and parse first response line
		$first = explode(' ', array_shift($response['headers'])); //HTTP/1.1 200 OK
		
		if($first[1] == '100') // if first line of header was HTTP/1.1 100 Continue...
		{
			array_shift($response['headers']);
			$first = explode(' ', array_shift($response['headers']));
		}
		
		$this->statusCode = $first[1];
		$this->statusMessage = $first[2];
		
		foreach($response['headers'] as $header)
		{
			if($header != '')
			{
				list($key, $value) = explode(': ', $header);
				$this->headers[trim($key)] = trim($value);
			}
		}
		
		$this->body = $response['body'];
		
		$this->verify();
	}
}

?>