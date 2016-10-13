<?php namespace sgoendoer\Sonic\Permission;

use sgoendoer\Sonic\Permission\PermissionManager;

/**
 * Interface for GlobalPermissionManager
 * version 20161013
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
interface IGlobalPermissionManager
{
	/**
	 * determines if a globalID is blocked from accessing the current profile
	 * 
	 * @param $gid the GlobalID
	 * 
	 * @return boolean
	 */
	public function isGloballyRestricted($gid);
}

?>