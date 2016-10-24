<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\ConversationMessageObjectBuilder;
use sgoendoer\Sonic\Model\ConversationMessageObject;
use sgoendoer\Sonic\Model\ConversationMessageStatusObject;

/**
 * Represents a CONVERSATION MESSAGE object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ConversationMessageObject extends ReferencingRemoteObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'conversation-message';
	
	protected $title = NULL;
	protected $body = NULL;
	protected $author = NULL;
	protected $datetime = NULL;
	protected $status = NULL;
	
	public function __construct(ConversationMessageObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID(), $builder->getTargetID());
		
		$this->title = $builder->getTitle();
		$this->body = $builder->getBody();
		$this->author = $builder->getAuthor();
		$this->datetime = $builder->getDatetime();
		$this->signature = $builder->getSignature();
		$this->status = $builder->getStatus();
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function setTitle($title)
	{
		$this->title = $title;
		$this->invalidate();
		return $this;
	}
	
	public function getBody()
	{
		return $this->body;
	}
	
	public function setBody($body)
	{
		$this->body = $body;
		$this->invalidate();
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
	
	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context":"' . ConversationMessageObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . ConversationMessageObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",'
			. '"targetID":"' . $this->targetID . '",';
			
		if($this->title != NULL)
			$json .= '"title":"' . $this->title . '",';
		
		$json .= '"author":"' . $this->author . '",'
			. '"body":"' . $this->body . '",'
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
				. $this->author
				. $this->datetime
				. $this->body
				. $this->title;
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
			"title":
			{
				"id": "http://jsonschema.net/sonic/conversationMessage/title",
				"type": "string"
			},
			"body":
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
			"status":
			{
				"id": "http://jsonschema.net/sonic/conversationMessage/statusList",
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
			"body",
			"author",
			"datetime",
			"signature",
			"status"
		]
	}';
}

?>