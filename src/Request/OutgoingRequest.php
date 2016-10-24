<?php namespace sgoendoer\Sonic\Request;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Config\Configuration;
use sgoendoer\Sonic\Crypt\Random;
use sgoendoer\Sonic\Crypt\Signature;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Request\AbstractRequest;

/**
 * OutgoingRequest
 * version 20160111
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class OutgoingRequest extends AbstractRequest
{
	public function __construct($expectedGID = NULL)
	{
		$this->headers = array();
		$this->headers[SONIC_HEADER__RANDOM] = Random::getRandom();
		$this->headers[SONIC_HEADER__DATE] = XSDDateTime::getXSDDatetime();
		$this->headers[SONIC_HEADER__TARGET_API] = SONIC_SDK__API_VERSION;
		$this->headers[SONIC_HEADER__PLATFORM_GID] = Sonic::getPlatformGlobalID();
		$this->headers[SONIC_HEADER__SOURCE_GID] = Sonic::getContextGlobalID();
	}
	
	public function send()
	{
		if(!$this->verifyRequest())
			throw new \Exception("Error: Malformed request!");
		else
		{
			$header = array();
			foreach ($this->headers as $key => $value)
				$header[] = $key . ': ' . $value;
			
			$ch = curl_init('http://' . $this->server . $this->path);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			if($this->method == 'GET')
			{
				curl_setopt($ch, CURLOPT_HTTPGET, true);
				
				$header[] = 'User-Agent: ' . SONIC_REQUEST__USERAGENT;
				$header[] = 'Connection: close';
				
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			}
			elseif($this->method == 'POST')
			{
				curl_setopt($ch, CURLOPT_POST, true);
				
				$header[] = 'User-Agent: ' . SONIC_REQUEST__USERAGENT;
				$header[] = 'Content-type: application/json';
				$header[] = 'Connection: close';
				$header[] = 'Content-Length: ' . strlen($this->body);
				$header[] = 'Expect: '; // explicitly un-setting Expect header
				
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				
				curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
			}
			elseif($this->method == 'PUT')
			{
				curl_setopt($ch, CURLOPT_POST, true);
				
				$header[] = 'User-Agent: ' . SONIC_REQUEST__USERAGENT;
				$header[] = 'Content-type: application/json';
				$header[] = 'Connection: close';
				$header[] = 'Content-Length: ' . strlen($this->body);
				$header[] = 'Expect: '; // explicitly un-setting Expect header
				
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				
				curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); //curl PUT only accepts files
			}
			elseif($this->method == 'DELETE')
			{
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				
				$header[] = 'User-Agent: ' . SONIC_REQUEST__USERAGENT;
				$header[] = 'Connection: close';
				$header[] = 'Expect: '; // explicitly un-setting Expect header
				
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			}
			
			if (Configuration::getCurlVerbose() >= 1) curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_HEADER, true);
			
			$response = curl_exec($ch);
			
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($response, 0, $header_size);
			
			$body = substr($response, $header_size);
			
			if (curl_errno($ch) != CURLE_OK)
			{
				throw new \Exception('Connection error: ' . curl_error($ch));
			}
			
			curl_close($ch);
			
			return array('headers' => $header, 'body' => $body);
		}
	}
	
	public function signRequest($privateAccountKey)
	{
		$this->headers[SONIC_HEADER__SIGNATURE] = Signature::createSignature($this->getStringForRequestSignature(), $privateAccountKey);
	}
	
	public function setServer($domain)
	{
		$this->server = $domain;
	}
	
	public function setPort($port)
	{
		$this->port = $port;
	}
	
	public function setPath($path)
	{
		$this->path = $path;
	}
	
	public function setRequestMethod($method)
	{
		$this->method = $method;
	}
	
	public function setRequestBody($body)
	{
		$this->body = $body;
	}
	
	public function setHeaderPlatformGID($gid)
	{
		$this->headers[SONIC_HEADER__PLATFORM_GID] = $gid;
	}
	
	public function setHeaderSourceGID($gid)
	{
		$this->headers[SONIC_HEADER__SOURCE_GID] = $gid;
	}
	
	public function setHeaderDate($date)
	{
		$this->headers[SONIC_HEADER__DATE] = $date;
	}
	
	public function setHeaderTargetAPI($api)
	{
		$this->headers[SONIC_HEADER__TARGET_API] = $api;
	}
	
	Public function setHeaderAuthToken($token)
	{
		$this->headers[SONIC_HEADER__AUTH_TOKEN] = $token;
	}
}

?>