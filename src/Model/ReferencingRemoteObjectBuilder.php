<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\RemoteObjectBuilder;

/**
 * builder class for referencing remote sonic objects
 * version 20151014
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
abstract class ReferencingRemoteObjectBuilder extends RemoteObjectBuilder
{
	protected $targetID = NULL;
	
	public function __construct()
	{
		parent::__construct();
	}
	
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