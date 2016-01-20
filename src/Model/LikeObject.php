<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\ReferencingRemoteObject;
use sgoendoer\Sonic\Model\LikeObjectBuilder;
use sgoendoer\Sonic\Model\ConversationMessageObject;
use sgoendoer\Sonic\Model\ConversationMessageStatusObject;

/**
 * Represents a LIKE object
 * version 20151021
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class LikeObject extends ReferencingRemoteObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'like';
	
	protected $author = NULL;
	protected $datePublished = NULL;
	
	public function __construct(LikeObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID(), $builder->getTargetID());
		
		$this->author = $builder->getAuthor();
		$this->datePublished = $builder->getDatePublished();
		$this->signature = $builder->getSignature();
	}
	
	public function getAuthor()
	{
		return $this->author;
	}
	
	public function setAuthor($author)
	{
		$this->author = $author;
		return $this;
	}
	
	public function getDatePublished()
	{
		return $this->datePublished;
	}
	
	public function setDatePublished($datePublished)
	{
		if($datePublished == NULL) 
			$this->datePublished = XSDDateTime::getXSDDateTime();
		else
			$this->datePublished = $datePublished;
		return $this;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context": "' . LikeObject::JSONLD_CONTEXT . '",'
			. '"@type": "' . LikeObject::JSONLD_TYPE . '",'
			. '"objectID": "' . $this->objectID . '",'
			. '"targetID": "' . $this->targetID . '",'
			. '"author": "' . $this->author . '",'
			. '"datePublished": "' . $this->datePublished . '",'
			. '"signature": ' . $this->signature->getJSON() . ''
		 	. '}';
		
		return $json;
	}
	
	protected function getStringForSignature()
	{
		return $this->objectID
				. $this->targetID
				. $this->author
				. $this->datePublished;
	}
	
	public static function validateJSON($json)
	{
		$result = \Jsv4::validate(json_decode($json), json_decode(LikeObject::SCHEMA));
		
		if($result->valid == true)
			return true;
		else
			throw new \Exception('invalid JSON format for Like: ' . $result->errors->message);
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/like,
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/like/objectID",
				"type": "string"
			},
			"targetID":
			{
				"id": "http://jsonschema.net/sonic/like/targetID",
				"type": "string"
			},
			"author":
			{
				"id": "http://jsonschema.net/sonic/like/author",
				"type": "string"
			},
			"datePublished":
			{
				"id": "http://jsonschema.net/sonic/like/datePublished,
			"type": "string"
			},
			"signature":
			{
				"id": "http://jsonschema.net/sonic/like/signature",
				"type": "object"
			}
		},
		"required": [
			"objectID",
			"targetID",
			"author",
			"datePublished",
			"signature"
		]
	}';
}

?>