<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Model\SignatureObject;
use sgoendoer\Sonic\Model\RemoteObjectBuilder;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\IllegalModelStateException;
use sgoendoer\Sonic\Model\FeatureObject;
use sgoendoer\Sonic\Model\FeatureObjectBuilder;
use sgoendoer\Sonic\Model\FeatureListObject;

/**
 * Builder class for a FEATURE LIST object
 * version 20160517
 *
 * author: Markus Beckmann, Senan Sharhan, Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class FeatureListObjectBuilder extends RemoteObjectBuilder
{
	protected $featureList = array();
	protected $datetime = NULL;
	protected $expires = NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		$jsonObject = json_decode($json);
		
		$signature = SignatureObject::createFromJSON(json_encode($jsonObject->signature));
		
		$featureArray = array();
		
		foreach($jsonObject->featureList as $feature)
		{
			$featureArray[] = FeatureObjectBuilder::buildFromJSON(json_encode($feature));
		}
		
		$builder = (new FeatureListObjectBuilder())
			->objectID($jsonObject->objectID)
			->featureList($featureArray)
			->datetime($jsonObject->datetime)
			->expires($jsonObject->expires)
			->signature($signature);
		
		return $builder->build();
	}
	
	public function getFeatureList()
	{
		return $this->featureList;
	}
	
	public function featureList($featureList)
	{
		$this->featureList = $featureList;
		asort($this->featureList);
		return $this;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function datetime($datetime)
	{
		$this->datetime = $datetime;
		return $this;
	}
	
	public function getExpires()
	{
		return $this->expires;
	}
	
	public function expires($expires)
	{
		$this->expires = $expires;
		return $this;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		if($this->featureList == NULL)
			$this->featureList = array();
		if($this->datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
		if($this->expires == NULL)
			$this->expires = $this->datetime;
		
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		
		$featureList = new FeatureListObject($this);
		
		if($this->signature == NULL)
			$featureList->signObject();
		
		if(!$featureList->verifyObjectSignature())
			throw new IllegalModelStateException('Invalid signature');
		
		return $featureList;
	}
}

?>