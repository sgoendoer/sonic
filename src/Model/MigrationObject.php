<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;

use sgoendoer\Sonic\Model\MigrationObjectBuilder;
use sgoendoer\Sonic\Model\RemoteObject;

/**
 * Represents a MIGRATION object
 * version 20180120
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class MigrationObject extends RemoteObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'migration';
	
	protected $migrationSource	= NULL;
	protected $migrationTarget	= NULL;
	protected $datetime			= NULL;
	
	public function __construct(MigrationObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->migrationSource = $builder->getMigrationSource();
		$this->migrationTarget = $builder->getMigrationTarget();
		$this->datetime = $builder->getDatetime();
		$this->signature = $builder->getSignature();
	}
	
	public function getMigrationSource()
	{
		return $this->migrationSource;
	}
	
	public function setMigrationSource($migrationSource)
	{
		$this->migrationSource = $migrationSource;
		$this->invalidate();
		return $this;
	}
	
	public function getMigrationTarget()
	{
		return $this->migrationTarget;
	}
	
	public function setMigrationTarget($migrationTarget)
	{
		$this->migrationTarget = $migrationTarget;
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
			. '"migrationSource":"' . $this->migrationSource . '",'
			. '"migrationTarget":"' . $this->migrationTarget . '",'
			. '"datetime":"' . $this->datetime . '",';
			
			$json .= '"signature": ' . $this->signature->getJSONString() . ''
		 	. '}';
		
		return $json;
	}
	
	protected function getStringForSignature()
	{
		return $this->objectID
				. $this->migrationSource
				. $this->migrationTarget
				. $this->datetime;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/migration",
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/comment/objectID",
				"type": "string"
			},
			"migrationSource":
			{
				"id": "http://jsonschema.net/sonic/comment/migrationSource",
				"type": "string"
			},
			"migrationTarget":
			{
				"id": "http://jsonschema.net/sonic/comment/migrationTarget",
				"type": "string"
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
			"migrationSource",
			"migrationTarget",
			"datetime",
			"signature"
		]
	}';
}

?>