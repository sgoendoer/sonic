<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\LocalGroupObjectBuilder;

/**
 * Represents a group object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class LocalGroupObject extends BasicObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'localgroup';
	
	protected $owner			= NULL;
	protected $accessList		= array();
	
	public function __construct(LocalGroupObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->owner = $builder->getOwner();
		$this->memberList = $builder->getMemberList();
		asort($this->memberList);
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
	
	public function addToMemberList($globalID)
	{
		$this->memberList[] = $globalID;
		asort($this->memberList);
		return $this;
	}
	
	public function setMemberList($memberList)
	{
		$this->memberList = $memberList;
		asort($this->memberList);
		return $this;
	}
	
	public function getMemberList()
	{
		return $this->memberList;
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