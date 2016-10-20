<?php namespace sgoendoer\Sonic\AccessControl;

/**
 * Manages friends
 * version 20161018
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
abstract class FriendManager
{
	abstract public function isAFriend($gid);
	
	abstract public function getAllFriends();
}

?>