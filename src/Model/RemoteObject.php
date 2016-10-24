<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Crypt\Random;
use sgoendoer\Sonic\Crypt\Signature;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Model\Object;
use sgoendoer\Sonic\Model\SignatureObject;

/**
 * Represents a remote sonic object
 * version 20150905
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
abstract class RemoteObject extends Object
{
	protected $signature = NULL;
	
	public function __construct($objectID)
	{
		parent::__construct($objectID);
	}
	
	public function signObject()
	{
		$this->signature = new SignatureObject($this->objectID, Sonic::getContextGlobalID());
		
		$this->signature->setTimeSigned(XSDDateTime::getXSDDateTime());
		$this->signature->setRandom(Random::getRandom());
		
		$sigmessage = $this->getStringForSignature() . $this->signature->getTargetID() . $this->signature->getCreatorGID() . $this->signature->getTimeSigned() . $this->signature->getRandom();
		//echo "\ncreating sig for \n".$sigmessage."\n";
		$this->signature->setSignature(Signature::createSignature($sigmessage, Sonic::getContextAccountKeyPair()->getPrivateKey()));
	}
	
	public function verifyObjectSignature()
	{
		// TODO catch fatal error when SocialRecord cannot be found/built
		$publicKey = SocialRecordManager::retrieveSocialRecord($this->signature->getCreatorGID())->getAccountPublicKey();
		
		$sigmessage = $this->getStringForSignature() . $this->signature->getTargetID() . $this->signature->getCreatorGID() . $this->signature->getTimeSigned() . $this->signature->getRandom();
		//echo "\nchecking sig for \n".$sigmessage."\n";
		return Signature::verifySignature($sigmessage, $publicKey, $this->signature->getSignature());
	}
	
	public function invalidate()
	{
		$this->signObject();
	}
	
	protected abstract function getStringForSignature();
	
	public function getSignature()
	{
		return $this->signature;
	}
	
	public function setSignature(SignatureObject $signature = NULL)
	{
		if($signature != NULL)
		{
			$this->signature = $signature;
		}
		else
		{
			$this->signObject();
		}
		
		$this->verifyObjectSignature(); // TODO check whether this may cause problems
		
		return $this;
	}
}

?>