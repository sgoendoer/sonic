<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\ReferencingObjectBuilder;
use sgoendoer\Sonic\Model\SearchResultObject;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\IllegalModelStateException;

/**
 * Builder class for a SEARCH RESULT object
 * version 20151214
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class SearchResultObjectBuilder extends ReferencingObjectBuilder
{
	protected $resultOwnerGID = NULL;
	protected $resultObjectID = NULL;
	protected $resultIndex = NULL;
	protected $resultType = NULL;
	protected $displayName = NULL;
	protected $datetime = NULL;
	
	public function __construct()
	{
	}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		return (new SearchResultObjectBuilder())
			->objectID($jsonObject->objectID)
			->targetID($jsonObject->targetID)
			->resultOwnerGID($jsonObject->resultOwnerGID)
			->resultObjectID($jsonObject->resultObjectID)
			->resultIndex($jsonObject->resultIndex)
			->resultType($jsonObject->resultType)
			->displayName($jsonObject->displayName)
			->datetime($jsonObject->datetime)
			->build();
	}
	
	public function getOwnerGID()
	{
		return $this->resultOwnerGID;
	}
	
	public function resultOwnerGID($ownerGID)
	{
		$this->resultOwnerGID = $ownerGID;
		return $this;
	}
	
	public function getResultObjectID()
	{
		return $this->resultObjectID;
	}
	
	public function resultObjectID($resultObjectID)
	{
		$this->resultObjectID = $resultObjectID;
		return $this;
	}
	
	public function getResultType()
	{
		return $this->resultType;
	}
	
	public function resultType($resultType)
	{
		$this->resultType = $resultType;
		return $this;
	}
	
	public function getResultIndex()
	{
		return $this->resultIndex;
	}
	
	public function resultIndex($resultIndex)
	{
		$this->resultIndex = $resultIndex;
		return $this;
	}
	
	public function getDisplayName()
	{
		return $this->displayName;
	}
	
	public function displayName($displayName)
	{
		$this->displayName = $displayName;
		return $this;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function datetime($datetime = NULL)
	{
		if ($datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
		else
			$this->datetime = $datetime;
		return $this;
	}
	
	public function build()
	{
		if ($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		
		if (!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if (!UOID::isValid($this->targetID))
			throw new IllegalModelStateException('Invalid targetID');
		if (!GID::isValid($this->resultOwnerGID))
			throw new IllegalModelStateException('Invalid ownerGID');
		if (!XSDDateTime::validateXSDDateTime($this->datetime))
			throw new IllegalModelStateException('Invalid datetime');
		
		return new SearchResultObject($this);
	}
}

?>