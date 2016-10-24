<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\SignatureObject;
use sgoendoer\Sonic\Model\TagObject;
use sgoendoer\Sonic\Model\ReferencingRemoteObjectBuilder;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\IllegalModelStateException;
use sgoendoer\Sonic\Model\SearchQueryObject;

use sgoendoer\json\JSONObject;
use sgoendoer\esquery\ESQuery;
use sgoendoer\esquery\ESQueryBuilder;

/**
 * Builder class for a SEARCH QUERY object
 * version 20160419
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class SearchQueryObjectBuilder extends RemoteObjectBuilder
{
	protected $initiatingGID = NULL;
	protected $query = NULL;
	protected $hopCount = NULL;
	protected $datetime = NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$signature = SignatureObject::createFromJSON(json_encode($jsonObject->signature));
		
		return (new SearchQueryObjectBuilder())
			->objectID($jsonObject->objectID)
			->initiatingGID($jsonObject->initiatingGID)
			->query(ESQueryBuilder::buildFromJSON(json_encode($jsonObject->query)))
			->hopCount($jsonObject->hopCount)
			->datetime($jsonObject->datetime)
			->signature($signature)
			->build();
	}
	
	public function getInitiatingGID()
	{
		return $this->initiatingGID;
	}
	
	public function initiatingGID($initiatingGID)
	{
		$this->initiatingGID = $initiatingGID;
		return $this;
	}
	
	public function getQuery()
	{
		return $this->query;
	}
	
	public function query(ESQuery $query)
	{
		
		$this->query = $query;
		return $this;
	}
	
	public function getHopCount()
	{
		return $this->hopCount;
	}
	
	public function hopCount($hopCount)
	{
		$this->hopCount = $hopCount;
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
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		if($this->hopCount == NULL)
			$this->hopCount = 0;
		if($this->datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
		
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!GID::isValid($this->initiatingGID))
			throw new IllegalModelStateException('Invalid initiatingGID');
		if(!is_numeric($this->hopCount) || $this->hopCount > 3)
			throw new IllegalModelStateException('Invalid value for hopCount');
		if(!XSDDateTime::validateXSDDateTime($this->datetime))
			throw new IllegalModelStateException('Invalid datetime');
		if($this->query === NULL || !($this->query instanceof ESQuery))
			throw new IllegalModelStateException('Invalid value for query');
		
		$searchQuery = new SearchQueryObject($this);
		
		if($searchQuery->getSignature() == NULL)
			$searchQuery->signObject();
		
		if(!$searchQuery->verifyObjectSignature())
			throw new IllegalModelStateException('Invalid signature');
		
		return $searchQuery;
	}
}

?>