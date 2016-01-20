<?php namespace sgoendoer\Sonic\Crypt;

use sgoendoer\Sonic\Sonic;

/**
 * Creates random numbers
 * version 20150925
 *
 * author: Seabstian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class Random
{
	public static function getRandom($length = 8)
	{
		return bin2hex(openssl_random_pseudo_bytes($length));
	}
	
	public static function getUniqueRandom($length = 8)
	{
		$id = self::getRandom($length);
		
		if(Sonic::getUniqueIDManager() != NULL)
		{
			while(Sonic::getUniqueIDManager()->isIDRegistered($id) === true)
			{
				$id = self::getRandom($length);
			}
			
			Sonic::getUniqueIDManager()->registerID($id);
		}
		
		return $id;
	}
}

?>