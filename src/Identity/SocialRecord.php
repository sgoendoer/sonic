<?php namespace sgoendoer\Sonic\Identity;

use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Crypt\PublicKey;

use sgoendoer\json\JSONObject;

/**
 * SocialRecord class
 * @version 20160513
 *
 * @author Sebastian Goendoer
 * @copyright Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class SocialRecord
{
	const JSONLD_CONTEXT		= 'http://sonic-project.net/';
	const JSONLD_TYPE			= 'socialrecord';
	
	const TYPE_PLATFORM			= 'platform';
	const TYPE_USER				= 'user';
	
	const SALT_CHARS			= 8;
	
	private $type				= NULL;
	private $globalID			= NULL;	// global id
	private $platformGID		= NULL;
	private $displayName		= NULL;	// human readable name
	private $profileLocation	= NULL;	// URL
	private $personalPublicKey	= NULL; // PEM PHP compatible string format!!!
	private $accountPublicKey	= NULL; // PEM PHP compatible string format!!!
	private $salt				= NULL;	// length MUST be 8 chars
	private $datetime			= NULL;	// XSD datetime format e.g. 2015-01-01T11:11:11Z
	private $active				= NULL;
	private $keyRevocationList	= array();
	
	public function __construct(SocialRecordBuilder $builder)
	{
		$this->setType($builder->getType());
		$this->setGlobalID($builder->getGlobalID());
		$this->setPlatformGID($builder->getPlatformGID());
		$this->setDisplayName($builder->getDisplayName());
		$this->setProfileLocation($builder->getProfileLocation());
		$this->setPersonalPublicKey(PublicKey::formatPEM($builder->getPersonalPublicKey()));
		$this->setAccountPublicKey(PublicKey::formatPEM($builder->getAccountPublicKey()));
		$this->setSalt($builder->getSalt());
		$this->setDatetime($builder->getDatetime());
		$this->setActive($builder->getActive());
		$this->setKeyRevocationList($builder->getKeyRevocationList());
	}
	
	/**
	 * Serialization method for SocialRecord
	 * 
	 * @return The serialized SocialRecord (String)
	 */
	public function __toString()
	{
		return $this->getJSONString();
	}
	
	/**
	 * Serialization method for SocialRecord
	 * 
	 * @return The serialized SocialRecord (String)
	 */
	public function getJSONString()
	{
		$json = '{'
				. '"@context":"' .			SocialRecord::JSONLD_CONTEXT . '",'
				. '"@type":"' .				SocialRecord::JSONLD_TYPE . '",'
				. '"type":"' .				$this->type . '",'
				. '"globalID":"' .			$this->globalID . '",'
				. '"platformGID":"' .		$this->platformGID . '",'
				. '"displayName":"' .		$this->displayName . '",'
				. '"profileLocation":"' .	$this->profileLocation . '",'
				. '"personalPublicKey":"' .	PublicKey::exportKey($this->personalPublicKey) . '",'
				. '"accountPublicKey":"' .	PublicKey::exportKey($this->accountPublicKey) . '",'
				. '"salt":"' .	 			$this->salt . '",'
				. '"datetime":"' .	 		$this->datetime . '",'
				. '"active":' . 			$this->active . ','
				. '"keyRevocationList":[';
				
		foreach($this->keyRevocationList as $krc)
		{
			$json .= $krc->getJSONString();
			if($krc !== end($this->keyRevocationList)) $json .= ',';
		}
		
		$json .= ']}';
		
		return $json;
	}
	
	/**
	 * returns the SocialRecord as a PHP-style JSON object (via json_decode())
	 * 
	 * @return the SocialRecord as a JSON object
	 */
	public function getJSONObject()
	{
		return new JSONObject($this->getJSONString());
		//return json_decode($this->getJSONString());
	}
	
	/**
	 * Runs a validation of the structure of the SocialRecord
	 *
	 * return true if structure is ok, otherwise a SocialRecordFormatException is thrown
	 */
	public function verify()
	{
		// TODO check structure
		if($this->type != SocialRecord::TYPE_PLATFORM && $this->type != SocialRecord::TYPE_USER)
			throw new SocialRecordFormatException('invalid type value [' . $this->type . ']');
		
		if(!GID::isValid($this->globalID))
			throw new SocialRecordFormatException('invalid globalID value');
			
		if(!GID::isValid($this->platformGID))
			throw new SocialRecordFormatException('invalid platformGID value');
		
		if(!GID::verifyGID($this->personalPublicKey, $this->salt, $this->globalID))
			throw new SocialRecordFormatException('invalid globalID value');
		
		if(!XSDDateTime::isValid($this->datetime))
			throw new SocialRecordFormatException('invalid date value [' . $this->datetime . ']');
		
		/*if($this->)
			throw new SocialRecordFormatException('invalid value');
		*/
		
		return true;
	}
	
	public function setType($type)
	{
		$this->type = $type;
		
		return $this;
	}
	
	public function setGlobalID($gid)
	{
		$this->globalID = $gid;
		
		return $this;
	}
	
	public function setPlatformGID($pgid)
	{
		$this->platformGID = $pgid;
		
		return $this;
	}
	
	public function setDisplayName($displayName)
	{
		$this->displayName = $displayName;
		
		return $this;
	}
	
	public function setProfileLocation($profileLocation)
	{
		$this->profileLocation = $profileLocation;
		
		return $this;
	}
	
	public function setPersonalPublicKey($personalPublicKey)
	{
		$this->personalPublicKey = $personalPublicKey;
		
		return $this;
	}
	
	public function setAccountPublicKey($accountPublicKey)
	{
		$this->accountPublicKey = $accountPublicKey;
		
		return $this;
	}
	
	public function setSalt($salt)
	{
		$this->salt = $salt;
		
		return $this;
	}
	
	public function setActive($active)
	{
		$this->active = $active;
		
		return $this;
	}
	
	public function setKeyRevocationList($krl)
	{
		$this->keyRevocationList = $krl;
	}
	
	public function setDateTime($date)
	{
		$this->datetime = $date;
		
		return $this;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function getGlobalID()
	{
		return $this->globalID;
	}
	
	public function getPlatformGID()
	{
		return $this->platformGID;
	}
	
	public function getDisplayName()
	{
		return $this->displayName;
	}
	
	public function getProfileLocation()
	{
		if($this->profileLocation[strlen($this->profileLocation)-1] == '/')
			return $this->profileLocation;
		else
			return $this->profileLocation . '/';
	}
	
	public function getPersonalPublicKey()
	{
		return $this->personalPublicKey;
	}
	
	public function getAccountPublicKey()
	{
		return $this->accountPublicKey;
	}
	
	public function getSalt()
	{
		return $this->salt;
	}
	
	public function getDateTime()
	{
		return $this->datetime;
	}
	
	public function getActive()
	{
		return $this->active;
	}
	
	public function getKeyRevocationList()
	{
		return $this->keyRevocationList;
	}
}

?>