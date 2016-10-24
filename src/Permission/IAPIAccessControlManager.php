<?php namespace sgoendoer\Sonic\AccessControl;

use sgoendoer\Sonic\AccessControl\AccessControlManager;

/**
 * Interface for APIAccessControlManager
 * version 20161014
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
interface IAPIAccessControlManager
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
	
	/**
	 * returns the access control object for a given resource
	 * 
	 * @param $resource The resource name as a string
	 * 
	 * @return APIAccessControlObject
	 */
	public function getAccessControlObjectForResource($resource);
}

?>