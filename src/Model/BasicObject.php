<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\json\JSONObject;
use geraintluff\jsv4\Jsv4;

/**
 * Represents a basic sonic object
 * version 20160413
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
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
	
	public function getJSONObject()
	{
		return new JSONObject($this->getJSONString());
	}
	
	public function validate()
	{
		$result = Jsv4::validate(json_decode($this->getJSONString()), json_decode(self::SCHEMA));
		
		if($result->valid == true)
			return true;
		else
			throw new \Exception('invalid JSON format for Comment: ' . $result->errors->message);
	}
	
	
}

?>