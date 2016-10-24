<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Model\CommentObject;
use sgoendoer\Sonic\Model\SignatureObject;
use sgoendoer\Sonic\Model\ReferencingRemoteObjectBuilder;
use sgoendoer\Sonic\Model\IllegalModelStateException;

/**
 * Builder class for a COMMENT object
 * version 20151021
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class CommentObjectBuilder extends ReferencingRemoteObjectBuilder
{
	protected $author			= NULL;
	protected $comment			= NULL;
	protected $datePublished	= NULL;
	protected $dateUpdated		= NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$json = json_decode($json);
		
		$signature = SignatureObject::createFromJSON(json_encode($json->signature));
		
		$builder = new CommentObjectBuilder();
		$builder->objectID($json->objectID)
				->targetID($json->targetID)
				->author($json->author)
				->comment($json->comment)
				->datePublished($json->datePublished)
				->signature($signature);
				
		if(property_exists($json, 'dateUpdated'))
			$builder->dateUpdated($json->dateUpdated);
		
		return $builder->build();
	}
	
	public function author($author)
	{
		$this->author = $author;
		return $this;
	}
	
	public function getAuthor()
	{
		return $this->author;
	}
	
	public function comment($comment)
	{
		$this->comment = $comment;
		return $this;
	}
	
	public function getComment()
	{
		return $this->comment;
	}
	
	public function datePublished($datePublished = NULL)
	{
		if($datePublished == NULL) 
			$this->datePublished = XSDDateTime::getXSDDateTime();
		else
			$this->datePublished = $datePublished;
		return $this;
	}
	
	public function getDatePublished()
	{
		return $this->datePublished;
	}
	
	public function dateUpdated($dateUpdated)
	{
		if($dateUpdated == NULL) 
			$this->dateUpdated = XSDDateTime::getXSDDateTime();
		else
			$this->dateUpdated = $dateUpdated;
		return $this;
	}
	
	public function getDateUpdated()
	{
		return $this->dateUpdated;
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
		if($this->comment == '' || $this->comment == NULL)
			throw new IllegalModelStateException('Invalid comment');
		if(!XSDDateTime::validateXSDDateTime($this->datePublished))
			throw new IllegalModelStateException('Invalid datePublished');
		if($this->dateUpdated != NULL && !XSDDateTime::validateXSDDateTime($this->dateUpdated))
			throw new IllegalModelStateException('Invalid dateUpdated');
			
		$comment = new CommentObject($this);
		
		if($this->signature == NULL)
			$comment->signObject();
		
		if(!$comment->verifyObjectSignature())
			throw new IllegalModelStateException('Invalid signature');
		
		return $comment;
	}
}

?>