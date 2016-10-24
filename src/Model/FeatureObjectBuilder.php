<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Model\IllegalModelStateException;
use sgoendoer\Sonic\Model\FeatureObject;

/**
 * Builder class for a FEATURE object
 * version 20160517
 *
 * author: Markus Beckmann, Senan Sharhan, Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class FeatureObjectBuilder extends ObjectBuilder
{
	protected $featureNamespace = NULL;
	protected $name = NULL;
	protected $version = NULL;
	protected $compatibilityVersion = NULL;
	protected $apiPath = NULL;
	
	public function __construct()
	{}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		
		$featureObject = new FeatureObject($this);
		
		return $featureObject;
	}

	public static function buildFromJSON($json)
	{
		$json = json_decode($json);
		
		$builder = (new FeatureObjectBuilder())
			->objectID($json->objectID)
			->featureNamespace($json->namespace)
			->name($json->name)
			->version($json->version)
			->compatibilityVersion($json->compatibilityVersion)
			->apiPath($json->apiPath);
		
		return $builder->build();
	}
	
	public function getFeatureNamespace()
	{
		return $this->featureNamespace;
	}
	
	public function featureNamespace($namespace)
	{
		$this->featureNamespace = $namespace;
		return $this;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function name($name)
	{
		$this->name = $name;
		return $this;
	}
	
	public function getVersion()
	{
		return $this->version;
	}
	
	public function version($version)
	{
		$this->version = $version;
		return $this;
	}
	
	public function getCompatibilityVersion()
	{
		return $this->compatibilityVersion;
	}
	
	public function compatibilityVersion($compatibilityVersion)
	{
		$this->compatibilityVersion = $compatibilityVersion;
		return $this;
	}
	
	public function getApiPath()
	{
		return $this->apiPath;
	}
	
	public function apiPath($apiPath)
	{
		$this->apiPath = $apiPath;
		return $this;
	}
}

?>