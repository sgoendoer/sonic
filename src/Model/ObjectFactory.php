<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\CommentObjectBuilder;
use sgoendoer\Sonic\Model\LikeObjectBuilder;
use sgoendoer\Sonic\Model\PersonObjectBuilder;
use sgoendoer\Sonic\Model\ModelFormatException;
use sgoendoer\json\JSONObject;

/**
 * Creates an instance of a JSON formatted Sonic object
 * version 20170220
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ObjectFactory
{
	private function __construct()
	{}
	
	
	// TODO add support for feature extentions
	// TODO add support for different versions of sonic/models
	
	/**
	 * Parses the JSONObject and creates a matching Sonic object
	 * 
	 * @param JSONObbject $json The JSONObject to parse
	 * @return BasicObject
	 * @throws ModelFormatException on malformed object content
	 */
	public static function init(JSONObject $json)
	{
		$object = NULL;
		
		if(!$json->has("@type"))
			throw new ModelFormatException("Not a valid object");
		try
		{
			switch(strtolower($json->get("@type")))
			{
				case "person":
					$object = PersonObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "comment":
					$object = CommentObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "like":
					$object = LikeObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				default:
					throw new ModelFormatException("Not a valid object type");
				break;
			}
		}
		catch(Exception $e)
		{
			throw new ModelFormatException("Could not init object for " . strtolower($json->get("@type")) . ": " . $e->getMessage());
		}
		
		return $object;
	}
}

?>