<?php namespace sgoendoer\Sonic\Crypt;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Crypt\Random;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Tools\JSONTools;

/**
 * Creates and verifies signatures
 * version 20150727
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
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
		$signature = null;
		
		openssl_sign($message, $signature, $privateKey, self::$algorithm);
		
		return self::$signaturePrefix . base64_encode($signature) . self::$signaturePostfix;
	}
	
	public static function verifySignature($message, $publicKey, $signature)
	{
		$signature = str_replace(self::$signaturePrefix, '', $signature);
		$signature = str_replace(self::$signaturePostfix, '', $signature);
		
		return openssl_verify($message, base64_decode($signature), $publicKey, self::$algorithm);
	}
	
	public static function createSignatureObject($message, $targetID, $accountPrivateKey)
	{
		$signatureObject = JSONTools::createJSONObject();
		
		$signatureObject->targetID = $targetID;
		$signatureObject->creatorGID = Sonic::getUserGlobalID();
		$signatureObject->timeSigned = XSDDateTime::getXSDDateTime();
		$signatureObject->random = Random::getRandom();
		
		$sigmessage = $message . $signatureObject->targetID . $signatureObject->creatorGID . $signatureObject->timeSigned . $signatureObject->random;
		
		$signatureObject->signature = self::createSignature($sigmessage, $accountPrivateKey);
		
		return $signatureObject;
	}
	
	public static function verifySignatureObject($message, $signatureObject, $accountPublicKey)
	{
		$signatureObject = JSONTools::coerceToJSON($signatureObject); // necessary?
		
		$sigmessage = $message . $signatureObject->targetID . $signatureObject->creatorGID . $signatureObject->timeSigned . $signatureObject->random;
		
		return self::verifySignature($sigmessage, $accountPublicKey, $signatureObject->signature);
	}
	
	/*
	 * creates a signature for a Sonic Object 
	 */
	public static function signJSON($jsonToSign, $accountPrivateKey)
	{
		if(gettype($jsonToSign) != 'string')
			$jsonToSign = json_encode($jsonToSign);
			//throw new \Exception('Value $jsonToSign must be a String, is a ' . gettype($jsonToSign));
		
		$signedJSON = JSONTools::createJSONObject();
		
		$signatureObject = JSONTools::createJSONObject();
		
		$tmpJSON = json_decode($jsonToSign);
		
		$signatureObject->targetID = $tmpJSON->objectID;
		$signatureObject->creatorGID = Sonic::getUserGlobalID();
		$signatureObject->timeSigned = XSDDateTime::getXSDDateTime();
		$signatureObject->random = Random::getRandom();
		
		$message = $jsonToSign . $signatureObject->targetID . $signatureObject->creatorGID . $signatureObject->timeSigned . $signatureObject->random;
		
		$signatureObject->signature = self::createSignature($message, $accountPrivateKey);
		
		$signedJSON->signature = $signatureObject;
		$signedJSON->object = $jsonToSign;
		
		return $signedJSON;
	}
	
	public static function verifyJSONSignature($jsonObject, $accountPublicKey)
	{
		$jsonObject = JSONTools::coerceToJSON($jsonObject); // necessary?
		
		if(gettype($jsonObject->object) != 'string')
			throw new \Exception('Value $jsonObject->object must be a String, is a ' . gettype($jsonObject->object));
		
		$signatureObject = $jsonObject->signature;
		
		$message = $jsonObject->object . $signatureObject->targetID . $signatureObject->creatorGID . $signatureObject->timeSigned . $signatureObject->random;
		
		return self::verifySignature($message, $accountPublicKey, $signatureObject->signature);
	}
}

?>