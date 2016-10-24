<?php namespace sgoendoer\Sonic\Request;

use sgoendoer\Sonic\Crypt\PublicKey;
use sgoendoer\Sonic\Crypt\Signature;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Request\MalformedRequestHeaderException;

/**
 * AbstractRequest
 * version 20160111
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
abstract class AbstractRequest
{
	protected $method			= NULL; // scheme, i.e. http or https
	protected $server			= NULL; // profile location, i.e. the domain and path to the api
	protected $port				= NULL; // unused as of now
	protected $path				= NULL; // path of the url without apiRoot and domain
	protected $headers			= NULL;
	protected $body				= NULL;
	
	public function getStringForRequestSignature()
	{
		return $this->method
				. $this->server . $this->path
				. $this->headers[SONIC_HEADER__TARGET_API]
				. $this->headers[SONIC_HEADER__DATE]
				. $this->headers[SONIC_HEADER__PLATFORM_GID]
				. $this->headers[SONIC_HEADER__SOURCE_GID]
				. $this->headers[SONIC_HEADER__RANDOM]
				. $this->body;
	}
	
	protected function verifyRequest()
	{
		return ($this->verifyHeaders() && $this->verifyDataFormat() && $this->verifySignature());
	}
	
	protected function verifyHeaders()
	{
		if(!array_key_exists(SONIC_HEADER__TARGET_API, $this->headers))
			throw new MalformedRequestHeaderException("Malformed request: Header " . SONIC_HEADER__TARGET_API . " missing");
		else if(!array_key_exists(SONIC_HEADER__SOURCE_GID, $this->headers))
			throw new MalformedRequestHeaderException("Malformed request: Header " . SONIC_HEADER__SOURCE_GID . " missing");
		else if(!array_key_exists(SONIC_HEADER__PLATFORM_GID, $this->headers))
			throw new MalformedRequestHeaderException("Malformed request: Header " . SONIC_HEADER__PLATFORM_GID . " missing");
		else if(!array_key_exists(SONIC_HEADER__DATE, $this->headers))
			throw new MalformedRequestHeaderException("Malformed request: Header " . SONIC_HEADER__DATE . " missing");
		else if(!array_key_exists(SONIC_HEADER__SIGNATURE, $this->headers))
			throw new MalformedRequestHeaderException("Malformed request: Header " . SONIC_HEADER__SIGNATURE . " missing");
		else if(!array_key_exists(SONIC_HEADER__RANDOM, $this->headers))
			throw new MalformedRequestHeaderException("Malformed request: Header " . SONIC_HEADER__RANDOM . " missing");
		else return true;
	}
	
	protected function verifyDataFormat()
	{
		// TODO validate data and formats
		if(!XSDDateTime::validateXSDDateTime($this->headers[SONIC_HEADER__DATE]))
			throw new MalformedRequestHeaderException("Malformed request: Header " . SONIC_HEADER__DATE . " malformed: " . $this->headers[SONIC_HEADER__DATE]);
		
		return true;
	}
	
	protected function verifySignature()
	{
		$publicAccountKey = PublicKey::formatPEM(SocialRecordManager::retrieveSocialRecord($this->headers[SONIC_HEADER__SOURCE_GID])->getAccountPublicKey());
		
		if(!Signature::verifySignature($this->getStringForRequestSignature(), $publicAccountKey, $this->headers[SONIC_HEADER__SIGNATURE]))
			throw new MalformedRequestHeaderException("Invalid request signature!");
		else return true;
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
	
	public function getHeaderAuthToken()
	{
		return $this->headers[SONIC_HEADER__AUTH_TOKEN];
	}
	
	public function getHeaders()
	{
		return $this->headers;
	}
	
	public function getBody()
	{
		return $this->body;
	}
	
	public function getMethod()
	{
		return $this->method;
	}
}

?>