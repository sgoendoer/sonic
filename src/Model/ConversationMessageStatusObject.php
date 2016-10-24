<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\ReferencingRemoteObject;
use sgoendoer\Sonic\Model\ConversationMessageStatusObjectBuilder;
use sgoendoer\Sonic\Model\ConversationMessageStatusObject;

/**
 * Represents a CONVERSATION MESSAGE STATUS object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ConversationMessageStatusObject extends ReferencingRemoteObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'conversation-message-status';
	
	const STATUS_READ = 'READ';
	const STATUS_RECEIVED = 'RECEIVED';
	const STATUS_DELETED = 'DELETED';
	
	protected $conversationID = NULL;
	protected $author = NULL;
	protected $datetime = NULL;
	protected $status = NULL;
	
	public function __construct(ConversationMessageStatusObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID(), $builder->getTargetID());
		
		$this->conversationID = $builder->getConversationID();
		$this->status = $builder->getStatus();
		$this->author = $builder->getAuthor();
		$this->datetime = $builder->getDatetime();
		$this->signature = $builder->getSignature();
	}
	
	public function getConversationID()
	{
		return $this->conversationID;
	}
	
	public function setConversationID($conversationID)
	{
		$this->conversationID = $conversationID;
		return $this;
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
			. '"@context":"' . ConversationMessageStatusObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . ConversationMessageStatusObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",'
			. '"targetID":"' . $this->targetID . '",'
			. '"conversationID":"' . $this->conversationID . '",'
			. '"author":"' . $this->author . '",'
			. '"status":"' . $this->status . '",'
			. '"datetime":"' . $this->datetime . '",'
			. '"signature":' . $this->signature->getJSONString() . ''
			. '}';
		
		return $json;
	}
	
	protected function getStringForSignature()
	{
		return $this->objectID
				. $this->targetID
				. $this->conversationID
				. $this->author
				. $this->datetime
				. $this->status;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/conversationMessageStatus",
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
				"id": "http://jsonschema.net/sonic/conversationMessageStatus/targetID",
				"type": "string"
			},
			"conversationID":
			{
				"id": "http://jsonschema.net/sonic/conversationMessage/conversationID",
				"type": "string"
			},
			"status":
			{
				"id": "http://jsonschema.net/sonic/conversationMessageStatus/title",
				"type": "string"
			},
			"author":
			{
				"id": "http://jsonschema.net/sonic/conversationMessageStatus/author",
				"type": "string"
			},
			"datetime":
			{
				"id": "http://jsonschema.net/sonic/conversationMessageStatus/datetime",
				"type": "string"
			},
			"signature":
			{
				"id": "http://jsonschema.net/sonic/conversationMessageStatus/signature",
				"type": "object"
			}
		},
		"required": [
			"objectID",
			"targetID",
			"conversationID",
			"status",
			"author",
			"datetime",
			"signature"
		]
	}';
}

?>