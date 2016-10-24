<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\ReferencingObject;
use sgoendoer\Sonic\Model\LinkResponseObjectBuilder;
use sgoendoer\Sonic\Model\LinkObject;

/**
 * Represents a LINK RESPONSE object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class LinkResponseObject extends ReferencingObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'link-response';
	
	protected $accept = false;
	protected $link = NULL;
	protected $datetime = NULL;
	protected $message = NULL;
	
	public function __construct(LinkResponseObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID(), $builder->getTargetID());
		
		$this->setAccept($builder->getAccept());
		$this->setLink($builder->getLink());
		$this->setDatetime($builder->getDatetime());
		$this->setMessage($builder->getMessage());
	}
	
	public function setMessage($message)
	{
		$this->message = $message;
	}
	
	public function getMessage()
	{
		return $this->message;
	}
	
	public function setAccept($accept)
	{
		$this->accept = $accept;
	}
	
	public function getAccept()
	{
		return $this->accept;
	}
	
	public function setLink(LinkObject $linkObject)
	{
		$this->link = $linkObject;
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
		return $this;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context":"' . LinkResponseObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . LinkResponseObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",'
			. '"targetID":"' . $this->targetID . '",'
			. '"datetime":"' . $this->datetime . '",';
		
		if($this->accept === true)
			$json .= '"accept":true';
		else
			$json .= '"accept":false';
		
		if($this->message != NULL && $this->message != '')
			$json .= ', "message":"' . $this->message . '"';
		
		if($this->link != NULL)
			$json .= ', "link":' . $this->link->getJSONString();
		
		$json .= '}';
		
		return $json;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/linkresponse",
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/linkresponse/objectID",
				"type": "string"
			},
			"accept":
			{
				"id": "http://jsonschema.net/sonic/linkresponse/accept",
				"type": "boolean"
			},
			"link":
			{
				"id": "http://jsonschema.net/sonic/linkresponse/link",
				"type": "object"
			},
			"datetime":
			{
				"id":"http://jsonschema.net/sonic/linkresponse/datetime",
				"type":"string"
			},
			"message":
			{
				"id":"http://jsonschema.net/sonic/linkresponse/message",
				"type":"string"
			}
		},
		"required": [
			"objectID",
			"accept",
			"link",
			"datetime"
		]
	}';
}

?>