<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\LocalGroupObjectBuilder;

/**
 * Represents a AccessControlGroup object
 * version 20161018
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class AccessControlGroupObject extends BasicObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'accessControlGroup';
	
	protected $owner			= NULL;
	protected $members			= array();
	
	public function __construct(AccessControlGroupObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->owner = $builder->getOwner();
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
				. '"@context":"' . APIAccessControlObject::JSONLD_CONTEXT . '",'
				. '"@type":"' . APIAccessControlObject::JSONLD_TYPE . '",'
				. '"objectID":"' . $this->objectID . '",'
				. '"owner":"' . $this->owner . '",'
				. '"resource":"' . $this->resource . '",'
				. '"directive":"' . $this->directive . '",'
				. '"scope":"' . $this->scope . '",'
				. '"accessList":[';
				
		asort($this->accessList);
		
		foreach($this->accessList as $gid)
		{
			$json .= '"' . $gid . '"';
			if($gid !== end($this->accessList)) $json .= ',';
		}
		
		$json .= ']}';
		
		return $json;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/comment",
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/comment/objectID",
				"type": "string"
			},
			"targetID":
			{
				"id": "http://jsonschema.net/sonic/comment/targetID",
				"type": "string"
			},
			"author":
			{
				"id": "http://jsonschema.net/sonic/comment/author",
				"type": "string"
			},
			"comment":
			{
				"id": "http://jsonschema.net/sonic/comment/comment",
				"type": "string"
			},
			"datePublished":
			{
				"id": "http://jsonschema.net/sonic/comment/datePublished,
				"type": "string"
			},
			"dateUpdated":
			{
				"id": "http://jsonschema.net/sonic/comment/dateUpdated,
				"type": "string"
			},
			"signature":
			{
				"id": "http://jsonschema.net/sonic/comment/signature",
				"type": "object"
			}
		},
		"required": [
			"objectID",
			"targetID",
			"author",
			"comment",
			"datePublished",
			"signature"
		]
	}';
}

?>