<?php namespace sgoendoer\Sonic\Request;

use sgoendoer\Sonic\Request\MalformedResponseHeaderException;
use sgoendoer\Sonic\Request\MalformedRequestHeaderException;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Crypt\Signature;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Model\ResponseObjectBuilder;


/**
 * AbstractResponse
 * version 20160129
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
abstract class AbstractResponse
{
	protected $expectedGID		= NULL;
	protected $statusCode		= NULL;
	protected $statusMessage	= NULL;
	protected $headers			= NULL;
	protected $body				= NULL;
	
	public function getStringForResponseSignature()
	{
		return $this->headers[SONIC_HEADER__TARGET_API]
				. $this->headers[SONIC_HEADER__DATE]
				. $this->headers[SONIC_HEADER__PLATFORM_GID]
				. $this->headers[SONIC_HEADER__SOURCE_GID]
				. $this->headers[SONIC_HEADER__RANDOM]
				. $this->body;
	}
	
	public function verify()
	{
		return ($this->verifyHeaders() && $this->verifyDataFormat() && $this->verifySignature());
	}
	
	protected function verifyHeaders()
	{
		if(!array_key_exists(SONIC_HEADER__TARGET_API, $this->headers))
			throw new MalformedResponseHeaderException("Malformed response: Header " . SONIC_HEADER__TARGET_API . " missing");
		else if(!array_key_exists(SONIC_HEADER__DATE, $this->headers))
			throw new MalformedResponseHeaderException("Malformed response: Header " . SONIC_HEADER__DATE . " missing");
		else if(!array_key_exists(SONIC_HEADER__SIGNATURE, $this->headers))
			throw new MalformedResponseHeaderException("Malformed response: Header " . SONIC_HEADER__SIGNATURE . " missing");
		else if(!array_key_exists(SONIC_HEADER__RANDOM, $this->headers))
			throw new MalformedResponseHeaderException("Malformed response: Header " . SONIC_HEADER__RANDOM . " missing");
		else if(!array_key_exists(SONIC_HEADER__SOURCE_GID, $this->headers))
			throw new MalformedResponseHeaderException("Malformed response: Header " . SONIC_HEADER__SOURCE_GID . " missing");
		else if(!array_key_exists(SONIC_HEADER__PLATFORM_GID, $this->headers))
			throw new MalformedRequestHeaderException("Malformed response: Header " . SONIC_HEADER__PLATFORM_GID . " missing");
		else return true;
	}
	
	protected function verifyDataFormat()
	{
		// TODO validate data and formats
		if(!XSDDateTime::validateXSDDateTime($this->headers[SONIC_HEADER__DATE]))
			throw new MalformedRequestHeaderException("Malformed response: Header " . SONIC_HEADER__DATE . " malformed: " . $this->headers[SONIC_HEADER__DATE]);
		if(!XSDDateTime::validateXSDDateTime($this->headers[SONIC_HEADER__DATE]))
			throw new MalformedRequestHeaderException("Malformed response: Header " . SONIC_HEADER__DATE . " malformed: " . $this->headers[SONIC_HEADER__DATE]);

		return true;
	}
	
	protected function verifySignature()
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($this->headers[SONIC_HEADER__SOURCE_GID]);
		
		if($this->expectedGID !== NULL)
		{
			if($this->headers[SONIC_HEADER__SOURCE_GID] != $this->expectedGID && $this->headers[SONIC_HEADER__SOURCE_GID] != $socialRecord->getPlatformGID())
			{
				throw new MalformedRequestHeaderException('Request signature from unexpected source GID. Expected [' . $this->expectedGID . '] found [' . $this->headers[SONIC_HEADER__SOURCE_GID] . ']');
			}
		}
		
		if(!Signature::verifySignature($this->getStringForResponseSignature(), $socialRecord->getAccountPublicKey(), $this->headers[SONIC_HEADER__SIGNATURE]))
			throw new MalformedRequestHeaderException("Invalid response signature");
		else
			return true;
	}
	
	public function getExpectedGID()
	{
		return $this->expectedGID;
	}
	
	public function getHeaderDate()
	{
		return $this->headers[SONIC_HEADER__DATE];
	}
	
	public function getHeaderTargetAPI()
	{
		return $this->headers[SONIC_HEADER__TARGET_API];
	}
	
	public function getHeaderRandom()
	{
		return $this->headers[SONIC_HEADER__RANDOM];
	}
	
	public function getHeaderSignature()
	{
		return $this->headers[SONIC_HEADER__SIGNATURE];
	}
	
	public function getHeaderPlatformGID()
	{
		return $this->headers[SONIC_HEADER__PLATFORM_GID];
	}
	
	public function getHeaderSourceGID()
	{
		return $this->headers[SONIC_HEADER__SOURCE_GID];
	}
	
	public function getResponseHeaders()
	{
		return $this->headers;
	}
	
	/**
	 * returns the body of the response
	 * 
	 * returns string
	 */
	public function getResponseBody()
	{
		return $this->body;
	}
	
	/**
	 * retrieves the request body as a ResponseObject 
	 *
	 * returns ResponseObject
	 */
	public function getDecodedBody()
	{
		return ResponseObjectBuilder::buildFromJSON($this->body);
	}
	
	/**
	 * retrieves the payload ("body") of the response as a string
	 * 
	 * returns string
	 */
	public function getPayload()
	{
		return $this->getDecodedBody()->getBody();
	}
	
	public function getResponseStatusCode()
	{
		return $this->statusCode;
	}
	
	public function getResponseStatusMessage()
	{
		return $this->statusMessage;
	}
}

?>