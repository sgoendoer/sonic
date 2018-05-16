<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\MigrationObject;
use sgoendoer\Sonic\Model\ObjectBuilder;
use sgoendoer\Sonic\Model\IllegalModelStateException;

/**
 * Builder class for a MIGRATION object
 * version 20180120
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class MigrationObjectBuilder extends ObjectBuilder
{
	protected $migrationSource			= NULL;
	protected $migrationTarget			= NULL;
	protected $datetime					= NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$json = json_decode($json);
		
		$builder = new MigrationObjectBuilder();
		$builder->objectID($json->objectID)
				->migrationSource($json->migrationSource)
				->migrationTarget($json->migrationTarget)
				->datetime($json->datetime);
				
		return $builder->build();
	}
	
	public function getMigrationSource()
	{
		return $this->migrationSource;
	}
	
	public function migrationSource($migrationSource)
	{
		$this->migrationSource = $migrationSource;
		return $this;
	}
	
	public function getMigrationTarget()
	{
		return $this->migrationTarget;
	}
	
	public function migrationTarget($migrationTarget)
	{
		$this->migrationTarget = $migrationTarget;
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
		if(!GID::isValid($this->globalID))
			throw new IllegalModelStateException('Invalid globalID');
		
		if($this->migrationSource == NULL || !GID::isValid($this->migrationSource))
			throw new IllegalModelStateException('Invalid migrationSource');
		if($this->migrationTarget == NULL || !GID::isValid($this->migrationTarget))
			throw new IllegalModelStateException('Invalid migrationTarget');
		
		return new MigrationObject($this);
	}
}

?>