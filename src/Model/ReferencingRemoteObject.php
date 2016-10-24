<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\RemoteObject;

/**
 * Represents a referencing remote sonic object
 * version 20150901
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
abstract class ReferencingRemoteObject extends RemoteObject
{
	protected $targetID = NULL;
	
	public function __construct($objectID, $targetID)
	{
		parent::__construct($objectID);
		
		$this->targetID = $targetID;
	}
	
	public function setTargetID($targetID)
	{
		$this->targetID = $targetID;
	}
	
	public function getTargetID()
	{
		return $this->targetID;
	}
}

?>