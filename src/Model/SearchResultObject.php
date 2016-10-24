<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\SearchResultObjectBuilder;
use sgoendoer\Sonic\Model\ReferencingObject;
use sgoendoer\Sonic\Date\XSDDateTime;

/**
 * Represents a SEARCH RESULT object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class SearchResultObject extends ReferencingObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'search-result';

	protected $resultOwnerGID = NULL;
	protected $resultObjectID = NULL;
	protected $resultIndex = NULL;
	protected $resultType = NULL;
	protected $displayName = NULL;
	protected $datetime = NULL;
	
	public function __construct(SearchResultObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID(), $builder->getTargetID());
		
		$this->resultOwnerGID = $builder->getOwnerGID();
		$this->resultObjectID = $builder->getResultObjectID();
		$this->resultIndex = $builder->getResultIndex();
		$this->resultType = $builder->getResultType();
		$this->displayName = $builder->getDisplayName();
		$this->datetime = $builder->getDatetime();
	}
	
	public function getOwnerGID()
	{
		return $this->resultOwnerGID;
	}
	
	public function setOwnerGID($ownerGID)
	{
		$this->resultOwnerGID = $ownerGID;
		return $this;
	}
	
	public function getResultObjectID()
	{
		return $this->resultObjectID;
	}
	
	public function setResultObjectID($resultObjectID)
	{
		$this->resultObjectID = $resultObjectID;
		return $this;
	}
	
	public function getResultIndex()
	{
		return $this->resultIndex;
	}
	
	public function setResultIndex($resultIndex)
	{
		$this->resultIndex = $resultIndex;
		return $this;
	}
	
	public function getResultType()
	{
		return $this->resultType;
	}
	
	public function setResultType($resultType)
	{
		$this->resultType = $resultType;
		return $this;
	}
	
	public function getDisplayName()
	{
		return $this->displayName;
	}
	
	public function setDisplayName($displayName)
	{
		$this->displayName = $displayName;
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
		return $this;
	}
	
	public function getJSONString()
	{
		$json = '{'
			. '"@context":"' . SearchResultObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . SearchResultObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",'
			. '"targetID":"' . $this->targetID . '",'
			. '"resultOwnerGID":"' . $this->resultOwnerGID . '",'
			. '"resultObjectID":"' . $this->resultObjectID . '",'
			. '"resultIndex":"' . $this->resultIndex . '",'
			. '"resultType":"' . $this->resultType . '",'
			. '"displayName":"' . $this->displayName . '",'
			. '"datetime":"' . $this->datetime . '"'
			. '}';
		
		return $json;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/searchResult",
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/searchResult/objectID",
				"type": "string"
			},
			"targetID":
			{
				"id": "http://jsonschema.net/sonic/searchResult/targetID",
				"type": "string"
			},
			"resultOwnerGID":
			{
				"id": "http://jsonschema.net/sonic/searchResult/resultOwnerGID",
				"type": "string"
			},
			"resultObjectID":
			{
				"id": "http://jsonschema.net/sonic/searchResult/resultObjectID",
				"type": "string"
			},
			"resultIndex":
			{
				"id": "http://jsonschema.net/sonic/searchResult/resultResultIndex",
				"type": "string"
			},
			"resultType":
			{
				"id": "http://jsonschema.net/sonic/searchResult/resultResultType",
				"type": "string"
			},
			"displayName":
			{
				"id": "http://jsonschema.net/sonic/searchResult/displayName",
				"type": "string"
			},
			"datetime":
			{
				"id": "http://jsonschema.net/sonic/searchResult/datetime,
				"type": "string"
			}
		},
		"required": [
			"objectID",
			"targetID",
			"resultOwnerGID",
			"resultObjectID",
			"resultIndex": ",
			"resultType": ",
			"displayName",
			"datetime"
		]
	}';
}

?>