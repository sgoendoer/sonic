<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\ObjectBuilder;

/**
 * Abstract class Object Builder
 * version 20151014
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
abstract class ReferencingObjectBuilder extends ObjectBuilder
{
	protected $targetID = NULL;
	
	public function __construct()
	{}
	
	public function targetID($targetID)
	{
		$this->targetID = $targetID;
		return $this;
	}
	
	public function getTargetID()
	{
		return $this->targetID;
	}
}

?>