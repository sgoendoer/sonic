<?php namespace sgoendoer\Sonic\AccessControl;

/**
 * Manages Access Control Groups
 * version 20161021
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
abstract class AccessControlGroupManager
{
	//abstract public static function getGroupsForGID($gid);
	
	abstract public static function isInGroup($gid, $groupUOID);
	
	//abstract public static function getMembersOfGroup($groupUOID);
	
	//abstract public static function getGroups();
	
	//abstract public static function addToGroup($gid, $groupUOID);
	
	//abstract public static function removeFromGroup($gid, $groupUOID);
	
	//abstract public static function createGroup($groupUOID);
	
	//abstract public static function deleteGroup($groupUOID);
}

?>