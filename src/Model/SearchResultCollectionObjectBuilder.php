<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\ReferencingObjectBuilder;
use sgoendoer\Sonic\Model\SearchResultCollectionObject;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\IllegalModelStateException;

/**
 * Builder class for a SEARCH RESULT COLLECTION object
 * version 20160429
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class SearchResultCollectionObjectBuilder extends ReferencingObjectBuilder
{
	protected $platformGID = NULL;
	protected $datetime = NULL;
	protected $results = array();
	
	public function __construct()
	{}
		
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$builder = (new SearchResultCollectionObjectBuilder())
			->objectID($jsonObject->objectID)
			->targetID($jsonObject->targetID)
			->platformGID($jsonObject->platformGID)
			->datetime($jsonObject->datetime);
		
		foreach($jsonObject->results as $result)
		{
			$builder->result(SearchResultObjectBuilder::buildFromJSON(json_encode($result)));
		}
		
		return $builder->build();
	}
	
	public function getPlatformGID()
	{
		return $this->platformGID;
	}
	
	public function platformGID($platformGID)
	{
		$this->platformGID = $platformGID;
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
	
	public function result($result)
	{
		$this->results[] = $result;
		return $this;
	}
	
	public function results($resultkArray)
	{
		$this->results = $resultkArray;
		return $this;
	}
	
	public function getResults()
	{
		return $this->results;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!UOID::isValid($this->targetID))
			throw new IllegalModelStateException('Invalid targetID');
		if(!GID::isValid($this->platformGID))
			throw new IllegalModelStateException('Invalid platformGID');
		if(!XSDDateTime::validateXSDDateTime($this->datetime))
			throw new IllegalModelStateException('Invalid datetime');
		if(!is_array($this->results))
			throw new IllegalModelStateException('Invalid results value');
		
		return new SearchResultCollectionObject($this);
	}
}

?>