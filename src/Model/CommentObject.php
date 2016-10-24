<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;

use sgoendoer\Sonic\Model\CommentObjectBuilder;
use sgoendoer\Sonic\Model\ILikeableObject;
use sgoendoer\Sonic\Model\ReferencingRemoteObject;
use sgoendoer\Sonic\Model\IAccessRestrictableObject;

/**
 * Represents a COMMENT object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class CommentObject extends ReferencingRemoteObject implements ILikeableObject, IAccessRestrictableObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'comment';
	
	protected $author			= NULL;
	protected $comment			= NULL;
	protected $datePublished	= NULL;
	protected $dateUpdated		= NULL;
	
	public function __construct(CommentObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID(), $builder->getTargetID());
		
		$this->author = $builder->getAuthor();
		$this->comment = $builder->getComment();
		$this->datePublished = $builder->getDatePublished();
		$this->dateUpdated = $builder->getDateUpdated();
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
	
	public function getComment()
	{
		return $this->comment;
	}
	
	public function setComment($comment)
	{
		$this->comment = $comment;
		$this->invalidate();
		return $this;
	}
	
	public function getDatePublished()
	{
		return $this->datePublished;
	}
	
	public function setDatePublished($datePublished = NULL)
	{
		if($datePublished == NULL) 
			$this->datePublished = XSDDateTime::getXSDDateTime();
		else
			$this->datePublished = $datePublished;
		$this->invalidate();
		return $this;
	}
	
	public function getDateUpdated()
	{
		return $this->dateUpdated;
	}
	
	public function setDateUpdated($dateUpdated = NULL)
	{
		if($dateUpdated == NULL) 
			$this->dateUpdated = XSDDateTime::getXSDDateTime();
		else
			$this->dateUpdated = $dateUpdated;
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
			. '"comment":"' . $this->comment . '",'
			. '"author":"' . $this->author . '",'
			. '"datePublished":"' . $this->datePublished . '",';
			
			if($this->dateUpdated != NULL)
				$json .= '"dateUpdated": "' . $this->dateUpdated . '",';
				
			$json .= '"signature": ' . $this->signature->getJSONString() . ''
		 	. '}';
		
		return $json;
	}
	
	protected function getStringForSignature()
	{
		return $this->objectID
				. $this->targetID
				. $this->author
				. $this->comment
				. $this->datePublished
				. $this->dateUpdated;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/comment",
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
			"author":
			{
				"id": "http://jsonschema.net/sonic/comment/author",
				"type": "string"
			},
			"comment":
			{
				"id": "http://jsonschema.net/sonic/comment/comment",
				"type": "string"
			},
			"datePublished":
			{
				"id": "http://jsonschema.net/sonic/comment/datePublished,
				"type": "string"
			},
			"dateUpdated":
			{
				"id": "http://jsonschema.net/sonic/comment/dateUpdated,
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
			"author",
			"comment",
			"datePublished",
			"signature"
		]
	}';
}

?>