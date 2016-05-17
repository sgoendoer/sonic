<?php
namespace sgoendoer\Sonic\Model;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\Object;
use sgoendoer\Sonic\Model\FeatureObjectBuilder;

class FeatureObject extends Object
{

    const JSONLD_CONTEXT = 'http://sonic-project.net/';
    const JSONLD_TYPE = 'feature';


    protected $namespace			            = NULL;
    protected $name     			            = NULL;
    protected $version  			            = NULL;
    protected $compatibility_version			= NULL;
    protected $api_path             			= NULL;
    

    public function __construct(FeatureObjectBuilder $builder)
    {

        parent::__construct($builder->getObjectID());

        $this->namespace = $builder->getFeatureNamespace();
        $this->name = $builder->getName();
        $this->version = $builder->getVersion();
        $this->compatibility_version = $builder->getCompatibilityVersion();
        $this->api_path = $builder->getApiPath();

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
        return $this->api_path;
    }

    public function setApiPath($api_path)
    {
        $this->api_path = $api_path;
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
            . '"compatibility_version": "' . $this->compatibility_version . '",'
            . '"api_path": "' . $this->api_path . '"'
            . '}';
        return $json;
    }

    public function getStringForSignature()
    {
        return $this->objectID
        . $this->namespace
        . $this->name
        . $this->version
        . $this->compatibility_version
        . $this->api_path;
    }

    public static function validateJSON($json)
    {
        $result = \Jsv4::validate(json_decode($json), json_decode(FeatureObject::SCHEMA));

        if($result->valid == true)
            return true;
        else
            throw new \Exception('invalid JSON format for Feature: ' . $result->errors->message);
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
			"compatibility_version":
			{
				"id": "http://jsonschema.net/feature/compatibility_version",
				"type": "string"
			},
			"api_path":
			{
				"id": "http://jsonschema.net/feature/api_path",
				"type": "string"
			}
		},
		"required": [
			"objectID",
			"namespace",
			"name",
			"version",
			"compatibility_version",
			"api_path"
		]
	}';
}