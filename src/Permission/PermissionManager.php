<?php namespace sgoendoer\Sonic\Permission;

use sgoendoer\Sonic\Permission\IGlobalPermissionManager;
use sgoendoer\Sonic\Permission\IAPIPermissionManager;
use sgoendoer\Sonic\Permission\IContentPermissionManager;
use sgoendoer\Sonic\Permission\PermissionManager;
use sgoendoer\Sonic\Permission\PermissionManagerException;
use sgoendoer\Sonic\Permission\PermissionException;

/**
 * Manages Permissions
 * version 20161013
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class PermissionManager
{
	protected static $_instance			= NULL;
	
	private $GlobalPermissionManager	= NULL;
	private $APIPermissionManager		= NULL;
	private $ContentPermissionManager	= NULL;
	
	/**
	 * protected/hidden constructor
	 */
	protected function __construct() {}
	
	/**
	 * disable cloning
	 */
	private function __clone() {}
	
	/**
	 * returns the singleton instance of the PermissionManager.
	 * 
	 * @param The PermissionManager instance
	 */
	public static function &getInstance()
	{
		if(NULL === self::$_instance)
			self::$_instance = new PermissionManager();
		return self::$_instance;
	}
	
	/**
	 * sets the GlobalPermissionManager instance
	 * 
	 * @param $globalPermissionManager The GlobalPermissionManager instance
	 * @return PermissionManager instance
	 */
	public function setGlobalPermissionManager(IGlobalPermissionManager $globalPermissionManager)
	{
		if(!array_key_exists('sgoendoer\Sonic\Permission\IGlobalPermissionManager', class_implements($IGlobalPermissionManager)))
		{
			throw new PermissionManagerException('globalPermissionManager must implement goendoer\Sonic\Permission\IGlobalPermissionManager');
		}
		else
			$this->globalPermissionManager = $globalPermissionManager;
		return $this;
	}
	
	/**
	 * returns the GlobalPermissionManager
	 * 
	 * @throws PermissionManagerException if instance not found
	 * @return the GlobalPermissionManager instance
	 */
	public function getGlobalPermissionManager()
	{
		if($this->globalPermissionManager === NULL)
			throw new PermissionManagerException('GlobalPermissionManager not set');
		else
			return $this->globalPermissionManager;
	}
	
	/**
	 * determines, if GlobalPermissionManager is enabled
	 * 
	 * @return true, if GlobalPermissionManager is enabled, else false
	 */
	public static function globalPermissionManagerEnabled()
	{
		if($this->globalPermissionManager === NULL)
			return false;
		else
			return true;
	}
	
	/**
	 * sets the APIPermissionManager instance
	 * 
	 * @param $APIPermissionManager The APIPermissionManager instance
	 * @return PermissionManager instance
	 */
	public function setAPIPermissionManager(IAPIPermissionManager $APIPermissionManager)
	{
		if(!array_key_exists('sgoendoer\Sonic\Permission\IAPIPermissionManager', class_implements($IAPIPermissionManager)))
		{
			throw new PermissionManagerException('APIPermissionManager must implement goendoer\Sonic\Permission\IAPIPermissionManager');
		}
		else
			$this->APIPermissionManager = $APIPermissionManager;
		return $this;
	}
	
	/**
	 * returns the APIPermissionManager
	 * 
	 * @throws PermissionManagerException if instance not found
	 * @return the APIPermissionManager instance
	 */
	public function getAPIPermissionManager()
	{
		if($this->APIPermissionManager === NULL)
			throw new PermissionManagerException('APIPermissionManager not set');
		else
			return $this->APIPermissionManager;
	}
	
	/**
	 * determines, if APIPermissionManager is enabled
	 * 
	 * @return true, if APIPermissionManager is enabled, else false
	 */
	public static function APIPermissionManagerEnabled()
	{
		if($this->APIPermissionManager === NULL)
			return false;
		else
			return true;
	}
	
	/**
	 * sets the ContentPermissionManager instance
	 * 
	 * @param $contentPermissionManager The ContentPermissionManager instance
	 * @return PermissionManager instance
	 */
	public function setContentPermissionManager(IContentPermissionManager $contentPermissionManager)
	{
		if(!array_key_exists('sgoendoer\Sonic\Permission\IContentPermissionManager', class_implements($IContentPermissionManager)))
		{
			throw new PermissionManagerException('contentPermissionManager must implement goendoer\Sonic\Permission\IContentPermissionManager');
		}
		else
			$this->contentPermissionManager = $contentPermissionManager;
		return $this;
	}
	
	/**
	 * returns the ContentPermissionManager
	 * 
	 * @throws PermissionManagerException if instance not found
	 * @return the ContentPermissionManager instance
	 */
	public function getContentPermissionManager()
	{
		if($this->contentPermissionManager === NULL)
			throw new PermissionManagerException('ContentPermissionManager not set');
		else
			return $this->contentPermissionManager;
	}
	
	/**
	 * determines, if ContentPermissionManager is enabled
	 * 
	 * @return true, if ContentPermissionManager is enabled, else false
	 */
	public static function contentPermissionManagerEnabled()
	{
		if($this->contentPermissionManager === NULL)
			return false;
		else
			return true;
	}
}

?>