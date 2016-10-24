<?php namespace sgoendoer\Sonic\Crypt;

/**
 * Creates and verifies signatures
 * version 20160125
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class Signature
{
	private static $algorithm			= OPENSSL_ALGO_SHA512;
	private static $signaturePrefix		= '-----BEGIN SIGNATURE-----';
	private static $signaturePostfix	= '-----END SIGNATURE-----';
	
	public static function setAlgorithm($algorithm)
	{
		self::$algorithm = $algorithm;
	}
	
	public static function createSignature($message, $privateKey)
	{
		$signature = NULL;
		
		openssl_sign($message, $signature, $privateKey, self::$algorithm);
		
		return self::$signaturePrefix . base64_encode($signature) . self::$signaturePostfix;
	}
	
	public static function verifySignature($message, $publicKey, $signature)
	{
		$signature = str_replace(self::$signaturePrefix, '', $signature);
		$signature = str_replace(self::$signaturePostfix, '', $signature);
		
		return openssl_verify($message, base64_decode($signature), $publicKey, self::$algorithm);
	}
}

?>