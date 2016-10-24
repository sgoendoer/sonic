<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\SearchResultCollectionObjectBuilder;
use sgoendoer\Sonic\Model\ReferencingObject;
use sgoendoer\Sonic\Date\XSDDateTime;
use Illuminate\Support\Facades\Log;

/**
 * Represents a SEARCH RESULT COLLECTION object
 * version 20160429
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class SearchResultCollectionObject extends ReferencingObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'search-result-collection';
	
	protected $platformGID = NULL;
	protected $datetime = NULL;
	protected $results = array();
	
	public function __construct(SearchResultCollectionObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID(), $builder->getTargetID());
		
		$this->setPlatformGID($builder->getPlatformGID());
		$this->setDatetime($builder->getDatetime());
		$this->addResultArray($builder->getResults());
	}
	
	public function getPlatformGID()
	{
		return $this->platformGID;
	}
	
	public function setPlatformGID($platformGID)
	{
		$this->platformGID = $platformGID;
		return $this;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function setDatetime($datetime = NULL)
	{
		if ($datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
		else
			$this->datetime = $datetime;
		return $this;
	}
	
	public function addResultArray($resultArray)
	{
		$this->results = array_merge($this->results, $resultArray);
	}
	
	public function addResult(SearchResultObject $result)
	{
		$this->results[] = $result;
	}
	
	public function getResults()
	{
		return $this->results;
	}
	
	public function getJSONString()
	{
		$json = '{'
			. '"@context":"' . SearchResultCollectionObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . SearchResultCollectionObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",'
			. '"targetID":"' . $this->targetID . '",'
			. '"platformGID":"' . $this->platformGID . '",'
			. '"datetime":"' . $this->datetime . '",'
			. '"results":[';
		
		foreach ($this->results as $result)
		{
			$json .= $result->getJSONString();
			if ($result !== end($this->results)) $json .= ',';
		}
		
		$json .= ']}';
		
		return $json;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/searchResultCollection",
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/searchResultCollection/objectID",
				"type": "string"
			},
			"targetID":
			{
				"id": "http://jsonschema.net/sonic/searchResultCollection/targetID",
				"type": "string"
			},
			"platformGID":
			{
				"id": "http://jsonschema.net/sonic/searchResultCollection/platformGID",
				"type": "string"
			},
			"datetime":
			{
				"id": "http://jsonschema.net/sonic/searchResultCollection/datetime,
				"type": "string"
			},
			"results":
			{
				"id": "http://jsonschema.net/sonic/searchResultCollection/results,
				"type": "array"
			}
		},
		"required": [
			"objectID",
			"targetID",
			"platformGID",
			"datetime",
			"results"
		]
	}';
}

?>