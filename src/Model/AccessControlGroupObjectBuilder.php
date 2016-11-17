<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Model\IllegalModelStateException;
use sgoendoer\Sonic\Model\ObjectBuilder;
use sgoendoer\Sonic\Model\AccessControlGroupObject;

/**
 * Builder class for a AccessControlGroup object
 * version 20151020
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class AccessControlGroupObjectBuilder extends ObjectBuilder
{
	protected $owner					= NULL;
	protected $name						= NULL;
	protected $members					= array();
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$builder = (new AccessControlGroupObjectBuilder())
				->objectID($jsonObject->objectID)
				->owner($jsonObject->owner)
				->displayName($jsonObject->displayName);
				
		$members = array();
		foreach($jsonObject->members as $member)
			$members[] = $member;
		
		asort($members);
		$builder->members($members);
		
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
	
	public function getDisplayName()
	{
		return $this->displayName;
	}
	
	public function displayName($displayName)
	{
		$this->displayName = $displayName;
		return $this;
	}
	
	public function getMembers()
	{
		return $this->members;
	}
	
	public function members($members)
	{
		$this->members = $members;
		return $this;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!GID::isValid($this->owner))
			throw new IllegalModelStateException('Invalid owner');
		if($this->displayName == NULL)
			throw new IllegalModelStateException('Invalid displayName');
		if(!is_array($this->members))
			throw new IllegalModelStateException('Invalid members');
		
		return new AccessControlGroupObject($this);
	}
}