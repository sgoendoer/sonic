<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\SignatureObject;
use sgoendoer\Sonic\Model\TagObject;
use sgoendoer\Sonic\Model\ReferencingRemoteObjectBuilder;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\IllegalModelStateException;

/**
 * Builder class for a TAG object
 * version 20151021
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class TagObjectBuilder extends ReferencingRemoteObjectBuilder
{
	protected $author = NULL;
	protected $tag = NULL;
 	protected $datePublished = NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$signature = SignatureObject::createFromJSON(json_encode($jsonObject->signature));
		
		return (new TagObjectBuilder())
				->objectID($jsonObject->objectID)
				->targetID($jsonObject->targetID)
				->author($jsonObject->author)
				->tag($jsonObject->tag)
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
	
	public function getTag()
	{
		return $this->tag;
	}
	
	public function tag($tag)
	{
		$this->tag = $tag;
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
			
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!UOID::isValid($this->targetID))
			throw new IllegalModelStateException('Invalid targetID');
		if(!GID::isValid($this->author))
			throw new IllegalModelStateException('Invalid author');
		if(!GID::isValid($this->tag))
			throw new IllegalModelStateException('Invalid tag');
		if(!XSDDateTime::validateXSDDateTime($this->datePublished))
			throw new IllegalModelStateException('Invalid date');
		
		$tag = new TagObject($this);
		
		if($tag->getSignature() == NULL)
			$tag->signObject();
		
		if(!$tag->verifyObjectSignature())
			throw new IllegalModelStateException('Invalid signature');
		
		return $tag;
	}
}

?>