<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\ReferencingObject;

/**
 * Represents a ContentAccessControlRule object
 * version 20161018
 * 
 * syntax: 	The $owner of content (grants|denies) [$directive] (everybody|his friends|a group|an individual) [$scope] 
 * 		identified by the $entityID read access to content identified by $targetID. Rules with a lower $index will
 * 		overwritten by rules with a higher index.
 * 
 * example: INDEX	DIRECTIVE	SCOPE		ENTITYID	TARGETID
 * 			0		DENY		ALL						ContentID1		Denies access for everyone
 * 			1		ALLOW		FRIENDS					ContentID1		Allows access for friends
 * 			2		ALLOW		INDIVIDUAL	GlobalID1	ContentID1		Further allows access for a specific GlobalID
 * 			3		DENY		INDIVIDUAL	GlobalID2	ContentID1		Denies access for another specific GlobalID
 * 
 * 			=> Friends and GlobalID1 have access, access for GlobalID2 is blocked - even if this GlobalID2 is a friend
 * 
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class ContentAccessControlRuleObject extends ReferencingObject
{
	const JSONLD_CONTEXT			= 'http://sonic-project.net/';
	const JSONLD_TYPE				= 'ContentAccessControlRule';
	
	const ACL_DIRECTIVE_DENY		= 'DENY';
	const ACL_DIRECTIVE_ALLOW		= 'ALLOW';
	
	const ACL_SCOPE_ALL				= 'ALL';
	const ACL_SCOPE_FRIENDS			= 'FRIENDS';
	const ACL_SCOPE_GROUP			= 'GROUP';
	const ACL_SCOPE_INDIVIDUAL		= 'INDIVIDUAL';
	
	protected $owner				= NULL;
	protected $index				= 0;
	protected $directive			= NULL;
	protected $scope				= NULL;
	protected $entityID				= NULL;
	
	public function __construct(ContentAccessControlRuleObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID(), $builder->getTargetID());
		
		$this->owner = $builder->getOwner();
		$this->priority = $builder->getPriority();
		$this->directive = $builder->getDirective();
		$this->scope = $builder->getScope();
		$this->entityID = $builder->entityID();
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
	
	public function getScope()
	{
		return $this->scope;
	}
	
	public function setScope($scope)
	{
		$this->scope = $scope;
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
	
	public function setEntityID($entityID)
	{
		$this->entityID = $entityID;
		return $this;
	}
	
	public function getEntityID()
	{
		return $this->entityID;
	}
	
	public function getJSONString()
	{
		$json =  '{'
				. '"@context":"'	. ContentAccessControlRuleObject::JSONLD_CONTEXT . '",'
				. '"@type":"'		. ContentAccessControlRuleObject::JSONLD_TYPE . '",'
				. '"objectID":"'	. $this->objectID . '",'
				. '"targetID":"'	. $this->targetID . '",'
				. '"owner":"'		. $this->owner . '",'
				. '"index":"'		. $this->index . '",'
				. '"scope":"'		. $this->scope . '",'
				. '"directive":"'	. $this->directive . '",'
				. '"entityID":"'	. $this->entityID . '"}';
		
		return $json;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/contentAccessControlRule",
		"type": "object",
		"properties":
		{
			"objectID": {"type": "string"},
			"targetID":	{"type": "string"},
			"owner": {"type": "string"},
			"index": {"type": "int"},
			"scope": {"type": "string"},
			"directive": {"type": "string"},
			"entityID": {"type": "string"}
		},
		"required": [
			"objectID",
			"targetID",
			"owner",
			"index",
			"scope",
			"directive",
			"entityID"
		]
	}';'
}