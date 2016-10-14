<?php namespace sgoendoer\Sonic\AccessControl;

use sgoendoer\Sonic\AccessControl\AccessControlManager;

/**
 * Interface for GlobalAccessControlManager
 * version 20161013
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
interface IGlobalAccessControlManager
{
	/**
	 * determines if a globalID is blocked from accessing the current profile
	 * 
	 * @param $gid the GlobalID
	 * 
	 * @return boolean
	 */
	public function isGloballyRestricted($gid);
	
	public function getAccessControlObject();
}

?>