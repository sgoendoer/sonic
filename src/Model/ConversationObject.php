<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\RemoteObject;
use sgoendoer\Sonic\Model\ConversationObjectBuilder;
use sgoendoer\Sonic\Model\ConversationObject;
use sgoendoer\Sonic\Model\ConversationStatusObject;
use sgoendoer\Sonic\Model\ConversationMessageObject;

/**
 * Represents a CONVERSATION object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ConversationObject extends RemoteObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'conversation';
	
	protected $members = array();
	protected $title = NULL;
	protected $owner = NULL;
	protected $datetime = NULL;
	
	public function __construct(ConversationObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->title = $builder->getTitle();
		$this->owner = $builder->getOwner();
		$this->datetime = $builder->getDatetime();
		$this->members = $builder->getMembers();
		asort($this->members);
		$this->signature = $builder->getSignature();
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	public function setOwner($owner)
	{
		$this->owner = $owner;
		$this->invalidate();
		return $this;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function setDatetime($datetime)
	{
		$this->datetime = $datetime;
		$this->invalidate();
		return $this;
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
	
	public function addMember($globalID)
	{
		$this->members[] = $globalID;
		asort($this->members);
		$this->invalidate();
		return $this;
	}
	
	public function setMembers($memberArray)
	{
		$this->members = $memberArray;
		asort($this->members);
		$this->invalidate();
		return $this;
	}
	
	public function getMembers()
	{
		return $this->members;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context":"' . ConversationObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . ConversationObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",'
			. '"owner":"' . $this->owner . '",'
			. '"datetime":"' . $this->datetime . '",'
			. '"members":[';
		
		asort($this->members);
		
		foreach($this->members as $member)
		{
			$json .= '"' . $member . '"';
			if($member !== end($this->members)) $json .= ',';
		}
			
		$json .= '],';
		
		if($this->title != NULL)
			$json .= '"title":"' . $this->title . '",';
		
		$json .= '"signature":' . $this->signature->getJSONString()
		. '}';
		
		return $json;
	}
	
	protected function getStringForSignature()
	{
		$string = $this->objectID
				. $this->owner
				. $this->datetime;
		
		asort($this->members);
		foreach($this->members as $member)
			$string .= $member;
		
		$string .= $this->title;
		
		return $string;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/conversation",
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/conversation/objectID",
				"type": "string"
			},
			"owner":
			{
				"id": "http://jsonschema.net/sonic/conversation/owner",
				"type": "string"
			},
			"datetime":
			{
				"id": "http://jsonschema.net/sonic/conversation/datetime",
				"type": "string"
			},
			"title":
			{
				"id": "http://jsonschema.net/sonic/conversation/title",
				"type": "string"
			},
			"members":
			{
				"id": "http://jsonschema.net/sonic/conversation/members",
				"type": "array"
			},
			"signature":
			{
				"id": "http://jsonschema.net/sonic/conversation/signature",
				"type": "object"
			}
		},
		"required": [
			"objectID",
			"owner",
			"datetime",
			"members",
			"signature"
		]
	}';
}

?>