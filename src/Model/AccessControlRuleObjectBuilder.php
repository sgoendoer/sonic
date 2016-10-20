<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Model\IllegalModelStateException;
use sgoendoer\Sonic\Model\ObjectBuilder;
use sgoendoer\Sonic\Model\AccessControlRuleObject;

/**
 * Builder class for a AccessControlRule object
 * version 20151020
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class AccessControlRuleObjectBuilder extends ObjectBuilder
{
	protected $owner					= NULL;
	protected $index					= 0;
	protected $directive				= NULL;
	protected $entity					= NULL;
	protected $entityID					= NULL;
	protected $targetType				= NULL;
	protected $target					= NULL;
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		return (new AccessControlRuleObjectBuilder())
				->objectID($jsonObject->objectID)
				->owner($jsonObject->owner)
				->index($jsonObject->index)
				->directive($jsonObject->directive)
				->entity($jsonObject->entity)
				->entityID($jsonObject->entityID)
				->targetType($jsonObject->targetType)
				->target($jsonObject->target)
				->build();
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
	
	public function getIndex()
	{
		return $this->index;
	}
	
	public function index($index)
	{
		$this->index = $index;
		return $this;
	}
	
	public function getDirective()
	{
		return $this->directive;
	}
	
	public function directive($directive)
	{
		$this->directive = $directive;
		return $this;
	}
	
	public function getEntity()
	{
		return $this->entity;
	}
	
	public function entity($entity)
	{
		$this->entity = $entity;
		return $this;
	}
	
	public function getEntityID()
	{
		return $this->entityID;
	}
	
	public function entityID($entityID)
	{
		$this->entityID = $entityID;
		return $this;
	}
	
	public function getTargetType()
	{
		return $this->targetType;
	}
	
	public function targetType($targetType)
	{
		$this->targetType = $targetType;
		return $this;
	}
	
	public function getTarget()
	{
		return $this->target;
	}
	
	public function target($target)
	{
		$this->target = $target;
		return $this;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		if($this->index == NULL 
			|| !is_numeric($this->index) 
			|| $this->index < 0)
			$this->index = 0;
		
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!GID::isValid($this->owner))
			throw new IllegalModelStateException('Invalid owner');
		if($this->entityType == NULL)
			throw new IllegalModelStateException('Invalid entityType');
		if($this->entityID == NULL)
			throw new IllegalModelStateException('Invalid entityID');
		
		if($this->directive != AccessControlRuleObject::DIRECTIVE_DENY 
			&& $this->directive != AccessControlRuleObject::DIRECTIVE_ALLOW)
			throw new IllegalModelStateException('Invalid directive');
		
		if($this->entity == NULL)
			throw new IllegalModelStateException('Invalid entity');
		
		if($this->entityType != AccessControlRuleObject::ENTITY_TYPE_ALL 
			&& $this->scope != AccessControlRuleObject::ENTITY_TYPE_FRIENDS 
			&& $this->scope != AccessControlRuleObject::ENTITY_TYPE_GROUP 
			&& $this->scope != AccessControlRuleObject::ENTITY_TYPE_INDIVIDUAL)
			throw new IllegalModelStateException('Invalid entityType');
		
		if($this->targetType != AccessControlRuleObject::TARGET_TYPE_INTERFACE 
			&& $this->scope != AccessControlRuleObject::TARGET_TYPE_CONTENT)
			throw new IllegalModelStateException('Invalid targetType');
		
		if($this->target == NULL)
			throw new IllegalModelStateException('Invalid target');
		
		return new AccessControlRuleObject($this);
	}
}