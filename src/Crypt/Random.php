<?php namespace sgoendoer\Sonic\Crypt;

use sgoendoer\Sonic\Sonic;

/**
 * Creates random numbers
 * version 20170913
 *
 * author: Seabstian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class Random
{
	/**
	 * creates a random Byte sequence of length $length. The value is returned as Hex.
	 * 
	 * @param $length integer. Defaults to 8 
	 * @return string
	 */
	public static function getRandom($length = 8)
	{
		return bin2hex(openssl_random_pseudo_bytes((int) $length));
	}
	
	/**
	 * creates a random character sequence of length $length. The created value is checked against already used values for this account to guarantee uniquenes. The newly created value is then registered via the UniqueIDManager of the SDK.
	 * 
	 * @param $length integer. Defaults to 8
	 * @return string
	 */
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