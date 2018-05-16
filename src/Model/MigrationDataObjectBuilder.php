<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\MigrationDataObject;
use sgoendoer\Sonic\Model\ReferencingRemoteObjectBuilder;
use sgoendoer\Sonic\Model\IllegalModelStateException;
use sgoendoer\Sonic\Model\SignatureObject;

/**
 * Builder class for a MIGRATION DATA object
 * version 20180120
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class MigrationDataObjectBuilder extends ReferencingRemoteObjectBuilder
{
	protected $item						= NULL;
	protected $datetime					= NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$json = json_decode($json);
		
		$signature = SignatureObject::createFromJSON(json_encode($jsonObject->signature));
		
		$itemArray = array();
		
		foreach($jsonObject->item as $i)
		{
			$itemArray[] = ObjectFactory::init(json_encode($i));
		}
		
		$builder = (new MigrationDataObjectBuilder())
				->objectID($json->objectID)
				->targetID($json->targetID)
				->item(json_encode($json->itemArray))
				->datetime($json->datetime)
				->signature($signature);
				
		return $builder->build();
	}
	
	public function getItem()
	{
		return $this->item;
	}
	
	public function item($item)
	{
		$this->item = $item;
		return $this;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function datetime($datetime = NULL)
	{
		if($datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
		else
			$this->datetime = $datetime;
		return $this;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
		
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!UOID::isValid($this->targetID))
			throw new IllegalModelStateException('Invalid targetID');
		
		return new MigrationDataObject($this);
	}
}

?>