<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\RemoteObject;
use sgoendoer\Sonic\Model\LinkObjectBuilder;

/**
 * Represents a LINK object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class LinkObject extends RemoteObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'link';
	
	protected $owner = NULL;
	protected $link = NULL;
	protected $datetime = NULL;
	
	public function __construct(LinkObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->owner = $builder->getOwner();
		$this->link = $builder->getLink();
		$this->datetime = $builder->getDatetime();
		$this->signature = $builder->getSignature();
	}
	
	public function setOwner($owner)
	{
		$this->owner = $owner;
		
		$this->invalidate();
		return $this;
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	public function setLink($link)
	{
		$this->link = $link;
		
		$this->invalidate();
		return $this;
	}
	
	public function getLink()
	{
		return $this->link;
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
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context":"' . LinkObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . 	LinkObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",' 
			. '"owner":"' . 	$this->owner . '",' 
			. '"link":"' .		$this->link . '",'
			. '"datetime":"' . $this->datetime . '",'
			. '"signature":' . $this->signature->getJSONString()
			. '}';
		
		return $json;
	}
	
	protected function getStringForSignature()
	{
		return $this->objectID . $this->owner . $this->link . $this->datetime;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/link",
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/link/objectID",
				"type": "string"
			},
			"owner":
			{
				"id": "http://jsonschema.net/sonic/link/owner",
				"type": "string"
			},
			"link":
			{
				"id": "http://jsonschema.net/sonic/link/link",
				"type": "string"
			},
			"datetime":
			{
				"id": "http://jsonschema.net/sonic/link/datetime",
				"type": "string"
			},
			"signature":
			{
				"id": "http://jsonschema.net/sonic/link/signature",
				"type": "object"
			}
		},
		"required": [
			"objectID",
			"owner",
			"link",
			"datetime",
			"signature"
		]
	}';
}

?>