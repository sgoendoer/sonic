<?php namespace sgoendoer\Sonic\Request;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Crypt\Random;
use sgoendoer\Sonic\Crypt\Signature;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Request\AbstractRequest;
use sgoendoer\Sonic\Request\MalformedResponseException;

/**
 * OutgoingResponse
 * version 20160509
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class OutgoingResponse extends AbstractResponse
{
	/**
	 * constructor for building a Sonic compliant HTTP response.
	 */
	public function __construct()
	{
		$this->headers = array();
		$this->headers[SONIC_HEADER__RANDOM]		= Random::getRandom();
		$this->headers[SONIC_HEADER__DATE]			= XSDDateTime::getXSDDatetime();
		$this->headers[SONIC_HEADER__TARGET_API]	= SONIC_SDK__API_VERSION;
		$this->headers[SONIC_HEADER__PLATFORM_GID]	= Sonic::getPlatformGlobalID();
		$this->headers[SONIC_HEADER__SOURCE_GID]	= Sonic::getContextGlobalID();
		
		$this->statusCode = 200;
		$this->statusMessage = 'OK';
	}
	
	/**
	 * sends the HTTP response
	 * 
	 * @throws MalformesResponseException if the response is invalid
	 */
	public function send()
	{
		if(!$this->verify())
			throw new \MalformesResponseException("Malformed response");
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
	
	/**
	 * signs the response object
	 */
	public function signResponse($privateAccountKey)
	{
		$this->headers[SONIC_HEADER__SIGNATURE] = Signature::createSignature($this->getStringForResponseSignature(), $privateAccountKey);
	}
	
	/**
	 * sets the HTTP status code
	 */
	public function setResponseStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;
	}
	
	/**
	 * sets the response's body
	 */
	public function setResponseBody($body)
	{
		$this->body = $body;
	}
	
	/**
	 * sets the SonicPlatformGID header
	 */
	public function setHeaderPlatformGID($gid)
	{
		$this->headers[SONIC_HEADER__PLATFORM_GID] = $gid;
	}
	
	/**
	 * sets the SonicSourceGID header
	 */
	public function setHeaderSourceGID($gid)
	{
		$this->headers[SONIC_HEADER__SOURCE_GID] = $gid;
	}
	
	/**
	 * sets the SonicDate header
	 */
	public function setHeaderDate($date)
	{
		$this->headers[SONIC_HEADER__DATE] = $date;
	}
	
	/**
	 * sets the SonicTargetAPI header
	 */
	public function setHeaderTargetAPI($api)
	{
		$this->headers[SONIC_HEADER__TARGET_API] = $api;
	}
}

?>