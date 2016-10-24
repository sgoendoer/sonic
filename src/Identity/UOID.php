<?php namespace sgoendoer\Sonic\Identity;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Crypt\Random;
use sgoendoer\Sonic\Identity\GID;

/**
 * Creates and verifies Unique Object IDs (UOID)
 * version 20160429
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class UOID
{
	const SEPARATOR = ':';
	
	/**
	 * Creates a new UOID for the current Sonic context
	 * 
	 * @param $gid String The global id part of the UOID
	 * @param $id String The local id part of the UOID
	 * 
	 * @return A UOID
	 */
	public static function createUOID($gid = NULL, $id = NULL)
	{
		if($gid == NULL)
			$gid = Sonic::getContextGlobalID();
		
		if($id == NULL)
			$id = Random::getUniqueRandom();
		
		$uoid = $gid . UOID::SEPARATOR . $id;
		
		return $uoid;
	}
	
	/**
	 * Verifies, if a given UOID is valid
	 * 
	 * @param $uoid String The UOID to verify
	 * 
	 * @return true, of the UOID is valid, else false
	 */
	public static function isValid($uoid)
	{
		$uoid = explode(UOID::SEPARATOR, $uoid);
		
		if(count($uoid) != 2)
			return false;
		
		// check GID
		if(!GID::isValid($uoid[0]))
			return false;
		
		// check id
		if(!preg_match("/^[a-zA-Z0-9]+$/", $uoid[1]) || strlen($uoid[1]) != 16)
		{
			return false;
		}
		
		return true;
	}
}

?>