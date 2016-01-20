<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\json\JSONObject;

/**
 * Represents a basic sonic object
 * version 20150901
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
abstract class BasicObject
{
	public function __construct()
	{}
	
	public abstract function getJSONString();
	
	// for historical reasons ;)
	public function getJSON()
	{
		return $this->getJSONString();
	}
	
	public function getJSONObject()
	{
		return new JSONObject($this->getJSONString());
	}
	
	// TODO public abstract function validate();
}

?>