<?php namespace sgoendoer\Sonic\Identity;

use sgoendoer\Sonic\Crypt\Signature;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Identity\KeyRevocationCertificateBuilder;

use sgoendoer\json\JSONObject;

/**
 * KeyRevocationCertificate class
 * version 20160105
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class KeyRevocationCertificate
{
	const JSONLD_CONTEXT		= 'http://sonic-project.net/';
	const JSONLD_TYPE			= 'keyrevocationcertificate';
	
	private $key				= NULL;	// the revoked public key
	private $datetime			= NULL;	// XSD datetime format e.g. 2015-01-01T11:11:11Z
	private $reason				= NULL;
	private $signature			= NULL;
	
	public function __construct(KeyRevocationCertificateBuilder $builder)
	{
		$this->key			= $builder->getKey();
		$this->datetime		= $builder->getDatetime();
		$this->reason		= $builder->getReason();
		$this->signature	= $builder->getSignature();
	}
	
	public function __toString()
	{
		return $this->getJSONString();
	}
	
	/**
	 * @deprecated
	 */
	public function getJSON()
	{
		return $this->getJSONString();
	}
	
	public function getJSONString()
	{
		return '{'
				. '"@context": "' .	KeyRevocationCertificate::JSONLD_CONTEXT . '",'
				. '"@type": "' .	KeyRevocationCertificate::JSONLD_TYPE . '",'
				. '"key": "' .		$this->key . '",'
				. '"datetime": "' . $this->datetime . '",'
				. '"reason": ' .	$this->reason . ','
				. '"signature": ' . $this->signature->getJSONString() . ''
				. '}';
	}
	
	public function getJSONObject()
	{
		return json_decode($this->getJSONString());
	}
	
	public function getGlobalID()
	{
		return $this->globalID;
	}
	
	// public function setGlobalID($globalID)
	// {
	// 	$this->globalID = $globalID;
	// }
	
	public function getKey()
	{
		return $this->key;
	}
	
	// public function setKey($key)
	// {
	// 	$this_>key = $key;
	// }
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	// public function setDatetime($datetime)
	// {
	// 	$this->datetime = $datetime;
	// }
	
	public function getReason()
	{
		return $this->reason;
	}
	
	// public function setReason($reason)
	// {
	// 	$this->reason = $reason;
	// }
	
	public function getSignature()
	{
		return $this->signature;
	}
	
	// public function setSignature($signature)
	// {
	// 	$this->signature = $signature;
	// }
	
	private function getStringForSignature()
	{
		return $this->globalID
				. $this->key
				. $this->datetime
				. $this->reason;
	}
	
	public function verify()
	{
		$personalPublicKey = SocialRecordManager::retrieveSocialRecord($this->globalID)->getPersonalPublicKey();
		
		return Signature::verifySignature($this->getStringForSignature(), $personalPublicKey, $this->signature);
	}
}