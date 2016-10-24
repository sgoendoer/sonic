<?php namespace sgoendoer\Sonic;

require_once(__DIR__ . "/init.inc.php");

use sgoendoer\Sonic\Config\Configuration;
use sgoendoer\Sonic\Identity\SocialRecord;
use sgoendoer\Sonic\Crypt\KeyPair;
use sgoendoer\Sonic\Identity\EntityAuthData;
use sgoendoer\Sonic\Identity\ISocialRecordCaching;
use sgoendoer\Sonic\Crypt\IUniqueIDManager;
use sgoendoer\Sonic\AccessControl\AccessControlManager;
use sgoendoer\Sonic\SonicRuntimeException;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Main class of the SONIC SDK
 * 
 * version 20161018
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class Sonic
{
	const CONTEXT_PLATFORM				= 0;
	const CONTEXT_USER					= 1;
	
	protected static $_instance			= NULL;
	
	private static $logger				= NULL;
	private $context					= NULL;
	
	private $userAuthData				= NULL;
	private $platformAuthData			= NULL;
	
	private $socialRecordCache			= NULL;
	private $uniqueIDManager			= NULL;
	private $accessControlManager		= NULL;
	
	/**
	 * protected/hidden constructor
	 */
	protected function __construct() {}
	
	/**
	 * disable cloning
	 */
	private function __clone() {}
	
	/**
	 * returns the singleton instance of the Sonic object. The instance needs to be initialized first by passing the
	 * required EntityAuthData object(s) to initInstance(). If the instance has not been initialized yet, a standard
	 * Exception will be thrown.
	 * 
	 * @param The Sonic instance
	 */
	public static function &getInstance()
	{
		// TODO check if user and platform are correctly initialized
		if(NULL === self::$_instance)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance;
	}
	
	/**
	 * initializes the Sonic SDK using EntityAuthData of the platform
	 * 
	 * @param $config Configuration object
	 * @param $platform EntityAuthData object of the platform
	 * 
	 * @return The Sonic instance
	 */
	public static function &initInstance(EntityAuthData $platform)
	{
		if(NULL === self::$_instance)
		{
			self::$_instance = new Sonic();
		}
		
		self::$_instance->platformAuthData = $platform;
		
		self::$logger = new Logger('sonic');
		self::$logger->pushHandler(new StreamHandler(Configuration::getLogfile()));
		
		self::$_instance->setContext(Sonic::CONTEXT_PLATFORM); // needs to be explicitly set to "user"
		
		return self::$_instance;
	}
	
	public static function getContext()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->context;
	}
	
	public static function setContext($context = NULL)
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		if($context === NULL)
			self::$_instance->context = self::$_instance->platformAuthData;
		elseif($context == Sonic::CONTEXT_USER)
		{
			if(self::$_instance->userAuthData == NULL)
				throw new SonicRuntimeException('User EntityAuthData must not be NULL');
			else
				self::$_instance->context = self::$_instance->userAuthData;
		}
		elseif($context == Sonic::CONTEXT_PLATFORM)
			self::$_instance->context = self::$_instance->platformAuthData;
		else
			throw new SonicRuntimeException('Invalid context');
		
		return self::$_instance;
	}
	
	/**
	 * sets the SocialRecordCaching instance
	 * 
	 * @param $srCachingObject The ISocialRecordCaching instance
	 * @return Sonic instance
	 */
	public function setSocialRecordCaching(ISocialRecordCaching $srCachingObject)
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		if(!array_key_exists('sgoendoer\Sonic\Identity\ISocialRecordCaching', class_implements($srCachingObject)))
		{
			throw new SonicRuntimeException('SocialRecordCaching must implement goendoer\Sonic\Identity\ISocialRecordCaching');
		}
		else
			$this->socialRecordCache = $srCachingObject;
		return $this; // TODO make static?
	}
	
	/**
	 * determines, if SocialRecordCaching is enabled
	 * 
	 * @return true, if SocialRecordCaching is enabled, else false
	 */
	public static function socialRecordCachingEnabled()
	{
		if(self::$_instance === NULL)
			return false;
		if(self::$_instance->socialRecordCache === NULL)
			return false;
		else
			return true;
	}
	
	/**
	 * returns the SocialRecordCaching instance
	 * 
	 * @return if set, the SocialRecordCaching instance
	 */
	public static function getSocialRecordCaching()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		if(self::$_instance->socialRecordCache != NULL)
			return self::$_instance->socialRecordCache;
		else
			return NULL;
	}
	
	/**
	 * sets the UniqueIDManager
	 * 
	 * @param $uniqueIDManagerObject The IUniqueIDManager instance
	 * @return Sonic instance
	 */
	public function setUniqueIDManager(IUniqueIDManager $uniqueIDManagerObject)
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		if(!array_key_exists('sgoendoer\Sonic\Crypt\IUniqueIDManager', class_implements($uniqueIDManagerObject)))
		{
			throw new SonicRuntimeException('UniqueIDManager must implement sgoendoer\Sonic\Crypt\IUniqueIDManager');
		}
		else
			$this->uniqueIDManager = $uniqueIDManagerObject;
		
		return self::$_instance;
	}
	
	/**
	 * returns the UniqueIDManager instance, if set
	 * 
	 * @return UniqueIDManager instance if set, else NULL
	 */
	public static function getUniqueIDManager()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		if(self::$_instance->uniqueIDManager != NULL)
			return self::$_instance->uniqueIDManager;
		else
			return NULL;
	}
	
	/**
	 * sets the AccessControlManager
	 * 
	 * @param $accessControlManagerObject The AccessControlManager instance
	 * @return Sonic instance
	 */
	public function setAccessControlManager(AccessControlManager $accessControlManagerObject)
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		if(!array_key_exists('sgoendoer\Sonic\AccessControl\AccessControlManager', class_parents($accessControlManagerObject)))
		{
			throw new SonicRuntimeException('AccessControlManager must implement sgoendoer\Sonic\AccessControl\AccessControlManager');
		}
		else
			$this->accessControlManager = $accessControlManagerObject;
		
		return self::$_instance;
	}
	
	/**
	 * returns the AccessControlManager instance
	 * 
	 * @return the AccessControlManager instance
	 */
	public static function getAccessControlManager()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		if(self::$_instance->accessControlManager != NULL)
			return self::$_instance->accessControlManager;
		else
			throw new AccessControlManagerException('AccessControlManager instance not found');
	}
	
	/**
	 * set the platform's AuthData
	 * 
	 * @param EntityAuthData object of the platform
	 */
	public static function setPlatformAuthData(EntityAuthData $entityAuthData)
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		self::$_instance->platformAuthData = $entityAuthData;
		
		return self::$_instance;
	}
	
	/**
	 * return the platform's AuthData
	 * 
	 * @return EntityAuthData object of the platform
	 */
	public static function getPlatformAuthData()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->platformAuthData;
	}
	
	/**
	 * return the platform's GlobalID
	 * 
	 * @return the platform's GlobalID
	 */
	public static function getPlatformGlobalID()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->platformAuthData->getGlobalID();
	}
	
	/**
	 * return the platform's Account KeyPair
	 * 
	 * @return the platform's Account KeyPair
	 */
	public static function getPlatformAccountKeyPair()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->platformAuthData->getAccountKeyPair();
	}
	
	/**
	 * return the platform's Personal KeyPair
	 * 
	 * @return the platform's Personal KeyPair
	 */
	public static function getPlatformPersonalKeyPair()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instancey->platformAuthData->getPersonalKeyPair();
	}
	
	/**
	 * return the platform's SocialRecord
	 * 
	 * @return the platform's SocialRecord
	 */
	public static function getPlatformSocialRecord()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->platformAuthData->getSocialRecord();
	}
	
	/**
	 * set the user's AuthData
	 * 
	 * @param EntityAuthData object of the user
	 */
	public static function setUserAuthData(EntityAuthData $entityAuthData)
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		self::$_instance->userAuthData = $entityAuthData;
		
		self::$_instance->setContext(Sonic::CONTEXT_USER);
		
		return self::$_instance;
	}
	
	/**
	 * return the user's AuthData
	 * 
	 * @return EntityAuthData object of the user
	 */
	public static function getUserAuthData()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->userAuthData;
	}
	
	/**
	 * return the user's GlobalID
	 * 
	 * @return the user's GlobalID
	 */
	public static function getUserGlobalID()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->userAuthData->getGlobalID();
	}
	
	/**
	 * return the user's Account KeyPair
	 * 
	 * @return the user's Account KeyPair
	 */
	public static function getUserAccountKeyPair()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->userAuthData->getAccountKeyPair();
	}
	
	/**
	 * return the user's Personal KeyPair
	 * 
	 * @return the user's Personal KeyPair
	 */
	public static function getUserPersonalKeyPair()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->userAuthData->getPersonalKeyPair();
	}
	
	/**
	 * return the user's SocialRecord
	 * 
	 * @return the user's SocialRecord
	 */
	public static function getUserSocialRecord()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->userAuthData->getSocialRecord();
	}
	
	/**
	 * return AuthData of the current context
	 * 
	 * @return EntityAuthData object of the current context
	 */
	public static function getContextAuthData()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->getContext();
	}
	
	/**
	 * return the user's GlobalID of the current context
	 * 
	 * @return the user's GlobalID of the current context
	 */
	public static function getContextGlobalID()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->getContext()->getGlobalID();
	}
	
	/**
	 * return the user's Account KeyPair of the current context
	 * 
	 * @return the user's Account KeyPair of the current context
	 */
	public static function getContextAccountKeyPair()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->getContext()->getAccountKeyPair();
	}
	
	/**
	 * return the user's Personal KeyPair of the current context
	 * 
	 * @return the user's Personal KeyPair of the current context
	 */
	public static function getContextPersonalKeyPair()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->getContext()->getPersonalKeyPair();
	}
	
	/**
	 * return the user's SocialRecord of the current context
	 * 
	 * @return the user's SocialRecord of the current context
	 */
	public static function getContextSocialRecord()
	{
		if(self::$_instance === NULL)
			throw new SonicRuntimeException('Sonic instance not initialized');
		
		return self::$_instance->getContext()->getSocialRecord();
	}
	
	/**
	 * return the monolog logger instance
	 * 
	 * @return the logger instance
	 */
	public static function getLogger()
	{
		if(self::$logger === NULL)
		{
			self::$logger = new Logger('sonic');
			self::$logger->pushHandler(new StreamHandler(Configuration::getLogfile()));
		}
		
		return self::$logger;
	}
}

?>