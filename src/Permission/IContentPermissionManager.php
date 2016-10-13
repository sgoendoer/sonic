<?php namespace sgoendoer\Sonic\Permission;

use sgoendoer\Sonic\Permission\PermissionManager;

/**
 * Interface for ContentPermissionManager
 * version 20161013
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
interface IContentPermissionManager
{
	/**
	 * determines if a globalID has access priviledges for a specific content object
	 * 
	 * @param $gid the GlobalID
	 * @param $resource The resource name as a string
	 * 
	 * @return boolean
	 */
	public function hasContentAccessPriviledges($gid, $uoid);
}

?>