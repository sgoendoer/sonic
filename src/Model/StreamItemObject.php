<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\RemoteObject;
use sgoendoer\Sonic\Model\StreamItemObjectBuilder;
use sgoendoer\Sonic\Model\IAccessRestrictableObject;

use sgoendoer\json\JSONObject;

/**
 * Represents a STREAM ITEM object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class StreamItemObject extends RemoteObject implements IAccessRestrictableObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'stream-item';
	
	protected $owner = NULL;
	protected $author = NULL;
	protected $datetime = NULL;
	protected $activity = NULL;
	
	public function __construct(StreamItemObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->owner = $builder->getOwner();
		$this->author = $builder->getAuthor();
		$this->datetime = $builder->getDatetime();
		$this->activity = $builder->getActivity();
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
	
	public function getActivity()
	{
		return $this->activity;
	}
	
	public function setActivity(JSONObject $activity)
	{
		$this->activity = $activity;
		$this->invalidate();
		return $this;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context":"' . StreamItemObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . StreamItemObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",'
			. '"owner":"' . $this->owner . '",'
			. '"author":"' . $this->author . '",'
			. '"datetime":"' . $this->datetime . '",'
			. '"activity":' . $this->activity->write() . ','
			. '"signature":' . $this->signature->getJSONString() . ''
			. '}';
		
		return $json;
	}
	
	protected function getStringForSignature()
	{
		return $this->objectID
				. $this->owner
				. $this->author
				. $this->datetime
				. $this->activity->write();
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/stream",
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/stream/objectID",
				"type": "string"
			},
			"owner":
			{
				"id": "http://jsonschema.net/sonic/stream/owner",
				"type": "string"
			},
			"author":
			{
				"id": "http://jsonschema.net/sonic/stream/author",
				"type": "string"
			},
			"datetime":
			{
				"id": "http://jsonschema.net/sonic/conversationMessage/datetime",
				"type": "string"
			},
			"activity":
			{
				"id": "http://jsonschema.net/sonic/stream/activity",
				"type": "object"
			},
			"signature":
			{
				"id": "http://jsonschema.net/sonic/conversationMessage/signature",
				"type": "object"
			}
		},
		"required": [
			"objectID",
			"owner",
			"author",
			"datetime",
			"activity",
			"signature"
		]
	}';
}

?>