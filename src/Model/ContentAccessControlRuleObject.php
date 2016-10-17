<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\ReferencingObject;

/**
 * Represents a ContentAccessControlRule object
 * version 20161017
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
	
	const ACL_SCOPE_FRIENDS			= 'FRIENDS';
	const ACL_SCOPE_INDIVIDUAL		= 'INDIVIDUAL';
	const ACL_SCOPE_GROUP			= 'GROUP';
	
	protected $owner				= NULL;
	protected $index				= 0;
	protected $directive			= NULL;
	protected $scope				= NULL;
	protected $accessList			= array();
	
	public function __construct(ContentAccessControlRuleObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID(), $builder->getTargetID());
		
		$this->owner = $builder->getOwner();
		$this->priority = $builder->getPriority();
		$this->directive = $builder->getDirective();
		$this->scope = $builder->getScope();
		$this->accessList = $builder->getAccessList();
		asort($this->accessList);
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
	
	public function addToAccessList($globalID)
	{
		$this->accessList[] = $globalID;
		asort($this->accessList);
		return $this;
	}
	
	public function setAccessList($accessList)
	{
		$this->accessList = $accessList;
		asort($this->accessList);
		return $this;
	}
	
	public function getAccessList()
	{
		return $this->accessList;
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
				. '"allow":[';
				
		asort($this->accessList);
		
		foreach($this->accessList as $member)
		{
			$json .= '"' . $member . '"';
			if($member !== end($this->accessList)) $json .= ',';
		}
		
		$json .= ']}';
		
		return $json;
	}
	
	// TODO add json schema
	const SCHEMA = '{}';'
}