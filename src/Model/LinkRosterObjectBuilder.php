<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\LinkRosterObject;
use sgoendoer\Sonic\Model\LinkObjectBuilder;
use sgoendoer\Sonic\Model\ObjectBuilder;
use sgoendoer\Sonic\Model\IllegalModelStateException;

/**
 * Builder class for a LINK ROSTER object
 * version 20151020
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class LinkRosterObjectBuilder extends ObjectBuilder
{
	protected $owner = NULL;
	protected $roster = array();
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$builder = (new LinkRosterObjectBuilder())
				->objectID($jsonObject->objectID)
				->owner($jsonObject->owner);
		
		foreach($jsonObject->roster as $link)
		{
			$builder->link(LinkObjectBuilder::buildFromJSON(json_encode($link)));
		}
		
		return $builder->build();
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
		$this->roster[] = $link;
		// TODO manually implement array_unique
		//$this->roster = array_unique($this->roster); // removing duplicates
		return $this;
	}
	
	public function roster($linkArray)
	{
		$this->roster = $linkArray;
		return $this;
	}
	
	public function getRoster()
	{
		return $this->roster;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
			
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!GID::isValid($this->owner))
			throw new IllegalModelStateException('Invalid owner');
			
		return new LinkRosterObject($this);
	}
}

?>