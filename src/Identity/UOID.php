<?php namespace sgoendoer\Sonic\Identity;

use sgoendoer\Sonic\Crypt\Random;
use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Identity\GID;

/**
 * Creates and verifies Unique Object IDs (UOID)
 * version 20160105
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class UOID
{
	private static $separator = ':';
	
	public static function createUOID($id = NULL)
	{
		if($id == NULL)
			$id = Random::getUniqueRandom();
		
		$uoid = Sonic::getUserGlobalID() . self::$separator . $id;
		
		return $uoid;
	}
	
	public static function isValid($uoid)
	{
		$uoid = explode(self::$separator, $uoid);
		
		if(count($uoid) != 2)
			return false;
		
		// check GID
		if(!GID::isValid($uoid[0]))
			return false;
		
		// check id // TODO
		if(false)
			return false;
		
		return true;
	}
}

?>