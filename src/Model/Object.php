<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\BasicObject;

/**
 * Represents a base sonic object
 * version 20150901
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
abstract class Object extends BasicObject
{
	protected $objectID = NULL;
	
	public function __construct($objectID)
	{
		$this->objectID = $objectID;
	}
	
	public function getObjectID()
	{
		return $this->objectID;
	}
}

?>