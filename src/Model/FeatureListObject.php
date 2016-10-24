<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\RemoteObject;
use sgoendoer\Sonic\Model\FeatureObject;
use sgoendoer\Sonic\Model\FeatureObjectBuilder;

/**
 * Represents a FEATURE LIST object
 * version 20160517
 *
 * author: Markus Beckmann, Senan Sharhan, Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class FeatureListObject extends RemoteObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'featureList';
	
	protected $featureList = array();
	protected $datetime = NULL;
	protected $expires = NULL;
	
	public function __construct(FeatureListObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->featureList = $builder->getFeatureList();
		$this->datetime = $builder->getDatetime();
		$this->expires = $builder->getExpires();
		asort($this->featureList);
		$this->signature = $builder->getSignature();
	}
	
	public function getFeatureList()
	{
		return $this->featureList;
	}
	
	public function setFeatureList($featureList)
	{
		$this->featureList = $featureList;
		$this->invalidate();
		return $this;
	}
	
	public function addFeature($feature)
	{
		$this->featureList = $feature;
		asort($this->featureList);
		$this->invalidate();
		return $this;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function setDatetime($datetime)
	{
		$this->datetime = $datetime;
		$this->invalidate();
		return $this;
	}
	
	public function getExpires()
	{
		return $this->expires;
	}
	
	public function setExpires($expires)
	{
		$this->expires = $expires;
		$this->invalidate();
		return $this;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context": "' . FeatureListObject::JSONLD_CONTEXT . '",'
			. '"@type": "' . FeatureListObject::JSONLD_TYPE . '",'
			. '"objectID": "' . $this->objectID . '",'
			. '"featureList": [';
		
		foreach($this->featureList as $feature)
		{
			$json .= '' . $feature->getJSONString() . '';
			if($feature !== end($this->featureList)) $json .= ',';
		}
		
		$json .= '],'
		. '"expires": "' . $this->expires . '",'
		. '"datetime": "' . $this->datetime . '",';
		
		$json .= '"signature": ' . $this->signature->getJSONString()
			. '}';
		
		return $json;
	}
	
	protected function getStringForSignature()
	{
		$string = $this->objectID;
		asort($this->featureList);
		
		foreach($this->featureList as $feature)
			$string .= $feature->getStringForSignature();
		
		$string .= $this->expires;
		$string .= $this->datetime;
		
		return $string;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/featureList,
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/featureList/objectID",
				"type": "string"
			},
			"featureList":
			{
				"id": "http://jsonschema.net/sonic/featureList/featureList",
				"type": "array",
				"items":{
					"type": "object"
				}
			},
			"datetime":
			{
				"id": "http://jsonschema.net/sonic/featureList/datetime",
				"type": "string"
			},
			"expires":
			{
				"id": "http://jsonschema.net/sonic/featureList/expires",
				"type": "string"
			},
			"signature":
			{
				"id": "http://jsonschema.net/sonic/featureList/signature",
				"type": "object"
			}
		},
		"required": [
			"objectID",
			"featureList",
			"datetime",
			"expires",
			"signature"
		]
	}';
}

?>