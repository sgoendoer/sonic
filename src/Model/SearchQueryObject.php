<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\SearchQueryObjectBuilder;
use sgoendoer\Sonic\Model\RemoteObject;
use sgoendoer\Sonic\Date\XSDDateTime;

use sgoendoer\json\JSONObject;
use sgoendoer\esquery\ESQuery;
use sgoendoer\esquery\ESQueryBuilder;

/**
 * Represents a SEARCH QUERY object
 * version 20160419
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class SearchQueryObject extends RemoteObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'search-query';
	
	protected $initiatingGID = NULL;
	protected $query = NULL;
	protected $hopCount = NULL;
	protected $datetime = NULL;
	
	public function __construct(SearchQueryObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->initiatingGID = $builder->getinitiatingGID();
		$this->query = $builder->getQuery();
		$this->hopCount = $builder->getHopCount();
		$this->datetime = $builder->getDatetime();
		$this->signature = $builder->getSignature();
	}
	
	public function getInitiatingGID()
	{
		return $this->initiatingGID;
	}
	
	public function setInititatingGID($initiatingGID)
	{
		$this->initiatingGID = $initiatingGID;
		$this->invalidate();
		return $this;
	}
	
	public function getQuery()
	{
		return $this->query;
	}
	
	public function setQuery(ESQuery $query)
	{
		$this->query = $query;
		$this->invalidate();
		return $this;
	}
	
	public function getHopCount()
	{
		return $this->hopCount;
	}
	
	public function setHopCount($hopCount)
	{
		$this->hopCount = $hopCount;
		return $this;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function setDatetime($datetime)
	{
		if ($datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
		else
			$this->datetime = $datetime;
		$this->invalidate();
		return $this;
	}
	
	public function getJSONString()
	{
		$json = '{'
			. '"@context":"' . SearchQueryObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . SearchQueryObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",'
			. '"initiatingGID":"' . $this->initiatingGID . '",'
			. '"query":' . $this->query->getJSONString() . ','
			. '"hopCount":"' . $this->hopCount . '",'
			. '"datetime":"' . $this->datetime . '",'
			. '"signature":' . $this->signature->getJSONString() . ''
			. '}';
			
		return $json;
	}
	
	protected function getStringForSignature()
	{
		return $this->objectID
		. $this->initiatingGID
		. $this->query->getJSONString()
		. $this->datetime;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/searchQuery",
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/searchQuery/objectID",
				"type": "string"
			},
			"initiatingGID":
			{
				"id": "http://jsonschema.net/sonic/searchQuery/initiatingGID",
				"type": "string"
			},
			"query":
			{
				"id": "http://jsonschema.net/sonic/searchQuery/query",
				"type": "object"
			},
			"hopCount":
			{
				"id": "http://jsonschema.net/sonic/searchQuery/hopCount",
				"type": "integer"
			},
			"datetime":
			{
				"id": "http://jsonschema.net/sonic/searchQuery/datePublished,
				"type": "string"
			},
			"signature":
			{
				"id": "http://jsonschema.net/sonic/searchQuery/signature",
				"type": "object"
			}
		},
		"required": [
			"objectID",
			"initiatingGID",
			"query",
			"hopCount",
			"datetime",
			"signature"
		]
	}';
}

?>