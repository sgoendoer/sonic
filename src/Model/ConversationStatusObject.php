<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\ReferencingRemoteObject;
use sgoendoer\Sonic\Model\ConversationStatusObjectBuilder;
use sgoendoer\Sonic\Model\ConversationStatusObject;

/**
 * Represents a CONVERSATION STATUS object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ConversationStatusObject extends ReferencingRemoteObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'conversation-status';
	
	const STATUS_INVITED = 'INVITED';
	const STATUS_JOINED = 'JOINED';
	const STATUS_LEFT = 'LEFT';
	const STATUS_DECLINED = 'DECLINED';
	
	protected $author = NULL;
	protected $datetime = NULL;
	protected $status = NULL;
	protected $targetGID = NULL;
	
	public function __construct(ConversationStatusObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID(), $builder->getTargetID());
		
		$this->targetGID = $builder->getTargetGID();
		$this->status = $builder->getStatus();
		$this->author = $builder->getAuthor();
		$this->datetime = $builder->getDatetime();
		$this->signature =$builder->getSignature();
	}
	
	public function getAuthor()
	{
		return $this->author;
	}
	
	public function setAuthor($author)
	{
		$this->author = $author;
		$this->invalidate();
		
		return $this;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
		$this->invalidate();
		
		return $this;
	}
	
	public function getTargetGID()
	{
		$this->targetGID;
	}
	
	public function setTargetGID($targetGID)
	{
		$this->targetGID = $targetGID;
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
			. '"@context":"' . ConversationStatusObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . ConversationStatusObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",'
			. '"targetID":"' . $this->targetID . '",'
			. '"author":"' . $this->author . '",'
			. '"status":"' . $this->status . '",'
			. '"datetime":"' . $this->datetime . '",'
			. '"targetGID":"' . $this->targetGID . '",'
			. '"signature":' . $this->signature->getJSONString() . ''
			. '}';
		
		return $json;
	}
	
	protected function getStringForSignature()
	{
		return $this->objectID
				. $this->targetID
				. $this->author
				. $this->datetime
				. $this->status
				. $this->targetGID;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/conversationMessage",
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/conversationMessage/objectID",
				"type": "string"
			},
			"targetID":
			{
				"id": "http://jsonschema.net/sonic/conversationMessage/targetID",
				"type": "string"
			},
			"status":
			{
				"id": "http://jsonschema.net/sonic/conversationMessage/title",
				"type": "string"
			},
			"targetGID":
			{
				"id": "http://jsonschema.net/sonic/conversationMessage/body",
				"type": "string"
			},
			"author":
			{
				"id": "http://jsonschema.net/sonic/conversationMessage/author",
				"type": "string"
			},
			"datetime":
			{
				"id": "http://jsonschema.net/sonic/conversationMessage/datetime",
				"type": "string"
			},
			"signature":
			{
				"id": "http://jsonschema.net/sonic/conversationMessage/signature",
				"type": "object"
			}
		},
		"required": [
			"objectID",
			"targetID",
			"status",
			"author",
			"targetGID",
			"datetime",
			"signature"
		]
	}';
}

?>