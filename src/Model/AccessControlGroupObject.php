<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\AccessControlGroupObjectBuilder;

/**
 * Represents a AccessControlGroup object
 * version 20161020
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class AccessControlGroupObject extends Object
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'accessControlGroup';
	
	protected $owner			= NULL;
	protected $displayName		= NULL;
	protected $members			= array();
	
	public function __construct(AccessControlGroupObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->owner = $builder->getOwner();
		$this->displayName = $builder->getDisplayName();
		$this->members = $builder->getMembers();
		asort($this->members);
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	public function setOwner($owner)
	{
		$this->owner = $owner;
		return $this;
	}
	
	public function getDisplayName()
	{
		return $this->displayName;
	}
	
	public function setDisplayName($displayName)
	{
		$this->displayName = $displayName;
		return $this;
	}
	
	public function addToMembers($globalID)
	{
		$this->members[] = $globalID;
		asort($this->members);
		return $this;
	}
	
	public function setMembers($members)
	{
		$this->members = $members;
		asort($this->members);
		return $this;
	}
	
	public function getMembers()
	{
		return $this->members;
	}
	
	public function getJSONString()
	{
		$json =  '{'
				. '"@context":"' . AccessControlGroupObject::JSONLD_CONTEXT . '",'
				. '"@type":"' . AccessControlGroupObject::JSONLD_TYPE . '",'
				. '"objectID":"' . $this->objectID . '",'
				. '"owner":"' . $this->owner . '",'
				. '"displayName":"' . $this->displayName . '",'
				. '"members":[';
				
		asort($this->members);
		
		foreach($this->members as $gid)
		{
			$json .= '"' . $gid . '"';
			if($gid !== end($this->members)) $json .= ',';
		}
		
		$json .= ']}';
		
		return $json;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/accessControlGroup",
		"type": "object",
		"properties":
		{
			"objectID": {"type": "string"},
			"owner": {"type": "string"},
			"displayName": {"type": "string"},
			"members": {"type": "array"}
		},
		"required": [
			"objectID",
			"owner",
			"displayName",
			"members"
		]
	}';
}

?>