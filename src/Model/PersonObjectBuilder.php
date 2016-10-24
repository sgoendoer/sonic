<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\PersonObject;
use sgoendoer\Sonic\Model\ObjectBuilder;
use sgoendoer\Sonic\Model\IllegalModelStateException;

/**
 * Builder class for a PERSON object
 * version 20151020
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class PersonObjectBuilder extends ObjectBuilder
{
	protected $globalID					= NULL;
	protected $displayName				= NULL;
	protected $profilePictureThumbnail	= NULL;
	protected $profilePicture			= NULL;
	protected $language					= NULL;
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$json = json_decode($json);
		
		$builder = new PersonObjectBuilder();
		$builder->objectID($json->objectID)
				->globalID($json->globalID)
				->displayName($json->displayName);
				
		if(property_exists($json, 'profilePicture'))
			$builder->profilePicture($json->profilePicture);
			
		if(property_exists($json, 'profilePictureThumbnail'))
			$builder->profilePictureThumbnail($json->profilePictureThumbnail);
		
		if(property_exists($json, 'language'))
			$builder->language($json->language);
		
		return $builder->build();
	}
	
	public function globalID($globalID)
	{
		$this->globalID = $globalID;
		return $this;
	}
	
	public function displayName($displayName)
	{
		$this->displayName = $displayName;
		return $this;
	}
	
	public function profilePicture($profilePicture)
	{
		$this->profilePicture = $profilePicture;
		return $this;
	}
	
	public function profilePictureThumbnail($profilePictureThumbnail)
	{
		$this->profilePictureThumbnail = $profilePictureThumbnail;
		return $this;
	}
	
	public function language($language)
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
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
			
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!GID::isValid($this->globalID))
			throw new IllegalModelStateException('Invalid globalID');
		
		return new PersonObject($this);
	}
}

?>