<?php namespace sgoendoer\Sonic\Identity;

use sgoendoer\Sonic\Crypt\KeyPair;
use sgoendoer\Sonic\Crypt\PublicKey;
use sgoendoer\Sonic\Crypt\Random;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Identity\SocialRecord;
use sgoendoer\Sonic\Identity\KeyRevocationCertificateBuilder;
use sgoendoer\Sonic\Identity\SocialRecordFormatException;

/**
 * SocialRecordBuilder
 * @version 20160513
 *
 * @author Sebastian Goendoer
 * @copyright Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class SocialRecordBuilder
{
	private $type				= NULL;
	private $globalID			= NULL;	// global id
	private $platformGID		= NULL;
	private $displayName		= NULL;	// human readable name
	private $profileLocation	= NULL;	// URL
	private $personalPublicKey	= NULL; // PEM PHP compatible format!!!
	private $accountPublicKey	= NULL; // PEM PHP compatible format!!!
	private $salt				= NULL;	// length MUST be 8 chars
	private $datetime			= NULL;	// XSD datetime format e.g. 2015-01-01T11:11:11Z
	private $active				= NULL;
	private $keyRevocationList	= NULL;
	
	public function __construct()
	{}
	
	/**
	 * Creates a SocialRecord object from a JSON String
	 * 
	 * @param $json (String) The serialized SocialRecord
	 * 
	 * @return SocialRecord
	 */
	public static function buildFromJSON($json)
	{
		$jsonObject = json_decode($json);
		
		if(!property_exists($jsonObject, 'platformGID'))
			throw new SocialRecordFormatException('SocialRecord: Property platformGID missing!');
		if(!property_exists($jsonObject, 'globalID'))
			throw new SocialRecordFormatException('SocialRecord: Property globalID missing!');
		if(!property_exists($jsonObject, 'type'))
			throw new SocialRecordFormatException('SocialRecord: Property type missing!');
		if(!property_exists($jsonObject, 'displayName'))
			throw new SocialRecordFormatException('SocialRecord: Property displayName missing!');
		if(!property_exists($jsonObject, 'profileLocation'))
			throw new SocialRecordFormatException('SocialRecord: Property profileLocation missing!');
		if(!property_exists($jsonObject, 'personalPublicKey'))
			throw new SocialRecordFormatException('SocialRecord: Property personalPublicKey missing!');
		if(!property_exists($jsonObject, 'accountPublicKey'))
			throw new SocialRecordFormatException('SocialRecord: Property accountPublicKey missing!');
		if(!property_exists($jsonObject, 'salt'))
			throw new SocialRecordFormatException('SocialRecord: Property salt missing!');
		if(!property_exists($jsonObject, 'datetime'))
			throw new SocialRecordFormatException('SocialRecord: Property datetime missing!');
		if(!property_exists($jsonObject, 'active'))
			throw new SocialRecordFormatException('SocialRecord: Property active missing!');
		if(!property_exists($jsonObject, 'keyRevocationList'))
			throw new SocialRecordFormatException('SocialRecord: Property keyRevocationList missing!');
		
		$krl = array();
		
		foreach($jsonObject->keyRevocationList as $krc)
		{
			$krl[] = KeyRevocationCertificateBuilder::buildFromJSON($krc);
		}
		
		return (new SocialRecordBuilder())
				->type($jsonObject->type)
				->globalID($jsonObject->globalID)
				->platformGID($jsonObject->platformGID)
				->displayName($jsonObject->displayName)
				->profileLocation($jsonObject->profileLocation)
				->personalPublicKey(PublicKey::formatPEM($jsonObject->personalPublicKey))
				->accountPublicKey(PublicKey::formatPEM($jsonObject->accountPublicKey))
				->salt($jsonObject->salt)
				->datetime($jsonObject->datetime)
				->active($jsonObject->active)
				->keyRevocationList($krl)
				->build();
	}
	
	/**
	 * Set the type
	 * 
	 * @param $type (String) The type of the SocialRecord
	 * 
	 * @return SocialRecordBuilder
	 */
	public function type($type)
	{
		$this->type = $type;
		
		return $this;
	}
	
	/**
	 * Set the GlobalID
	 * 
	 * @param $gid (String) The GlobalID of the SocialRecord
	 * 
	 * @return SocialRecordBuilder
	 */
	public function globalID($gid)
	{
		$this->globalID = $gid;
		
		return $this;
	}
	
	/**
	 * Set the platformGID
	 * 
	 * @param $pid (String) The PlatformGID of the SocialRecord
	 * 
	 * @return SocialRecordBuilder
	 */
	public function platformGID($pid)
	{
		$this->platformGID = $pid;
		
		return $this;
	}
	
	/**
	 * Set the display name
	 * 
	 * @param $displayName (String) The display name of the SocialRecord
	 * 
	 * @return SocialRecordBuilder
	 */
	public function displayName($displayName)
	{
		$this->displayName = $displayName;
		
		return $this;
	}
	
	/**
	 * Set the profile location
	 * 
	 * @param $profileLocation (String) The profile location of the SocialRecord
	 * 
	 * @return SocialRecordBuilder
	 */
	public function profileLocation($profileLocation)
	{
		$this->profileLocation = $profileLocation;
		
		return $this;
	}
	
	/**
	 * Set the personalPublicKey
	 * 
	 * @param $personalPublicKey (String) The personalPublicKey of the SocialRecord
	 * 
	 * @return SocialRecordBuilder
	 */
	public function personalPublicKey($personalPublicKey)
	{
		$this->personalPublicKey = $personalPublicKey;
		
		return $this;
	}
	
	/**
	 * Set the accountPublicKey
	 * 
	 * @param $accountPublicKey (String) The accountPublicKey of the SocialRecord
	 * 
	 * @return SocialRecordBuilder
	 */
	public function accountPublicKey($accountPublicKey)
	{
		$this->accountPublicKey = $accountPublicKey;
		
		return $this;
	}
	
	/**
	 * Set the salt
	 * 
	 * @param $salt (String) The salt of the SocialRecord
	 * 
	 * @return SocialRecordBuilder
	 */
	public function salt($salt)
	{
		$this->salt = $salt;
		
		return $this;
	}
	
	/**
	 * Set the active flag
	 * 
	 * @param $active (Integer) The active status of the SocialRecord
	 * 
	 * @return SocialRecordBuilder
	 */
	public function active($active)
	{
		$this->active = $active;
		
		return $this;
	}
	
	/**
	 * Set the key revocation list
	 * 
	 * @param $keyRevocationList (String) The key revocation list of the SocialRecord
	 * 
	 * @return SocialRecordBuilder
	 */
	public function keyRevocationList($krl = NULL)
	{
		if($krl == NULL)
			$this->keyRevocationList = array();
		else
			$this->keyRevocationList = $krl;
		
		return $this;
	}
	
	/**
	 * Set the datetime
	 * 
	 * @param $datetime (String) The datetime of the SocialRecord
	 * 
	 * @return SocialRecordBuilder
	 */
	public function datetime($date)
	{
		$this->datetime = $date;
		
		return $this;
	}
	
	/**
	 * Get the value for the parameter type
	 * 
	 * @return The type (String)
	 */
	public function getType()
	{
		return $this->type;
	}
	
	/**
	 * Get the value for the parameter globalID
	 * 
	 * @return The globalID (String)
	 */
	public function getGlobalID()
	{
		return $this->globalID;
	}
	
	/**
	 * Get the value for the parameter PlatformGID
	 * 
	 * @return The platformGID (String)
	 */
	public function getPlatformGID()
	{
		return $this->platformGID;
	}
	
	/**
	 * Get the value for the parameter displayName
	 * 
	 * @return The displayName (String)
	 */
	public function getDisplayName()
	{
		return $this->displayName;
	}
	
	/**
	 * Get the value for the parameter profileLocation
	 * 
	 * @return The profileLocation (String)
	 */
	public function getProfileLocation()
	{
		return $this->profileLocation;
	}
	
	/**
	 * Get the value for the parameter personalPublicKey
	 * 
	 * @return The personalPublicKey (String)
	 */
	public function getPersonalPublicKey()
	{
		return $this->personalPublicKey;
	}
	
	/**
	 * Get the value for the parameter accountPublicKey
	 * 
	 * @return The accountPublicKey (String)
	 */
	public function getAccountPublicKey()
	{
		return $this->accountPublicKey;
	}
	
	/**
	 * Get the value for the parameter salt
	 * 
	 * @return The salt (String)
	 */
	public function getSalt()
	{
		return $this->salt;
	}
	
	/**
	 * Get the value for the parameter datetime
	 * 
	 * @return The datetime (String)
	 */
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	/**
	 * Get the value for the parameter active
	 * 
	 * @return The active flag (Integer)
	 */
	public function getActive()
	{
		return $this->active;
	}
	
	/**
	 * Get the value for the parameter keyRevocationList
	 * 
	 * @return The keyRevocationList (array)
	 */
	public function getKeyRevocationList()
	{
		return $this->keyRevocationList;
	}
	
	/**
	 * Builder method that creates the actual SocialRecord object
	 * 
	 * @throws SocialRecordFormatException
	 * 
	 * @return The SocialRecord (SocialRecord)
	 */
	public function build()
	{
		if($this->displayName == NULL)
			throw new SocialRecordFormatException('SocialRecord: displayName must be specified for instantiation');
		if($this->profileLocation == NULL)
			throw new SocialRecordFormatException('SocialRecord: profileLocation must be specified for instantiation');
		if($this->personalPublicKey == NULL)
			throw new SocialRecordFormatException('SocialRecord: personalPublicKey must be specified for instantiation');
		if($this->accountPublicKey == NULL)
			throw new SocialRecordFormatException('SocialRecord: accountPublicKey must be specified for instantiation');
		if($this->type == NULL)
			throw new SocialRecordFormatException('SocialRecord: type must be specified for instantiation');
		
		if($this->type != SocialRecord::TYPE_PLATFORM && $this->type != SocialRecord::TYPE_USER)
			throw new SocialRecordFormatException('SocialRecord: Invalid type value [' . $this->type . ']');
		
		if($this->salt == NULL)
			$this->salt = Random::getRandom(SocialRecord::SALT_CHARS);
		if($this->datetime == NULL)
			$this->datetime = XSDDateTime::getXSDDateTime();
		if($this->globalID == NULL)
			$this->globalID = GID::createGID($this->personalPublicKey, $this->salt);
		if(!GID::isValid($this->globalID))
			throw new SocialRecordFormatException('SocialRecord: Invalid globalID value [' . $this->globalID . ']');
			
		if($this->platformGID == NULL && $this->type == SocialRecord::TYPE_PLATFORM)
			$this->platformGID = $this->globalID;
		if($this->platformGID == NULL)
			throw new SocialRecordFormatException('SocialRecord: platformID must be specified for instantiation');
		if(!GID::isValid($this->platformGID))
			throw new SocialRecordFormatException('SocialRecord: Invalid platformGID value [' . $this->platformGID . ']');
		
		if($this->keyRevocationList == NULL)
			$this->keyRevocationList = array();
		if($this->active == NULL)
			$this->active = 1;
		
		return new SocialRecord($this);
	}
}

?>