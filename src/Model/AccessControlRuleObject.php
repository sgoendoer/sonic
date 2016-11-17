<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\Object;

/**
 * Represents a AccessControlRule object
 * version 20161117
 * 
 * syntax: 	The $owner of content (grants|denies) [$directive] (everybody|his friends|a group|an individual) [$scope] 
 * 		identified by the $entityID read access to content identified by $targetID. Rules with a lower $index will
 * 		overwritten by rules with a higher index.
 * 
 * example: 
 * INDEX	DIRECTIVE	ENTITY_TYPE	ENTITY_ID	TARGET_TYPE	TARGET		ACCESS_TYPE
 * 0		DENY		ALL			*			INTERFACE	*			RW			Denies access for everyone
 * 1		ALLOW		FRIENDS		*			INTERFACE	*			RW			Allows access for friends
 * 2		ALLOW		INDIVIDUAL	GlobalID1	CONTENT		ContentID1	*			Further allows access for a specific GlobalID
 * 3		DENY		INDIVIDUAL	GlobalID2	CONTENT		ContentID1	*			Denies access for another specific GlobalID
 * 
 * 			=> Friends and GlobalID1 have access, access for GlobalID2 is blocked - even if this GlobalID2 is a friend
 * 
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class AccessControlRuleObject extends Object
{
	const JSONLD_CONTEXT				= 'http://sonic-project.net/';
	const JSONLD_TYPE					= 'AccessControlRule';
	
	const DIRECTIVE_DENY				= 'DENY';
	const DIRECTIVE_ALLOW				= 'ALLOW';
	
	const ENTITY_TYPE_ALL				= 'ALL';
	const ENTITY_TYPE_FRIENDS			= 'FRIENDS';
	const ENTITY_TYPE_FRIEND_OF_FRIENDS	= 'FOF'; // unused as of now
	const ENTITY_TYPE_GROUP				= 'GROUP';
	const ENTITY_TYPE_INDIVIDUAL		= 'INDIVIDUAL';
	
	const TARGET_TYPE_INTERFACE			= 'INTERFACE';
	const TARGET_TYPE_CONTENT			= 'CONTENT';
	
	const ACCESS_TYPE_READ				= 'R';
	const ACCESS_TYPE_WRITE				= 'W';
	
	const WILDCARD						= '*';
	
	protected $owner					= NULL;
	protected $index					= 0;
	protected $directive				= NULL;
	protected $entityType				= NULL;
	protected $entityID					= NULL;
	protected $targetType				= NULL;
	protected $target					= NULL;
	protected $accessType				= NULL;
	
	public function __construct(AccessControlRuleObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->owner = $builder->getOwner();
		$this->index = $builder->getIndex();
		$this->directive = $builder->getDirective();
		$this->entityType = $builder->getEntityType();
		$this->entityID = $builder->getEntityID();
		$this->targetType = $builder->getTargetType();
		$this->target = $builder->getTarget();
		$this->accessType = $builder->getAccessType();
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
	
	public function getIndex()
	{
		return $this->index;
	}
	
	public function setIndex($index)
	{
		$this->index = $index;
		return $this;
	}
	
	public function getDirective()
	{
		return $this->directive;
	}
	
	public function setDirective($directive)
	{
		$this->directive = $directive;
		return $this;
	}
	
	public function getEntityType()
	{
		return $this->entityType;
	}
	
	public function setEntityType($entityType)
	{
		$this->entityType = $entityType;
		return $this;
	}
	
	public function setEntityID($entityID)
	{
		$this->entityID = $entityID;
		return $this;
	}
	
	public function getEntityID()
	{
		return $this->entityID;
	}
	
	public function getTargetType()
	{
		return $this->targetType;
	}
	
	public function setTargetType($targetType)
	{
		$this->targetType = $targetType;
		return $this;
	}
	
	public function getTarget()
	{
		return $this->target;
	}
	
	public function setTarget($target)
	{
		$this->target = $target;
		return $this;
	}
	
	public function getAccessType()
	{
		return $this->accessType;
	}
	
	public function setAccessType($accessType)
	{
		$this->accessType = $accessType;
		return $this;
	}
	
	public function getJSONString()
	{
		$json =  '{'
				. '"@context":"'	. AccessControlRuleObject::JSONLD_CONTEXT . '",'
				. '"@type":"'		. AccessControlRuleObject::JSONLD_TYPE . '",'
				. '"objectID":"'	. $this->objectID . '",'
				. '"owner":"'		. $this->owner . '",'
				. '"index":'		. $this->index . ','
				. '"directive":"'	. $this->directive . '",'
				. '"entityType":"'	. $this->entityType . '",'
				. '"entityID":"'	. $this->entityID . '",'
				. '"targetType":"'	. $this->targetType . '",'
				. '"target":"'		. $this->target . '",'
				. '"accessType":"'	. $this->accessType . '"}';
		
		return $json;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/accessControlRule",
		"type": "object",
		"properties":
		{
			"objectID": {"type": "string"},
			"owner": {"type": "string"},
			"index": {"type": "integer"},
			"directive": {"type": "string"},
			"entityType": {"type": "string"},
			"entityID": {"type": "string"},
			"targetType":	{"type": "string"},
			"target":	{"type": "string"},
			"accessType":	{"type": "string"}
		},
		"required": [
			"objectID",
			"owner",
			"index",
			"directive",
			"entityType",
			"entityID",
			"targetType",
			"target",
			"accessType"
		]
	}';
}

?>