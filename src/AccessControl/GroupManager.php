<?php namespace sgoendoer\Sonic\AccessControl;

/**
 * Manages groups for Access Control
 * version 20161018
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
abstract class GroupManager
{
	protected static $_instance				= NULL;
	
	/**
	 * protected/hidden constructor
	 */
	protected function __construct() {}
	
	/**
	 * disable cloning
	 */
	private function __clone() {}
	
	/**
	 * returns the singleton instance of the GroupManager.
	 * 
	 * @param The GroupManager instance
	 */
	public static function &getInstance()
	{
		if(NULL === self::$_instance)
			self::$_instance = new GroupManager();
		return self::$_instance;
	}
	
	abstract public function getGroupsForGID($gid);
	
	abstract public function isInGroup($gid, $groupID);
	
	abstract public function getMembersOfGroup($groupID);
	
	abstract public function getGroups();
}

?>