<?php namespace sgoendoer\Sonic\Request;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Crypt\Random;
use sgoendoer\Sonic\Crypt\Signature;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Request\AbstractRequest;

/**
 * OutgoingResponse
 * version 20160111
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class OutgoingResponse extends AbstractResponse
{
	public function __construct()
	{
		$this->headers = array();
		$this->headers[SONIC_HEADER__RANDOM]		= Random::getRandom();
		$this->headers[SONIC_HEADER__DATE]			= XSDDateTime::getXSDDatetime();
		$this->headers[SONIC_HEADER__TARGET_API]	= SONIC_SDK__API_VERSION;
		$this->headers[SONIC_HEADER__PLATFORM_GID]	= Sonic::getPlatformGlobalID();
		$this->headers[SONIC_HEADER__SOURCE_GID]	= Sonic::getContextGlobalID();
	}
	
	public function send()
	{
		if(!$this->verify())
			throw new \Exception("Malformed response");
		else
		{
			http_response_code($this->statusCode);
			
			foreach($this->headers as $header => $value)
			{
				header($header . ': ' . $value);
			}
		}
		
		echo $this->body;
	}
	
	public function signResponse($privateAccountKey)
	{
		$this->headers[SONIC_HEADER__SIGNATURE] = Signature::createSignature($this->getStringForResponseSignature(), $privateAccountKey);
	}
	
	public function setResponseStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;
	}
	
	public function setResponseBody($body)
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
}

?>