<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\Object;
use sgoendoer\Sonic\Model\LinkRequestObjectBuilder;

/**
 * Represents a LINK REQUEST object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class LinkRequestObject extends Object
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'link-request';
	
	protected $initiatingGID = NULL;
	protected $targetedGID = NULL;
	protected $datetime = NULL;
	protected $message = NULL;
	
	public function __construct(LinkRequestObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->setInitiatingGID($builder->getInitiatingGID());
		$this->setTargetedGID($builder->getTargetedGID());
		$this->setDatetime($builder->getDatetime());
		$this->setMessage($builder->getMessage());
	}
	
	public function setInitiatingGID($initiatingGID)
	{
		$this->initiatingGID = $initiatingGID;
	}
	
	public function getInitiatingGID()
	{
		return $this->initiatingGID;
	}
	
	public function setTargetedGID($targetedGID)
	{
		$this->targetedGID = $targetedGID;
	}
	
	public function getTargetedGID()
	{
		return $this->targetedGID;
	}
	
	public function setDatetime($datetime)
	{
		$this->datetime = $datetime;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function getMessage()
	{
		return $this->message;
	}
	
	public function setMessage($message)
	{
		$this->message = $message;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context":"' . LinkRequestObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . LinkRequestObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",' 
			. '"initiatingGID":"' . $this->initiatingGID . '",' 
			. '"targetedGID":"' . $this->targetedGID . '",'
			. '"datetime":"' . $this->datetime . '"';
		
		if($this->message != NULL && $this->message != '')
			$json .= ', "message":"' . $this->message . '"';
		
		$json .= '}';
		
		return $json;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/linkrequest",
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/linkrequest/objectID",
				"type": "string"
			},
			"initiatingGID":
			{
				"id": "http://jsonschema.net/sonic/linkrequest/initiatingGID",
				"type": "string"
			},
			"targetedGID":
			{
				"id": "http://jsonschema.net/sonic/linkrequest/targetedGID",
				"type": "string"
			},
			"datetime":
			{
				"id":"http://jsonschema.net/sonic/linkrequest/datetime",
				"type":"string"
			},
			"message":
			{
				"id":"http://jsonschema.net/sonic/linkrequest/message",
				"type":"string"
			}
		},
		"required": [
			"objectID",
			"initiatingGID",
			"targetedGID",
			"datetime"
		]
	}';
}

?>