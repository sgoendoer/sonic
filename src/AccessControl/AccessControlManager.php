<?php namespace sgoendoer\Sonic\AccessControl;

use sgoendoer\Sonic\AccessControl\IGlobalAccessControlManager;
use sgoendoer\Sonic\AccessControl\IAPIAccessControlManager;
use sgoendoer\Sonic\AccessControl\IContentAccessControlManager;
use sgoendoer\Sonic\AccessControl\AccessControlManager;
use sgoendoer\Sonic\AccessControl\AccessControlManagerException;
use sgoendoer\Sonic\AccessControl\AccessControlException;

/**
 * Manages permissions for access control
 * version 20161014
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class AccessControlManager
{
	protected static $_instance			= NULL;
	
	private $GlobalAccessControlManager	= NULL;
	private $APIAccessControlManager		= NULL;
	private $ContentAccessControlManager	= NULL;
	
	/**
	 * protected/hidden constructor
	 */
	protected function __construct() {}
	
	/**
	 * disable cloning
	 */
	private function __clone() {}
	
	/**
	 * returns the singleton instance of the AccessControlManager.
	 * 
	 * @param The AccessControlManager instance
	 */
	public static function &getInstance()
	{
		if(NULL === self::$_instance)
			self::$_instance = new AccessControlManager();
		return self::$_instance;
	}
	
	/**
	 * sets the GlobalAccessControlManager instance
	 * 
	 * @param $globalAccessControlManager The GlobalAccessControlManager instance
	 * @return AccessControlManager instance
	 */
	public function setGlobalAccessControlManager(IGlobalAccessControlManager $globalAccessControlManager)
	{
		if(!array_key_exists('sgoendoer\Sonic\AccessControl\IGlobalAccessControlManager', class_implements($IGlobalAccessControlManager)))
		{
			throw new AccessControlManagerException('globalAccessControlManager must implement goendoer\Sonic\AccessControl\IGlobalAccessControlManager');
		}
		else
			$this->globalAccessControlManager = $globalAccessControlManager;
		return $this;
	}
	
	/**
	 * returns the GlobalAccessControlManager
	 * 
	 * @throws AccessControlManagerException if instance not found
	 * @return the GlobalAccessControlManager instance
	 */
	public function getGlobalAccessControlManager()
	{
		if($this->globalAccessControlManager === NULL)
			throw new AccessControlManagerException('GlobalAccessControlManager not set');
		else
			return $this->globalAccessControlManager;
	}
	
	/**
	 * determines, if GlobalAccessControlManager is enabled
	 * 
	 * @return true, if GlobalAccessControlManager is enabled, else false
	 */
	public static function globalAccessControlManagerEnabled()
	{
		if($this->globalAccessControlManager === NULL)
			return false;
		else
			return true;
	}
	
	/**
	 * sets the APIAccessControlManager instance
	 * 
	 * @param $APIAccessControlManager The APIAccessControlManager instance
	 * @return AccessControlManager instance
	 */
	public function setAPIAccessControlManager(IAPIAccessControlManager $APIAccessControlManager)
	{
		if(!array_key_exists('sgoendoer\Sonic\AccessControl\IAPIAccessControlManager', class_implements($IAPIAccessControlManager)))
		{
			throw new AccessControlManagerException('APIAccessControlManager must implement goendoer\Sonic\AccessControl\IAPIAccessControlManager');
		}
		else
			$this->APIAccessControlManager = $APIAccessControlManager;
		return $this;
	}
	
	/**
	 * returns the APIAccessControlManager
	 * 
	 * @throws AccessControlManagerException if instance not found
	 * @return the APIAccessControlManager instance
	 */
	public function getAPIAccessControlManager()
	{
		if($this->APIAccessControlManager === NULL)
			throw new AccessControlManagerException('APIAccessControlManager not set');
		else
			return $this->APIAccessControlManager;
	}
	
	/**
	 * determines, if APIAccessControlManager is enabled
	 * 
	 * @return true, if APIAccessControlManager is enabled, else false
	 */
	public static function APIAccessControlManagerEnabled()
	{
		if($this->APIAccessControlManager === NULL)
			return false;
		else
			return true;
	}
	
	/**
	 * sets the ContentAccessControlManager instance
	 * 
	 * @param $contentAccessControlManager The ContentAccessControlManager instance
	 * @return AccessControlManager instance
	 */
	public function setContentAccessControlManager(IContentAccessControlManager $contentAccessControlManager)
	{
		if(!array_key_exists('sgoendoer\Sonic\AccessControl\IContentAccessControlManager', class_implements($IContentAccessControlManager)))
		{
			throw new AccessControlManagerException('contentAccessControlManager must implement goendoer\Sonic\AccessControl\IContentAccessControlManager');
		}
		else
			$this->contentAccessControlManager = $contentAccessControlManager;
		return $this;
	}
	
	/**
	 * returns the ContentAccessControlManager
	 * 
	 * @throws AccessControlManagerException if instance not found
	 * @return the ContentAccessControlManager instance
	 */
	public function getContentAccessControlManager()
	{
		if($this->contentAccessControlManager === NULL)
			throw new AccessControlManagerException('ContentAccessControlManager not set');
		else
			return $this->contentAccessControlManager;
	}
	
	/**
	 * determines, if ContentAccessControlManager is enabled
	 * 
	 * @return true, if ContentAccessControlManager is enabled, else false
	 */
	public static function contentAccessControlManagerEnabled()
	{
		if($this->contentAccessControlManager === NULL)
			return false;
		else
			return true;
	}
}

?>