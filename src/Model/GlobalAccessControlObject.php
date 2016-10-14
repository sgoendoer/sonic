<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\BasicObject;

/**
 * Represents a GlobalAccessControl object
 * version 20161014
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class GlobalAccessControlObject extends BasicObject
{
	const JSONLD_CONTEXT		= 'http://sonic-project.net/';
	const JSONLD_TYPE			= 'GlobalAccessControl';
	
	const ACL_DIRECTIVE_DENY	= 'DENY';
	const ACL_DIRECTIVE_ALLOW	= 'ALLOW';
	
	protected $owner			= NULL;
	protected $directive		= NULL;
	protected $accessList		= array();
	
	public function __construct(AccessControlObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->owner = $builder->getOwner();
		$this->directive = $builder->getDirective();
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
	
	public function getDirective()
	{
		return $this->directive;
	}
	
	public function setDirective($directive = GlobalAccessControlObject::ACL_DIRECTIVE_DENY)
	{
		if($directive == GlobalAccessControlObject::ACL_DIRECTIVE_ALLOW)
			$this->directive = GlobalAccessControlObject::ACL_DIRECTIVE_ALLOW;
		else
			$this->directive = GlobalAccessControlObject::ACL_DIRECTIVE_DENY;
		return $this;
	}
	
	public function addAccessList($globalID)
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
				. '"@context":"' . GlobalAccessControlObject::JSONLD_CONTEXT . '",'
				. '"@type":"' . GlobalAccessControlObject::JSONLD_TYPE . '",'
				. '"objectID":"' . $this->objectID . '",'
				. '"owner":"' . $this->owner . '",'
				. '"directive":"' . $this->directive . '",'
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
}