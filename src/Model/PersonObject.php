<?php namespace sgoendoer\Sonic\Model;

// require_once(__DIR__ . '/../Object.abstract.class.php');

/**
 * Represents a PROFILE object
 * version 20151020
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class PersonObject extends Object
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'person';
	
	protected $globalID					= NULL;
	protected $displayName				= NULL;
	protected $profilePictureThumbnail	= NULL;
	protected $profilePicture			= NULL;
	protected $language					= NULL;
	
	public function __construct(PersonObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->globalID = $builder->getGlobalID();
		$this->displayName = $builder->getDisplayName();
		$this->profilePictureThumbnail = $builder->getProfilePictureThumbnail();
		$this->profilePicture = $builder->getProfilePicture();
		$this->language = $builder->getLanguage();
	}
	
	public function setGlobalID($globalID)
	{
		$this->globalID = $globalID;
		return $this;
	}
	
	public function setDisplayName($displayName)
	{
		$this->displayName = $displayName;
		return $this;
	}
	
	public function setProfilePicture($pic)
	{
		$this->profilePicture = $pic;
		return $this;
	}
	
	public function setProfilePictureThumbnail($pic)
	{
		$this->profilePictureThumbnail = $pic;
		return $this;
	}
	
	public function setLanguage($language)
	{
		$this->language = $language;
		return $this;
	}
	
	public function getGlobalID()
	{
		return $this->globalID;
	}
	
	public function getDisplayName()
	{
		return $this->displayName;
	}
	
	public function getProfilePicture()
	{
		return $this->profilePicture;
	}
	
	public function getProfilePictureThumbnail()
	{
		return $this->profilePictureThumbnail;
	}
	
	public function getLanguage()
	{
		return $this->language;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context": "' . PersonObject::JSONLD_CONTEXT . '",'
			. '"@type": "' . PersonObject::JSONLD_TYPE . '",'
			. '"objectID": "' . $this->objectID . '",' 
			. '"globalID": "' . $this->globalID . '",' 
			. '"displayName": "' . $this->displayName . '"';
		
		if($this->profilePictureThumbnail != NULL) $json .= ', "profilePictureThumbnail": "' . $this->profilePictureThumbnail . '"';
		if($this->profilePicture != NULL) $json .= ', "profilePicture": "' . $this->profilePicture . '"';
		
		$json .= '}';
		
		return $json;
	}
	
	public static function validateJSON($json)
	{
		$result = \Jsv4::validate(json_decode($json), json_decode(PersonObject::SCHEMA));
		
		if($result->valid == true)
			return true;
		else
			throw new \Exception('invalid JSON format for person: ' . $result->errors->message);
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/person,
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/person/objectID",
				"type": "string"
			},
			"globalID":
			{
				"id": "http://jsonschema.net/sonic/person/globalID",
				"type": "string"
			},
			"displayName":
			{
				"id": "http://jsonschema.net/sonic/person/displayName",
				"type": "string"
			},
			"profilePictureThumbnail":
			{
				"id":"http://jsonschema.net/sonic/person/profilePictureThumbnail",
				"type":"string"
			},
			"profilePicture":
			{
				"id":"http://jsonschema.net/sonic/person/profilePicture",
				"type":"string"
			}
		},
		"required": [
			"objectID",
			"globalID",
			"displayName"
		]
	}';
}

?>