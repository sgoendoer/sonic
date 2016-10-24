<?php namespace sgoendoer\Sonic\Identity;

use sgoendoer\Sonic\Crypt\PublicKey;

/**
 * Creates a SONIC GlobalID from a given key and salt
 * version 20160125
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class GID
{
	private static $ITERATIONS		= 10000;
	private static $HASH_ALGORITHM	= "sha256";
	
	/**
	 * Creates a GlobalID from a $key and $salt.
	 * 
	 * @param $key the publicKey
	 * @param $salt the salt
	 * 
	 * @return the GlobalID
	 */
	public static function createGID($key, $salt)
	{
		$gid = null;
		$key = PublicKey::exportKey($key); // headers, trailers, and linebreaks have to be deleted
		
		$gid = strtoupper(hash_pbkdf2(self::$HASH_ALGORITHM, $key, $salt, self::$ITERATIONS));
		$gid = self::convBase($gid, "0123456789ABCDEF", "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ");
		
		return $gid;
	}
	
	/**
	 * Determines if the format of a given GlobalID is valid.
	 * 
	 * @param $gid the GlobalID to check
	 * 
	 * @return true if the format of $gid is a valid, else false
	 */
	public static function isValid($gid)
	{
		if(preg_match("/^[A-Z0-9]+$/", $gid))
		{
			// approx 85% of GIDs are 50 chars long, 14% 49, and 1% 48.
			// On very rare occasions, a length of 47 is possible.
			if(strlen($gid) <= 50 && strlen($gid) >= 47)
			{
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Determines if a given GlobalID is correct, i.e. if it is derived by the specified $key and $salt.
	 * 
	 * @param $key the publicKey
	 * @param $salt the salt
	 * @param $gid the GlobalID to check
	 *
	 * @return true if the $gid is correct, else false
	 */
	public static function verifyGID($key, $salt, $gid)
	{
		if(self::createGID($key, $salt) == $gid)
			return true;
		else
			return false;
	}
	
	/**
	 * Converts a string to base36
	 * 
	 * @param $inputNumber
	 * @param $fromBaseInput
	 * @param $toBaseInput
	 * 
	 * @return the converted value
	 */
	private static function convBase($numberInput, $fromBaseInput, $toBaseInput)
	{
		if($fromBaseInput == $toBaseInput)
			return $numberInput;
		
		$fromBase	= str_split($fromBaseInput, 1);
		$toBase		= str_split($toBaseInput, 1);
		$number		= str_split($numberInput, 1);
		$fromLen	= strlen($fromBaseInput);
		$toLen		= strlen($toBaseInput);
		$numberLen	= strlen($numberInput);
		$retval		= '';
		
		if($toBaseInput == '0123456789')
		{
			$retval = 0;
			for ($i=1; $i<=$numberLen; $i++)
				$retval = bcadd($retval, bcmul(array_search($number[$i-1], $fromBase), bcpow($fromLen, $numberLen-$i)));
			return $retval;
		}
		
		if($fromBaseInput != '0123456789')
			$base10 = self::convBase($numberInput, $fromBaseInput, '0123456789');
		else
			$base10 = $numberInput;
		
		if($base10 < strlen($toBaseInput))
			return $toBase[$base10];
		
		while($base10 != '0')
		{
			$retval = $toBase[bcmod($base10, $toLen)].$retval;
			$base10 = bcdiv($base10, $toLen, 0);
		}
		
		return $retval;
	}
}

?>