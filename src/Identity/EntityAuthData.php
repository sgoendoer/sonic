<?php namespace sgoendoer\Sonic\Identity;

use sgoendoer\Sonic\Crypt\KeyPair;
use sgoendoer\Sonic\Identity\SocialRecord;

use sgoendoer\json\JSONObject;

/**
 * EntityAuthData container class
 * version 20160104
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class EntityAuthData
{
	private $globalID			= NULL;	// global id
	private $socialRecord		= NULL;	// socialRecord
	private $personalKeyPair	= NULL; // 
	private $accountKeyPair		= NULL; // 
	
	public function __construct(SocialRecord $socialRecord,
								KeyPair $accountKeyPair,
								KeyPair $personalKeyPair = NULL)
	{
		$this->globalID			= $socialRecord->getGlobalID();
		$this->socialRecord		= $socialRecord;
		$this->personalKeyPair	= $personalKeyPair;
		$this->accountKeyPair	= $accountKeyPair;
	}
	
	public function getGlobalID()
	{
		return $this->globalID;
	}
	
	public function getSocialRecord()
	{
		return $this->socialRecord;
	}
	
	public function setSocialRecord(SocialRecord $socialRecord)
	{
		$this->socialRecord = $socialRecord;
	}
	
	public function getPersonalKeyPair()
	{
		return $this->personalKeyPair;
	}
	
	public function setPersonalKeyPair(KeyPair $personalKeyPair)
	{
		$this->personalKeyPair = $personalKeyPair;
	}
	
	public function getAccountKeyPair()
	{
		return $this->accountKeyPair;
	}
	
	public function setAccountKeyPair(KeyPair $accountKeyPair)
	{
		$this->accountKeyPair = $accountKeyPair;
	}
	
	// TODO provide functionality to store and create JWT
}

?>