<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\SignatureObject;
use sgoendoer\Sonic\Model\LinkObjectBuilder;
use sgoendoer\Sonic\Model\LinkObject;
use sgoendoer\Sonic\Model\RemoteObjectBuilder;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\IllegalModelStateException;


/**
 * Builder class for a LINK object
 * version 20151020
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class LinkObjectBuilder extends RemoteObjectBuilder
{
	protected $owner = NULL;
	protected $link = NULL;
	protected $datetime = NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$signature = SignatureObject::createFromJSON(json_encode($jsonObject->signature));
		
		return (new LinkObjectBuilder())
				->objectID($jsonObject->objectID)
				->owner($jsonObject->owner)
				->link($jsonObject->link)
				->datetime($jsonObject->datetime)
				->signature($signature)
				->build();
	}
	
	public function owner($owner)
	{
		$this->owner = $owner;
		return $this;
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	public function link($link)
	{
		$this->link = $link;
		return $this;
	}
	
	public function getLink()
	{
		return $this->link;
	}
	
	public function datetime($datetime = NULL)
	{
		$this->datetime = $datetime;
		return $this;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		if($this->datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
			
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!GID::isValid($this->owner))
			throw new IllegalModelStateException('Invalid owner');
		if(!GID::isValid($this->link))
			throw new IllegalModelStateException('Invalid link');
		if(!XSDDateTime::validateXSDDateTime($this->datetime))
			throw new IllegalModelStateException('Invalid datetime');
		
		$link = new LinkObject($this);
		
		if($link->getSignature() == NULL)
			$link->signObject();
		
		if(!$link->verifyObjectSignature())
			throw new IllegalModelStateException('Invalid signature');
		
		return $link;
	}
}

?>