<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\SignatureObject;
use sgoendoer\Sonic\Model\LinkRequestObject;
use sgoendoer\Sonic\Model\LinkObjectBuilder;
use sgoendoer\Sonic\Model\LinkObject;
use sgoendoer\Sonic\Model\ObjectBuilder;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\IllegalModelStateException;

/**
 * Builder class for a LINK REQUEST object
 * version 20151020
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class LinkRequestObjectBuilder extends ObjectBuilder
{
	protected $initiatingGID = NULL;
	protected $targetedGID = NULL;
	protected $datetime = NULL;
	protected $message = NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		return (new LinkRequestObjectBuilder())
				->objectID($jsonObject->objectID)
				->initiatingGID($jsonObject->initiatingGID)
				->targetedGID($jsonObject->targetedGID)
				->datetime($jsonObject->datetime)
				->message($jsonObject->message)
				->build();
	}
	
	public function initiatingGID($initiatingGID)
	{
		$this->initiatingGID = $initiatingGID;
		return $this;
	}
	
	public function getInitiatingGID()
	{
		return $this->initiatingGID;
	}
	
	public function targetedGID($targetedGID)
	{
		$this->targetedGID = $targetedGID;
		return $this;
	}
	
	public function getTargetedGID()
	{
		return $this->targetedGID;
	}
	
	public function datetime($datetime = NULL)
	{
		if($datetime == NULL) 
			$this->datetime = XSDDateTime::getXSDDateTime();
		else
			$this->datetime = $datetime;
		return $this;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function message($message = NULL)
	{
		$this->message = $message;
		return $this;
	}
	
	public function getMessage()
	{
		return $this->message;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		if($this->datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
		
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!GID::isValid($this->initiatingGID))
			throw new IllegalModelStateException('Invalid initiatingGID');
		if(!GID::isValid($this->targetedGID))
			throw new IllegalModelStateException('Invalid targetedGID');
		if(!XSDDateTime::validateXSDDateTime($this->datetime))
			throw new IllegalModelStateException('Invalid datetime');
		
		return new LinkRequestObject($this);
	}
}

?>