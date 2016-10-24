<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\SignatureObject;
use sgoendoer\Sonic\Model\ConversationMessageStatusObjectBuidler;
use sgoendoer\Sonic\Model\ConversationMessageObjectBuilder;
use sgoendoer\Sonic\Model\ConversationMessageObject;
use sgoendoer\Sonic\Model\ReferencingRemoteObjectBuilder;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\IllegalModelStateException;

/**
 * Builder class for a CONVERSATION object
 * version 20151021
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ConversationMessageObjectBuilder extends ReferencingRemoteObjectBuilder
{
	protected $title = NULL;
	protected $body = NULL;
	protected $author = NULL;
	protected $datetime = NULL;
	protected $status = NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$signature = SignatureObject::createFromJSON(json_encode($jsonObject->signature));
		
		$builder = (new ConversationMessageObjectBuilder())
				->objectID($jsonObject->objectID)
				->targetID($jsonObject->targetID)
				->author($jsonObject->author)
				->body($jsonObject->body)
				->datetime($jsonObject->datetime)
				->status($jsonObject->status)
				->signature($signature);
		
		if(property_exists($jsonObject, 'title')) $builder->title($jsonObject->title);
		
		return $builder->build();
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function title($title)
	{
		$this->title = $title;
		return $this;
	}
	
	public function getBody()
	{
		return $this->body;
	}
	
	public function body($body)
	{
		$this->body = $body;
		return $this;
	}
	
	public function getAuthor()
	{
		return $this->author;
	}
	
	public function author($author)
	{
		$this->author = $author;
		return $this;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function datetime($datetime = NULL)
	{
		if($datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
		else
			$this->datetime = $datetime;
		return $this;
	}
	
	public function status($status)
	{
		$this->status = $status;
		return $this;
	}
	
	public function getStatus()
	{
		return $this->status;
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
		if(!GID::isValid($this->author))
			throw new IllegalModelStateException('Invalid author');
		if($this->body == '' || $this->body == NULL)
			throw new IllegalModelStateException('Invalid body');
		if(!XSDDateTime::validateXSDDateTime($this->datetime))
			throw new IllegalModelStateException('Invalid datetime');
			
		$conversationMessage = new ConversationMessageObject($this);
		
		if($conversationMessage->getSignature() == NULL)
			$conversationMessage->signObject();
		
		if(!$conversationMessage->verifyObjectSignature())
			throw new IllegalModelStateException('Invalid signature');
		
		return $conversationMessage;
	}
}

?>