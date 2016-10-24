<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\Object;
use sgoendoer\Sonic\Model\ProfileObjectBuilder;
use sgoendoer\Sonic\Model\IAccessRestrictableObject;

/**
 * Represents a PROFILE object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ProfileObject extends Object implements IAccessRestrictableObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'profile';
	
	protected $globalID = NULL;
	protected $displayName = NULL;
	protected $params = array();
	
	public function __construct(ProfileObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->setGlobalID($builder->getGlobalID());
		$this->setDisplayName($builder->getDisplayName());
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
				. '"displayName":"' . $this->displayName . '"';
		
		foreach($this->params as $key => $value)
		{
			$json .= ',"' . $key . '":"' . $value . '"';
		}
		
		$json .= '}';
		
		return $json;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/profile",
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
			"displayName":
			{
				"id": "http://jsonschema.net/sonic/profile/displayName",
				"type": "string"
			}
		},
		"required": [
			"objectID",
			"globalID",
			"displayName"
		]
	}';
}

?>