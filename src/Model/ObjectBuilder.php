<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\BasicObjectBuilder;

/**
 * Abstract class ObjectBuilder
 * version 20151020
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
abstract class ObjectBuilder extends BasicObjectBuilder
{
	protected $objectID = NULL;
	
	public function __construct()
	{}
		
	public function objectID($objectID)
	{
		$this->objectID = $objectID;
		return $this;
	}
	
	public function getObjectID()
	{
		return $this->objectID;
	}
}

?>