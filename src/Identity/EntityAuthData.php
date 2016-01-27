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
	private $jwt				= NULL;
	
	/**
	 * Initializes EntityAuthData with the auth data of a user or a platform. While the parameters $socialRecord and
	 * $accountKeyPair are mandatory, the $personalKeyPair is optional and may be omitted.
	 * 
	 * @param $socialRecord SocialRecord The SocialRecord of the entity
	 * @param $accountKeyPair KeyPair The accountKeyPair for the entity
	 * @param $personalKeyPair KeyPair The personalKeyPair for the entity. OPTIONAL!
	 */
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
	
	/**
	 * Creates  a signed JWT to be pushed to the GSLS. If the personalKeyPair is not set, an exception is thrown
	 * 
	 * @return String The JWT
	 */
	public function getJWT()
	{
		if($this->personalKeyPair === NULL)
			throw new \Exception("JWT cannot be built without the personalKey");
		
		// create and sign JWT
		$signer = new Sha512();
		
		$personalPrivateKey = PrivateKey::formatPEM($this->personalKeyPair->getPrivateKey());
		
		$token = (new Builder())
			->set('socialRecord', base64_encode($this->socialRecord->getJSONString()))
			->sign($signer, $personalPrivateKey)
			->getToken();
		
		return $token->__toString();
	}
}

?>