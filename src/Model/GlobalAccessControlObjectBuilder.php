<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Model\IllegalModelStateException;
use sgoendoer\Sonic\Model\BasicObjectBuilder;
use sgoendoer\Sonic\Model\GlobalAccessControlObject;

/**
 * Builder class for a GlobalAccessControl object
 * version 20151014
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class GlobalAccessControlObjectBuilder extends BasicObjectBuilder
{
	protected $owner			= NULL;
	protected $directive		= NULL;
	protected $accessList		= array();
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$builder = (new GlobalAccessControlObjectBuilder())
					->objectID($jsonObject->objectID)
					->owner($jsonObject->owner)
					->directive($jsonObject->directive);
					
		$accessList = array();
		
		foreach($jsonObject->accessList as $gid)
			$accessList[] = $gid;
		
		asort($accessList);
		
		$builder->accessList($accessList);
		
		return $builder->build();
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	public function owner($owner)
	{
		$this->owner = $owner;
		return $this;
	}
	
	public function getDirective()
	{
		return $this->directive;
	}
	
	public function type($directive)
	{
		$this->directive = $directive;
		return $this;
	}
	
	public function accessList($accessList = array())
	{
		$this->accessList = $accessList;
		asort($this->accessList);
		return $this;
	}
	
	public function getAccessList()
	{
		asort($this->accessList);
		return $this->accessList;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		if($this->accessList == NULL)
			$this->accessList = array();
		
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!GID::isValid($this->owner))
			throw new IllegalModelStateException('Invalid owner');
		if(!is_array($this->accessList))
			throw new IllegalModelStateException('Invalid accessList');
		if($this->directive != GlobalAccessControlObject::ACL_DIRECTIVE_DENY && 
			$this->directive != GlobalAccessControlObject::ACL_DIRECTIVE_ALLOW)
			throw new IllegalModelStateException('Invalid directive');
		
		return new GlobalAccessControlObject($this);
	}
}