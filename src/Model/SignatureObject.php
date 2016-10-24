<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\SignatureObject;
use sgoendoer\Sonic\Model\BasicObject;

/**
 * Represents a SIGNATURE object
 * version 20150904
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class SignatureObject extends BasicObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'signature';
	
	protected $targetID = NULL;
	protected $creatorGID = NULL;
	protected $timeSigned = NULL;
	protected $random = NULL;
	protected $signature = NULL;
	
	public function __construct($targetID, $creatorGID, $timeSigned = NULL, $random = NULL, $signature = NULL)
	{
		$this->targetID = $targetID;
		$this->creatorGID = $creatorGID;
		
		if($timeSigned != NULL)
			$this->timeSigned = $timeSigned;
		
		if($random != NULL)
			$this->random = $random;
		
		if($signature != NULL)
			$this->signature = $signature;
	}
	
	public static function createFromJSON($json)
	{
		$json = json_decode($json);
		
		return new SignatureObject($json->targetID, $json->creatorGID, $json->timeSigned, $json->random, $json->signature);
	}
	
	public function setTargetID($targetID)
	{
		$this->targetID = $targetID;
	}
	
	public function getTargetID()
	{
		return $this->targetID;
	}
	
	public function setCreatorGID($creatorGID)
	{
		$this->creatorGID = $creatorGID;
	}
	
	public function getCreatorGID()
	{
		return $this->creatorGID;
	}
	
	public function setTimeSigned($timeSigned)
	{
		$this->timeSigned = $timeSigned;
	}
	
	public function getTimeSigned()
	{
		return $this->timeSigned;
	}
	
	public function setRandom($random)
	{
		$this->random = $random;
	}
	
	public function getRandom()
	{
		return $this->random;
	}
	
	public function setSignature($signature)
	{
		$this->signature = $signature;
	}
	
	public function getSignature()
	{
		return $this->signature;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context":"' . SignatureObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . SignatureObject::JSONLD_TYPE . '",'
			. '"targetID":"' . $this->targetID . '",' 
			. '"creatorGID":"' . $this->creatorGID . '",' 
			. '"timeSigned":"' . $this->timeSigned . '",'
			. '"random":"' . $this->random . '",'
			. '"signature":"' . $this->signature . '"';
		
		$json .= '}';
		
		return $json;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/signature",
		"type": "object",
		"properties":
		{
			"targetID":
			{
				"id": "http://jsonschema.net/sonic/signature/targetID",
				"type": "string"
			},
			"creatorGID":
			{
				"id": "http://jsonschema.net/sonic/signature/creatorGID",
				"type": "string"
			},
			"timeSigned":
			{
				"id": "http://jsonschema.net/sonic/signature/timeSigned",
				"type": "string"
			},
			"datetime":
			{
				"id": "http://jsonschema.net/sonic/signature/random",
				"type": "string"
			},
			"signature":
			{
				"id": "http://jsonschema.net/sonic/signature/signature",
				"type": "string"
			}
		},
		"required": [
			"targetID",
			"creatorGID",
			"timeSigned",
			"random",
			"signature"
		]
	}';
}

?>