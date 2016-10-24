<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\LinkResponseObject;
use sgoendoer\Sonic\Model\LinkObjectBuilder;
use sgoendoer\Sonic\Model\LinkObject;
use sgoendoer\Sonic\Model\ReferencingRemoteObjectBuilder;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\IllegalModelStateException;

/**
 * Builder class for a LINK REQUEST object
 * version 20151020
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class LinkResponseObjectBuilder extends ReferencingObjectBuilder
{
	protected $accept = false;
	protected $link = NULL;
	protected $datetime = NULL;
	protected $message = NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$builder = (new LinkResponseObjectBuilder())
				->objectID($jsonObject->objectID)
				->targetID($jsonObject->targetID)
				->accept($jsonObject->accept)
				->datetime($jsonObject->datetime);
				
		if(property_exists($jsonObject, "message"))
			$builder->message($jsonObject->message);
		
		if(property_exists($jsonObject, "link"))
		{
			$builder->link(LinkObjectBuilder::buildFromJSON(json_encode($jsonObject->link)));
		}
		
		return $builder->build();
	}
	
	public function accept($accept)
	{
		$this->accept = $accept;
		return $this;
	}
	
	public function getAccept()
	{
		return $this->accept;
	}
	
	public function link(LinkObject $link)
	{
		$this->link = $link;
		return $this;
	}
	
	public function getLink()
	{
		return $this->link;
	}
	
	public function datetime($datetime = NULL)
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
	
	public function message($message = NULL)
	{
		$this->message = $message;
		return $this;
	}
	
	public function getMessage()
	{
		return $this->message;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		if($this->datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
		
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!UOID::isValid($this->targetID))
			throw new IllegalModelStateException('Invalid targetID');
		if($this->accept === true && $this->link == NULL)
			throw new IllegalModelStateException('Invalid link');
		if(!XSDDateTime::validateXSDDateTime($this->datetime))
			throw new IllegalModelStateException('Invalid datetime: ' . $this->datetime);
		
		return new LinkResponseObject($this);
	}
}

?>