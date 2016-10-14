<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\BasicObject;

/**
 * Represents a APIAccessControl object
 * Default setting is [*] ALLOW ALL
 * version 20161014
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class APIAccessControlObject extends BasicObject
{
	const JSONLD_CONTEXT		= 'http://sonic-project.net/';
	const JSONLD_TYPE			= 'APIAccessControl';
	
	const ACL_DIRECTIVE_DENY	= 'DENY';
	const ACL_DIRECTIVE_ALLOW	= 'ALLOW';
	
	const ACL_SCOPE_ALL			= 'ALL';
	const ACL_SCOPE_FRIENDS		= 'FRIENDS';
	const ACL_SCOPE_INDIVIDUAL	= 'INDIVIDUAL';
	
	protected $owner			= NULL;
	protected $resource			= NULL;
	protected $directive		= NULL;
	protected $accessList		= array();
	
	public function __construct(APIAccessControlObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->owner = $builder->getOwner();
		$this->resource = $builder->getResource();
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
	
	public function getResource()
	{
		return $this->resource;
	}
	
	public function setResource($resource)
	{
		$this->resource = $resource;
		return $this;
	}
	
	public function getDirective()
	{
		return $this->directive;
	}
	
	public function setDirective($directive = APIAccessControlObject::ACL_DIRECTIVE_ALLOW)
	{
		if($directive == APIAccessControlObject::ACL_DIRECTIVE_DENY)
			$this->directive = APIAccessControlObject::ACL_DIRECTIVE_DENY;
		else
			$this->directive = APIAccessControlObject::ACL_DIRECTIVE_ALLOW;
		return $this;
	}
	
	public function getScope()
	{
		return $this->scope;
	}
	
	public function setScope($scope = APIAccessControlObject::ACL_SCOPE_ALL)
	{
		if($scope == APIAccessControlObject::ACL_SCOPE_INDIVIDUAL || $scope == APIAccessControlObject::ACL_SCOPE_FRIENDS)
			$this->scope = $scope;
		else
			$this->scope = APIAccessControlObject::ACL_SCOPE_ALL;
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
}