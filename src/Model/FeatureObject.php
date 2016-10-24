<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\Object;
use sgoendoer\Sonic\Model\FeatureObjectBuilder;

/**
 * Represents a FEATURE object
 * version 20160517
 *
 * author: Markus Beckmann, Senan Sharhan, Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class FeatureObject extends Object
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'feature';
	
	protected $namespace = NULL;
	protected $name = NULL;
	protected $version = NULL;
	protected $compatibilityVersion = NULL;
	protected $apiPath = NULL;
	
	public function __construct(FeatureObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->namespace = $builder->getFeatureNamespace();
		$this->name = $builder->getName();
		$this->version = $builder->getVersion();
		$this->compatibilityVersion = $builder->getCompatibilityVersion();
		$this->apiPath = $builder->getApiPath();
	}
	
	public function getNamespace()
	{
		return $this->namespace;
	}
	
	public function setNamespace($namespace)
	{
		$this->namespace = $namespace;
		return $this;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}
	
	public function getVersion()
	{
		return $this->version;
	}
	
	public function setVersion($version)
	{
		$this->version = $version;
		return $this;
	}
	
	public function getCompatibilityVersion()
	{
		return $this->compatibility_version;
	}
	
	public function setCompatibilityVersion($compatibility_version)
	{
		$this->compatibility_version = $compatibility_version;
		return $this;
	}
	
	public function getApiPath()
	{
		return $this->apiPath;
	}
	
	public function setApiPath($api_path)
	{
		$this->apiPath = $apiPath;
		return $this;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context": "' . FeatureObject::JSONLD_CONTEXT . '",'
			. '"@type": "' . FeatureObject::JSONLD_TYPE . '",'
			. '"objectID": "' . $this->objectID . '",'
			. '"namespace": "' . $this->namespace . '",'
			. '"name": "' . $this->name . '",'
			. '"version": "' . $this->version . '",'
			. '"compatibilityVersion": "' . $this->compatibilityVersion . '",'
			. '"apiPath": "' . $this->apiPath . '"'
			. '}';
		return $json;
	}
	
	public function getStringForSignature()
	{
		return $this->objectID
		. $this->namespace
		. $this->name
		. $this->version
		. $this->compatibilityVersion
		. $this->api_path;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/feature,
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/feature/objectID",
				"type": "string"
			},
			"namespace":
			{
				"id": "http://jsonschema.net/feature/namespace",
				"type": "string"
			},
			"name":
			{
				"id": "http://jsonschema.net/feature/name",
				"type": "string"
			},
			"version":
			{
				"id": "http://jsonschema.net/feature/version",
				"type": "string"
			},
			"compatibilityVersion":
			{
				"id": "http://jsonschema.net/feature/compatibilityVersion",
				"type": "string"
			},
			"apiPath":
			{
				"id": "http://jsonschema.net/feature/apiPath",
				"type": "string"
			}
		},
		"required": [
			"objectID",
			"namespace",
			"name",
			"version",
			"compatibilityVersion",
			"apiPath"
		]
	}';
}

?>