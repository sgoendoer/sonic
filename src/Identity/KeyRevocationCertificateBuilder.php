<?php namespace sgoendoer\Sonic\Identity;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Crypt\Signature;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Identity\KeyRevocationCertificate;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Date\XSDDateTime;

/**
 * KeyRevocationCertificateBuilder
 * version 20160105
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class KeyRevocationCertificateBuilder
{
	private $globalID			= NULL;
	private $key				= NULL;	// the revoked public key
	private $datetime			= NULL;	// XSD datetime format e.g. 2015-01-01T11:11:11Z
	private $reason				= NULL;
	private $signature			= NULL;
	private $personalPrivateKey	= NULL; // the personalPrivateKey to sign the KRC with
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		$json = json_decode($json);
		
		if(!property_exists($jsonObject, 'globalID'))
			throw new SocialRecordFormatException('KeyRevocationCertificate: Property globalID missing!');
		if(!property_exists($jsonObject, 'key'))
			throw new SocialRecordFormatException('KeyRevocationCertificate: Property key missing!');
		if(!property_exists($jsonObject, 'datetime'))
			throw new SocialRecordFormatException('KeyRevocationCertificate: Property datetime missing!');
		if(!property_exists($jsonObject, 'reason'))
			throw new SocialRecordFormatException('KeyRevocationCertificate: Property reason missing!');
		
		return (new KeyRevocationCertificateBuilder())
				->globalID($json->globalID)
				->key($json->key)
				->datetime($json->datetime)
				->reason($json->reason)
				->signature($json->signature)
				->build();
	}
	
	public function getGlobalID()
	{
		return $this->globalID;
	}
	
	public function globalID($globalID)
	{
		$this->globalID = $globalID;
		
		return $this;
	}
	
	public function getKey()
	{
		return $this->key;
	}
	
	public function key($key)
	{
		$this->key = $key;
		
		return $this;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function datetime($datetime)
	{
		$this->datetime = $datetime;
		
		return $this;
	}
	
	public function getReason()
	{
		return $this->reason;
	}
	
	public function reason($reason)
	{
		$this->reason = $reason;
		
		return $this;
	}
	
	public function getSignature()
	{
		return $this->signature;
	}
	
	public function signature($signature)
	{
		$this->signature = $signature;
		
		return $this;
	}
	
	public function personalPrivateKey($personalPrivateKey)
	{
		$this->personalPrivateKey = $personalPrivateKey;
		
		return $this;
	}
	
	public function getPersonalPrivateKey()
	{
		return $this->personalPrivateKey;
	}
	
	private function getStringForSignature()
	{
		return $this->globalID
				. $this->key
				. $this->datetime
				. $this->reason;
	}
	
	private function sign($personalPrivateKey)
	{
		$this->signature = Signature::createSignature($this->getStringForSignature(), $personalPrivateKey);
	}
	
	public function verify()
	{
		$personalPublicKey = SocialRecordManager::retrieveSocialRecord($this->globalID)->getPersonalPublicKey();
		
		return Signature::verifySignature($this->getStringForSignature(), $personalPublicKey, $this->signature);
	}
	
	public function build()
	{
		if($this->globalID == NULL)
			throw new \Exception('GlobalID is not set');
		if($this->key == NULL)
			throw new \Exception('Key is not set');
		if($this->datetime == NULL)
			throw new \Exception('Datetime is not set');
		if($this->reason == NULL)
			throw new \Exception('Reason is not set');
		
		if(!GID::isValid($this->globalID))
			throw new \Exception('Invalid value for GlobalID');
		if(!XSDDateTime::isValid($this->datetime))
			throw new \Exception('Invalid value for Datetime');
		
		if($this->signature == NULL)
		{
			if($this->personalPrivateKey = NULL)
				throw new \Exception('Signing key not set');
			
			$this->sign($this->personalPrivateKey);
		}
		
		if(!$this->verify())
			throw new \Exception('Invalid signature for KeyRevocationCertificate!');
		
		return new KeyRevocationCertificate($this);
	}
}