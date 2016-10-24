<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\SignatureObject;
use sgoendoer\Sonic\Model\ConversationObject;
use sgoendoer\Sonic\Model\ConversationStatusObjectBuilder;
use sgoendoer\Sonic\Model\ConversationMessageObjectBuilder;
use sgoendoer\Sonic\Model\RemoteObjectBuilder;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\IllegalModelStateException;

/**
 * Builder class for a CONVERSATION object
 * version 20151021
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ConversationObjectBuilder extends RemoteObjectBuilder
{
	protected $members = array();
	protected $title = NULL;
	protected $owner = NULL;
	protected $datetime = NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$signature = SignatureObject::createFromJSON(json_encode($jsonObject->signature));
		
		$builder = (new ConversationObjectBuilder())
				->objectID($jsonObject->objectID)
				->members($jsonObject->members)
				->datetime($jsonObject->datetime)
				->owner($jsonObject->owner)
				->signature($signature);
		
		$memberArray = array();
		foreach($jsonObject->members as $member)
		{
			$memberArray[] = $member;
		}
		
		if(is_array($memberArray))
		{
			asort($memberArray);
		}
		$builder->members($memberArray);
		
		if(property_exists($jsonObject, 'title')) $builder->title($jsonObject->title);
		
		return $builder->build();
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	public function owner($owner)
	{
		$this->owner = $owner;
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
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function title($title)
	{
		$this->title = $title;
		return $this;
	}
	
	public function members($memberArray = array())
	{
		$this->members = $memberArray;
		asort($this->members);
		return $this;
	}
	
	public function getMembers()
	{
		asort($this->members);
		return $this->members;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		if($this->members == NULL)
			$this->members = array();
		if($this->datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
		
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!GID::isValid($this->owner))
			throw new IllegalModelStateException('Invalid owner');
		if(!XSDDateTime::validateXSDDateTime($this->datetime))
			throw new IllegalModelStateException('Invalid datetime');
		if(!is_array($this->members))
			throw new IllegalModelStateException('Invalid members');
		
		$conversation = new ConversationObject($this);
		
		if($this->signature == NULL)
			$conversation->signObject();
		
		if(!$conversation->verifyObjectSignature())
			throw new IllegalModelStateException('Invalid signature');
		
		return $conversation;
	}
}

?>