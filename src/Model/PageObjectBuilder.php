<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Model\PageObject;
use sgoendoer\Sonic\Model\ObjectBuilder;
use sgoendoer\Sonic\Model\IllegalModelStateException;

/**
 * Builder class for a PROFILE object
 * version 20151020
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class PageObjectBuilder extends ObjectBuilder
{
	protected $globalID = NULL;
	protected $displayName = NULL;
	protected $owner = NULL;
	protected $pageType = NULL;
	protected $params = array();
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$builder = new PageObjectBuilder();
		$builder->objectID($jsonObject->objectID)
				->globalID($jsonObject->globalID)
				->displayName($jsonObject->displayName)
				->pageType($jsonObject->pageType);
				
		foreach(json_decode($json, true) as $key => $value)
		{
			if($key != 'objectID' && $key != 'globalID' && $key != 'displayName' && $key != 'owner' && $key != 'pageType' && $key != '@context' && $key != '@type')
			{
				$builder->param($key, $value);
			}
		}
		
		return $builder->build();
	}
	
	public function globalID($globalID)
	{
		$this->globalID = $globalID;
		return $this;
	}
	
	public function getGlobalID()
	{
		return $this->globalID;
	}
	
	public function displayName($displayName)
	{
		$this->displayName = $displayName;
		return $this;
	}
	
	public function getDisplayName()
	{
		return $this->displayName;
	}
	
	public function owner($owner)
	{
		$this->owner = $owner;
		return $this;
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	public function pageType($pageType)
	{
		$this->pageType = $pageType;
		return $this;
	}
	
	public function getPageType()
	{
		return $this->pageType;
	}
	
	public function param($key, $value)
	{
		$this->params[$key] = $value;
		return $this;
	}
	
	public function getParam($key)
	{
		if(array_key_exists($key, $this->param))
			return $this->params[$key];
		else
			return NULL;
	}
	
	public function paramArray($params)
	{
		$this->params = $this->params + $params;
		return $this;
	}
	
	public function getParamArray()
	{
		return $this->params;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
			
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!GID::isValid($this->globalID))
			throw new IllegalModelStateException('Invalid globalID');
		if($this->displayName == '' || $this->displayName == NULL)
			throw new IllegalModelStateException('Invalid displayName');
		if(!GID::isValid($this->owner))
			throw new IllegalModelStateException('Invalid owner');
		if($this->pageType == '' || $this->pageType == NULL)
			throw new IllegalModelStateException('Invalid pageType');
		
		return new PageObject($this);
	}
}

?>