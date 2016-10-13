<?php namespace sgoendoer\Sonic\Permission;

use sgoendoer\Sonic\Permission\PermissionManager;

/**
 * Interface for GlobalPermissionManager
 * version 20161013
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
interface IAPIPermissionManager
{
	/**
	 * determines if a globalID has create access priviledges for a given resource at the current profile
	 * 
	 * @param $gid the GlobalID
	 * @param $resource The resource name as a string
	 * 
	 * @return boolean
	 */
	public function hasCreatePriviledges($gid, $resource);
	
	/**
	 * determines if a globalID has read access priviledges for a given resource at the current profile
	 * 
	 * @param $gid the GlobalID
	 * @param $resource The resource name as a string
	 * 
	 * @return boolean
	 */
	public function hasReadPriviledges($gid, $resource);
	
	/**
	 * determines if a globalID has update access priviledges for a given resource at the current profile
	 * 
	 * @param $gid the GlobalID
	 * @param $resource The resource name as a string
	 * 
	 * @return boolean
	 */
	public function hasUpdatePriviledges($gid, $resource);
	
	/**
	 * determines if a globalID has delete access priviledges for a given resource at the current profile
	 * 
	 * @param $gid the GlobalID
	 * @param $resource The resource name as a string
	 * 
	 * @return boolean
	 */
	public function hasDeletePriviledges($gid, $resource);
}

?>