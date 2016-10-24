<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\SignatureObject;
use sgoendoer\Sonic\Model\ConversationStatusObject;
use sgoendoer\Sonic\Model\ReferencingRemoteObjectBuilder;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\IllegalModelStateException;

/**
 * Builder class for a CONVERSATION STATUS object
 * version 20151022
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ConversationStatusObjectBuilder extends ReferencingRemoteObjectBuilder
{
	protected $author = NULL;
	protected $datetime = NULL;
	protected $status = NULL;
	protected $targetGID = NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$signature = SignatureObject::createFromJSON(json_encode($jsonObject->signature));
		
		return (new ConversationStatusObjectBuilder())
				->objectID($jsonObject->objectID)
				->targetID($jsonObject->targetID)
				->author($jsonObject->author)
				->status($jsonObject->status)
				->datetime($jsonObject->datetime)
				->targetGID($jsonObject->targetGID)
				->signature($signature)
				->build();
	}
	
	public function getAuthor()
	{
		return $this->author;
	}
	
	public function author($author)
	{
		$this->author = $author;
		return $this;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function status($status)
	{
		$this->status = $status;
		return $this;
	}
	
	public function getTargetGID()
	{
		$this->targetGID;
	}
	
	public function targetGID($targetGID)
	{
		$this->targetGID = $targetGID;
		return $this;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function datetime($datetime = NULL)
	{
		if($datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
		else
			$this->datetime = $datetime;
		return $this;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		if($this->datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
			
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!UOID::isValid($this->targetID))
			throw new IllegalModelStateException('Invalid targetID');
		if(!GID::isValid($this->author))
			throw new IllegalModelStateException('Invalid author');
		if($this->targetGID != "" && !GID::isValid($this->targetGID))
			throw new IllegalModelStateException('Invalid targetGID: ' . $this->targetGID);
		if($this->status == '' || $this->status == NULL)
			throw new IllegalModelStateException('Invalid status: ' . $this->status);
		if(!XSDDateTime::validateXSDDateTime($this->datetime))
			throw new IllegalModelStateException('Invalid datetime');
			
		$conversationStatus = new ConversationStatusObject($this);
		
		if($conversationStatus->getSignature() == NULL)
			$conversationStatus->signObject();
		
		if(!$conversationStatus->verifyObjectSignature())
			throw new IllegalModelStateException('Invalid signature');
		
		return $conversationStatus;
	}
}

?>