<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\TagObjectBuilder;
use sgoendoer\Sonic\Model\ReferencingRemoteObject;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\IAccessRestrictableObject;

/**
 * Represents a TAG object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class TagObject extends ReferencingRemoteObject implements IAccessRestrictableObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'tag';
	
	protected $author = NULL;
	protected $tag = NULL;
	protected $datePublished = NULL;
	
	public function __construct(TagObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID(), $builder->getTargetID());
		
		$this->author = $builder->getAuthor();
		$this->tag = $builder->getTag();
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
		$this->invalidate();
		return $this;
	}
	
	public function getTag()
	{
		return $this->tag;
	}
	
	public function setTag($tag)
	{
		$this->tag = $tag;
		$this->invalidate();
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
		$this->invalidate();
		return $this;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context":"' . TagObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . TagObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",'
			. '"targetID":"' . $this->targetID . '",'
			. '"tag":"' . $this->tag . '",'
			. '"author":"' . $this->author . '",'
			. '"datePublished":"' . $this->datePublished . '",'
			. '"signature":' . $this->signature->getJSONString() . ''
		 	. '}';
		
		return $json;
	}
	
	protected function getStringForSignature()
	{
		return $this->objectID
				. $this->targetID
				. $this->tag
				. $this->author
				. $this->datePublished;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/tag",
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/tag/objectID",
				"type": "string"
			},
			"targetID":
			{
				"id": "http://jsonschema.net/sonic/tag/targetID",
				"type": "string"
			},
			"tag":
			{
				"id": "http://jsonschema.net/sonic/tag/tag",
				"type": "string"
			},
			"author":
			{
				"id": "http://jsonschema.net/sonic/tag/author",
				"type": "string"
			},
			"datePublished":
			{
				"id": "http://jsonschema.net/sonic/tag/datePublished,
				"type": "string"
			},
			"signature":
			{
				"id": "http://jsonschema.net/sonic/tag/signature",
				"type": "object"
			}
		},
		"required": [
			"objectID",
			"targetID",
			"tag",
			"author",
			"datePublished",
			"signature"
		]
	}';
}

?>