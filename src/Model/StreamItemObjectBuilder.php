<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\SignatureObject;
use sgoendoer\Sonic\Model\StreamItemObject;
use sgoendoer\Sonic\Model\RemoteObjectBuilder;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\IllegalModelStateException;

use sgoendoer\json\JSONObject;

/**
 * Builder class for a SREAM ITEM object
 * version 20151021
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class StreamItemObjectBuilder extends RemoteObjectBuilder
{
	protected $owner = NULL;
	protected $author = NULL;
	protected $datetime = NULL;
	protected $activity = NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$signature = SignatureObject::createFromJSON(json_encode($jsonObject->signature));
		
		return (new StreamItemObjectBuilder())
				->objectID($jsonObject->objectID)
				->owner($jsonObject->owner)
				->author($jsonObject->author)
				->dateTime($jsonObject->datetime)
				->activity(new JSONObject($jsonObject->activity))
				->signature($signature)
				->build();
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	public function owner($owner)
	{
		$this->owner = $owner;
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
	
	public function getActivity()
	{
		return $this->activity;
	}
	
	public function activity(JSONObject $activity)
	{
		$this->activity = $activity;
		return $this;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
			
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!GID::isValid($this->owner))
			throw new IllegalModelStateException('Invalid owner');
		if(!GID::isValid($this->author))
			throw new IllegalModelStateException('Invalid author');
		if($this->activity == '' || $this->activity == NULL)
			throw new IllegalModelStateException('Invalid activity');
		if(!XSDDateTime::validateXSDDateTime($this->datetime))
			throw new IllegalModelStateException('Invalid datetime');
		
		$streamItem = new StreamItemObject($this);
		
		if($streamItem->getSignature() == NULL)
			$streamItem->signObject();
			
		if(!$streamItem->verifyObjectSignature())
			throw new IllegalModelStateException('Invalid signature');
		
		return $streamItem;
	}
}

?>