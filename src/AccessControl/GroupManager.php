<?php namespace sgoendoer\Sonic\AccessControl;

/**
 * Manages groups for Access Control
 * version 20161019
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
abstract class GroupManager
{
	abstract public function getGroupsForGID($gid);
	
	abstract public function isInGroup($gid, $groupID);
	
	abstract public function getMembersOfGroup($groupID);
	
	abstract public function getGroups();
}

?>