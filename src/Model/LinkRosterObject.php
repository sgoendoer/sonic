<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\Object;
use sgoendoer\Sonic\Model\LinkRosterObjectBuilder;
use sgoendoer\Sonic\Model\LinkObject;
use sgoendoer\Sonic\Model\IAccessRestrictableObject;

/**
 * Represents a LINK-ROSTER object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class LinkRosterObject extends Object implements IAccessRestrictableObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'link-roster';
	
	protected $owner = NULL;
	protected $roster = array();
	
	public function __construct(LinkRosterObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->setOwner($builder->getOwner());
		$this->addLinkArray($builder->getRoster());
	}
	
	public function setOwner($owner)
	{
		$this->owner = $owner;
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	public function addLinkArray($linkArray)
	{
		$this->roster = array_merge($this->roster, $linkArray);
		// TODO manually implement array_unique
		//$this->roster = array_unique($this->roster); // removing duplicates
	}
	
	public function addLink(LinkObject $link)
	{
		$this->roster[] = $link;
		// TODO manually implement array_unique
		//$this->roster = array_unique($this->roster); // removing duplicates
	}
	
	public function getRoster()
	{
		return $this->roster;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context":"' . LinkRosterObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . LinkRosterObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",' 
			. '"owner":"' . $this->owner . '",' 
			. '"roster":[';
		
		foreach($this->roster as $link)
		{
			$json .= $link->getJSONString();
			if($link !== end($this->roster)) $json .= ',';
		}
		
		$json .= ']}';
		
		return $json;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/linkroster",
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/linkroster/objectID",
				"type": "string"
			},
			"owner":
			{
				"id": "http://jsonschema.net/sonic/linkroster/ownerID",
				"type": "string"
			},
			"roster":
			{
				"id": "http://jsonschema.net/sonic/linkroster/displayName",
				"type": "array"
			}
		},
		"required": [
			"objectID",
			"owner",
			"roster"
		]
	}';
}

?>