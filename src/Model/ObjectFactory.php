<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\CommentObjectBuilder;
use sgoendoer\Sonic\Model\PersonObjectBuilder;
use sgoendoer\Sonic\Model\ProfileObjectBuilder;
use sgoendoer\Sonic\Model\ConversationObjectBuilder;
use sgoendoer\Sonic\Model\ConversationStatusObjectBuilder;
use sgoendoer\Sonic\Model\ConversationStatusObject;
use sgoendoer\Sonic\Model\ConversationMessageObjectBuilder;
use sgoendoer\Sonic\Model\ConversationMessageStatusObjectBuilder;
use sgoendoer\Sonic\Model\ConversationMessageStatusObject;
use sgoendoer\Sonic\Model\LikeObjectBuilder;
use sgoendoer\Sonic\Model\LinkObjectBuilder;
use sgoendoer\Sonic\Model\LinkRequestObjectBuilder;
use sgoendoer\Sonic\Model\LinkResponseObjectBuilder;
use sgoendoer\Sonic\Model\LinkRosterObjectBuilder;
use sgoendoer\Sonic\Model\StreamItemObjectBuilder;
use sgoendoer\Sonic\Model\TagObjectBuilder;
use sgoendoer\Sonic\Model\SearchQueryObjectBuilder;
use sgoendoer\Sonic\Model\SearchResultObjectBuilder;
use sgoendoer\Sonic\Model\SearchResultCollectionObjectBuilder;

use sgoendoer\Sonic\Model\ModelFormatException;
use sgoendoer\json\JSONObject;

/**
 * Creates an instance of a JSON formatted Sonic object
 * version 20170613
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
				
				case "image":
					$object = ImageObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "profile":
					$object = ProfileObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "conversation":
					$object = ConversationObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "conversation-status":
					$object = ConversationStatusObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "conversation-message":
					$object = ConversationMessageObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "conversation-message-status":
					$object = ConversationMessageStatusObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "accesscontrolgroup":
					$object = AccessControlGroupObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "accesscontrolrule":
					$object = AccessControlRuleObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "link":
					$object = LinkObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "link-request":
					$object = LinkRequesObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "link-response":
					$object = LinkResponseObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "response":
					$object = ResponseObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "search-query":
					$object = SearchQueryObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "search-result":
					$object = SearchResultObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "search-result-collection":
					$object = SearchResultCollectionObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				//case "signature":
					//$object = SignaObjectBuilder::buildFromJSON($json->__toString());
				//break;
				
				// feature
				// migration
				
				case "stream-item":
					$object = StreamItemObjectBuilder::buildFromJSON($json->__toString());
				break;
				
				case "tag":
					$object = TagObjectBuilder::buildFromJSON($json->__toString());
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