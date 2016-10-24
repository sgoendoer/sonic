<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\SignatureObject;
use sgoendoer\Sonic\Model\LikeObject;
use sgoendoer\Sonic\Model\ReferencingRemoteObjectBuilder;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\IllegalModelStateException;


/**
 * Builder class for a LIKE object
 * version 20151021
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class LikeObjectBuilder extends ReferencingRemoteObjectBuilder
{
	protected $author = NULL;
 	protected $datePublished = NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$signature = SignatureObject::createFromJSON(json_encode($jsonObject->signature));
		
		return (new LikeObjectBuilder())
				->objectID($jsonObject->objectID)
				->targetID($jsonObject->targetID)
				->author($jsonObject->author)
				->datePublished($jsonObject->datePublished)
				->signature($signature)
				->build();
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
	
	public function getDatePublished()
	{
		return $this->datePublished;
	}
	
	public function datePublished($datePublished = NULL)
	{
		if($datePublished == NULL) 
			$this->datePublished = XSDDateTime::getXSDDateTime();
		else
			$this->datePublished = $datePublished;
		return $this;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		if($this->datePublished == NULL)
			$this->datePublished = XSDDateTime::getXSDDateTime();
			
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!UOID::isValid($this->targetID))
			throw new IllegalModelStateException('Invalid targetID');
		if(!GID::isValid($this->author))
			throw new IllegalModelStateException('Invalid author');
		if(!XSDDateTime::validateXSDDateTime($this->datePublished))
			throw new IllegalModelStateException('Invalid datePublished');
		
		$like = new LikeObject($this);
		
		if($like->getSignature() == NULL)
			$like->signObject();
		
		if(!$like->verifyObjectSignature())
			throw new IllegalModelStateException('Invalid signature');
		
		return $like;
	}
}

?>