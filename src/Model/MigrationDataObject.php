<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;

use sgoendoer\Sonic\Model\MigrationDataObjectBuilder;
use sgoendoer\Sonic\Model\ReferencingRemoteObject;

/**
 * Represents a MIGRATION DATA object
 * version 20180120
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class MigrationDataObject extends ReferencingRemoteObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'migration-data';
	
	protected $item				= NULL;
	protected $datetime			= NULL;
	
	public function __construct(MigrationDataObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID(), $builder->getTargetID());
		
		$this->item = $builder->getItem();
		$this->datetime = $builder->getDatetime();
		$this->signature = $builder->getSignature();
	}
	
	public function getItem()
	{
		return $this->item;
	}
	
	public function setItem($item)
	{
		$this->item = $item;
		$this->invalidate();
		return $this;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function setDatetime($datetime = NULL)
	{
		if($datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
		else
			$this->datetime = $datetime;
		$this->invalidate();
		return $this;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context":"' . CommentObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . CommentObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",'
			. '"targetID":"' . $this->targetID . '",'
			. '"item": [';
			
			foreach($this->item as $i)
			{
				$json .= '' . $i->getJSONString() . '';
				if($i !== end($this->item)) $json .= ',';
			}
			
			$json .= '],'
			. '"datetime":"' . $this->datetime . '",';
			
			$json .= '"signature": ' . $this->signature->getJSONString() . ''
		 	. '}';
		
		return $json;
	}
	
	protected function getStringForSignature()
	{
		return $this->objectID
				. $this->targetID
				. $this->item->getJSONString()
				. $this->datetime;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/migration-data",
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
			"item":
			{
				"id": "http://jsonschema.net/sonic/comment/item",
				"type": "array"
			},
			"datetime":
			{
				"id": "http://jsonschema.net/sonic/comment/datetime,
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
			"item",
			"datetime",
			"signature"
		]
	}';
}

?>