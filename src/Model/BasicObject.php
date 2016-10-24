<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\ModelFormatException;
use sgoendoer\json\JSONObject;

/**
 * Represents a basic sonic object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
abstract class BasicObject
{
	public function __construct()
	{}
	
	public abstract function getJSONString();
	
	/**
	 * returns a JSONString for the object
	 * @deprecated
	 */
	public function getJSON()
	{
		return $this->getJSONString();
	}
	
	/**
	 * serializes a model object to it's JSON String representation
	 */
	public function serialize()
	{
		return $this->getJSONString();
	}
	
	/**
	 * deserializes a model object from it's JSON String representation
	 */
	//public abstract static function deserialize();
	
	public function getJSONObject()
	{
		return new JSONObject($this->getJSONString());
	}
	
	public function validate()
	{
		$result = \Jsv4::validate(json_decode($this->getJSONString()), json_decode(static::SCHEMA));
		
		if($result->valid == true)
			return true;
		else
			throw new ModelFormatException('invalid JSON format for ' . get_class($this) . ': ' . $result->errors[0]->dataPath . ": " . $result->errors[0]->message);
	}
}

?>