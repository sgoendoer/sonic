<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\SearchQueryObjectBuilder;
use sgoendoer\Sonic\Model\RemoteObject;
use sgoendoer\Sonic\Date\XSDDateTime;

use sgoendoer\json\JSONObject;
use sgoendoer\esquery\ESQuery;
use sgoendoer\esquery\ESQueryBuilder;

/**
 * Represents a SEARCH QUERY object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class SearchQueryObject extends RemoteObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'search-query';
	
	protected $initiatingGID = NULL;
	protected $esIndex = NULL;
	protected $esType = NULL;
	protected $query = NULL;
	protected $hopCount = NULL;
	protected $datetime = NULL;
	
	public function __construct(SearchQueryObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->initiatingGID = $builder->getinitiatingGID();
		$this->esIndex = $builder->getEsIndex();
		$this->esType = $builder->getEsType();
		$this->query = $builder->getQuery();
		$this->hopCount = $builder->getHopCount();
		$this->datetime = $builder->getDatetime();
		$this->signature = $builder->getSignature();
	}
	
	public function getEsIndex()
	{
		return $this->esIndex;
	}
	
	public function setEsIndex($esIndex)
	{
		$this->esIndex = $esIndex;
		$this->invalidate();
		return $this;
	}
	
	public function getEsType()
	{
		return $this->esType;
	}
	
	public function setEsType($esType)
	{
		$this->esType = $esType;
		$this->invalidate();
		return $this;
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
			. '"esIndex":"' . $this->esIndex . '",'
			. '"esType":"' . $this->esType . '",'
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
		. $this->esIndex
		. $this->esType
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
			"esIndex":
			{
				"id": "http://jsonschema.net/sonic/searchQuery/esIndex",
				"type": "string"
 			},
 			"esType":
 			{
 				"id": "http://jsonschema.net/sonic/searchQuery/esType",
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
			"esIndex",
			"esType",
			"query",
			"hopCount",
			"datetime",
			"signature"
		]
	}';
}

?>