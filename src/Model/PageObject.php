<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\Object;
use sgoendoer\Sonic\Model\ILikeableObject;
use sgoendoer\Sonic\Model\PageObjectBuilder;
use sgoendoer\Sonic\Model\IAccessRestrictableObject;

/**
 * Represents a PAGE object
 * version 20170428
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class PageObject extends Object implements IAccessRestrictableObject, ILikeableObject
{
	const JSONLD_CONTEXT		= 'http://sonic-project.net/';
	const JSONLD_TYPE			= 'page';
	
	const PAGE_TYPE_PAGE		= 'page';
	const PAGE_TYPE_MOVIE		= 'movie';
	const PAGE_TYPE_BOOK		= 'book';
	const PAGE_TYPE_VENUE		= 'venue';
	const PAGE_TYPE_LOCATION	= 'location';
	const PAGE_TYPE_ARTIST		= 'artist';
	const PAGE_TYPE_BRAND		= 'brand';
	const PAGE_TYPE_PERSON		= 'person';
	const PAGE_TYPE_CITY		= 'city';
	const PAGE_TYPE_BAND		= 'band';
	
	protected $globalID			= NULL;
	protected $owner			= NULL;
	protected $displayName		= NULL;
	protected $pageType			= NULL;
	protected $params			= array();
	
	public function __construct(PageObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->setGlobalID($builder->getGlobalID());
		$this->setOwner($builder->getOwner());
		$this->setDisplayName($builder->getDisplayName());
		$this->setPageType($builder->getPageType());
		$this->setParamArray($builder->getParamArray());
	}
	
	public function setGlobalID($globalID)
	{
		$this->globalID = $globalID;
	}
	
	public function getGlobalID()
	{
		return $this->globalID;
	}
	
	public function setDisplayName($displayName)
	{
		$this->displayName = $displayName;
	}
	
	public function getDisplayName()
	{
		return $this->displayName;
	}
	
	public function setOwner($owner)
	{
		$this->owner = $owner;
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	public function setPageTyoe($pageType)
	{
		$this->pageType = $pageType;
	}
	
	public function getPageType()
	{
		return $this->pageType;
	}
	
	public function setParam($key, $value)
	{
		$this->params[$key] = $value;
	}
	
	public function getParam($key)
	{
		if(array_key_exists($key, $this->param))
			return $this->params[$key];
		else
			return NULL;
	}
	
	public function getParamArray()
	{
		return $this->params;
	}
	
	public function setParamArray($params)
	{
		$this->params = $this->params + $params;
	}
	
	public function getJSONString()
	{
		$json =  '{'
				. '"@context":"' . ProfileObject::JSONLD_CONTEXT . '",'
				. '"@type":"' . ProfileObject::JSONLD_TYPE . '",'
				. '"objectID":"' . $this->objectID . '",'
				. '"globalID":"' . $this->globalID . '",'
				. '"owner":"' . $this->owner . '",'
				. '"displayName":"' . $this->displayName . '",'
				. '"pageType":"' . $this->pageType . '"';
		
		foreach($this->params as $key => $value)
		{
			$json .= ',"' . $key . '":"' . $value . '"';
		}
		
		$json .= '}';
		
		return $json;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/page",
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/profile/objectID",
				"type": "string"
			},
			"globalID":
			{
				"id": "http://jsonschema.net/sonic/profile/globalID",
				"type": "string"
			},
			"owner":
			{
				"id": "http://jsonschema.net/sonic/profile/owner",
				"type": "string"
			},
			"displayName":
			{
				"id": "http://jsonschema.net/sonic/profile/displayName",
				"type": "string"
			},
			"pageType":
			{
				"id": "http://jsonschema.net/sonic/profile/pageType",
				"type": "string"
			}
		},
		"required": [
			"objectID",
			"globalID",
			"owner",
			"displayName",
			"pageType"
		]
	}';
}

?>