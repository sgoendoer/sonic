<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Model\IllegalModelStateException;
use sgoendoer\Sonic\Model\ReferencingObjectBuilder;
use sgoendoer\Sonic\Model\ContentAccessControlObject;

/**
 * Builder class for a ContentAccessControl object
 * version 20151014
 *
 * author: Senan Sharhan, Sebastian Goendoer
 * copyright: Senan Sharhan <senan.sharhan@campus.tu-berlin.de>, Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class ContentAccessControlObjectBuilder extends ReferencingObjectBuilder
{
	protected $owner		= NULL;
	protected $type			= NULL;
	protected $allow		= array();
	protected $deny			= array();
	protected $datetime		= NULL;
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$builder = (new ContentAccessControlObjectBuilder())
					->objectID($jsonObject->objectID)
					->targetID($jsonObject->targetID)
					->owner($jsonObject->owner)
					->type($jsonObject->type)
					->allow($jsonObject->allow)
					->deny($jsonObject->deny)
					->datetime($jsonObject->datetime);
					
		$allowArray = array();
		
		foreach($jsonObject->allow as $member)
			$allowArray[] = $member;
		
		if(is_array($allowArray))
			asort($allowArray);
		
		$builder->allow($allowArray);
		
		$denyArray = array();
		foreach($jsonObject->deny as $member)
			$denyArray[] = $member;
		
		if(is_array($denyArray))
			asort($denyArray);
		
		$builder->deny($denyArray);
		
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
	
	public function getType()
	{
		return $this->type;
	}
	
	public function type($type)
	{
		$this->type = $type;
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
	
	public function allow($memberArray = array())
	{
		$this->allow = $memberArray;
		asort($this->allow);
		return $this;
	}
	
	public function getAllow()
	{
		asort($this->allow);
		return $this->allow;
	}
	
	public function deny($memberArray = array())
	{
		$this->deny = $memberArray;
		asort($this->deny);
		return $this;
	}
	
	public function getDeny()
	{
		asort($this->deny);
		return $this->deny;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		if($this->allow == NULL)
			$this->allow = array();
		if($this->deny == NULL)
			$this->deny = array();
		if($this->datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
		
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!UOID::isValid($this->targetID))
			throw new IllegalModelStateException('Invalid targetID');
		if(!GID::isValid($this->owner))
			throw new IllegalModelStateException('Invalid owner');
		if(!XSDDateTime::validateXSDDateTime($this->datetime))
			throw new IllegalModelStateException('Invalid datetime');
		if(!is_array($this->allow))
			throw new IllegalModelStateException('Invalid allow');
		if(!is_array($this->deny))
			throw new IllegalModelStateException('Invalid deny');
		
		return new AccessControlObject($this);
	}
}