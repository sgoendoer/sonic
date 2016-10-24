<?php namespace sgoendoer\Sonic\Identity;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Config\Configuration;
use sgoendoer\Sonic\Crypt\PublicKey;
use sgoendoer\Sonic\Crypt\PrivateKey;

use sgoendoer\Sonic\Identity\SocialRecord;
use sgoendoer\Sonic\Identity\SocialRecordBuider;
use sgoendoer\Sonic\Identity\SocialRecordFormatException;
use sgoendoer\Sonic\Identity\SocialRecordNotFoundException;
use sgoendoer\Sonic\Identity\SocialRecordIntegrityException;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Rsa\Sha512;
use Lcobucci\JWT\Parser;

/**
 * GSLS API
 * version 20160203
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class GSLS
{
	/**
	 * Retrieves a SocialRecord for a given GlobalID from the GSLS. The signed JWT stored in the GSLS will be retrieved, the payloads verified, and the enclosed SocialRecord object will be returned.
	 * 
	 * @param $gid The GlobalID to resolve
	 * @param $raw If set to true, the signed JWT will be returned instead of the SocialRecrod
	 * 
	 * @throws SocialRecordNotFoundException
	 * @throws SocialRecordIntegrityException
	 * @throws Exception
	 * 
	 * @return SocialRecord object
	 */
	public static function getSocialRecord($gid, $raw = false)
	{
		$ch = curl_init(Configuration::getPrimaryGSLSNode() . '/' . $gid);
		if(Configuration::getCurlVerbose() >= 2) curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, Configuration::getGSLSTimeout());
		$result = curl_exec($ch);
		
		if(curl_errno($ch) != CURLE_OK)
		{
			$ch = curl_init(Configuration::getSecondaryGSLSNode() . '/' . $gid);
			if(Configuration::getCurlVerbose() >= 2) curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
			$result = curl_exec($ch);
			
			if(curl_errno($ch) != CURLE_OK)
			{
				throw new \Exception('Connection error: ' . curl_error($ch));
			}
		}
		
		$result = json_decode($result);
		curl_close($ch);
		
		if($result->responseCode != 200)
		{
			if($result->responseCode == 404)
				throw new SocialRecordNotFoundException($result->message);
			else
				throw new \Exception($result->message);
		}
		else
		{
			// verify JWT and extract SocialRecord
			$signer = new Sha512();
			
			$token = (new Parser())->parse((string) $result->socialRecord);
			
			$socialRecord = json_decode(base64_decode($token->getClaim('socialRecord')));
			
			$personalPublicKey = PublicKey::formatPEM($socialRecord->personalPublicKey);
			
			try
			{
				$token->verify($signer, $personalPublicKey);
			}
			catch(\Exception $e)
			{
				throw new SocialRecordIntegrityException('SocialRecord integrity compromised: ' . $e->getMessage());
			}
			
			if($raw)
				return $token;
			else
				return SocialRecordBuilder::buildFromJSON(json_encode($socialRecord, JSON_UNESCAPED_SLASHES));
		}
	}
	
	/**
	 * Pushes a new SocialRecord to the GSLS. The SocialRecord will be transformed into a signed JWT, which is then stored in the GSLS
	 * 
	 * @param $sr The SocialRecord
	 * @param $personalPrivateKey The private key to sign the JWT
	 * 
	 * @throws Exception
	 * 
	 * @return result json string
	 */
	public static function postSocialRecord(SocialRecord $sr, $personalPrivateKey)
	{
		if(!$sr->verify())
			throw new \Exception("Error: Invalid Social Record");
		
		// create and sign JWT
		$signer = new Sha512();
		
		$personalPrivateKey = PrivateKey::formatPEM($personalPrivateKey);
		
		$token = (new Builder())
			->set('socialRecord', base64_encode($sr->getJSONString()))
			->sign($signer, $personalPrivateKey)
			->getToken();
		
		$ch = curl_init(Configuration::getPrimaryGSLSNode());
		if(Configuration::getCurlVerbose() >= 2) curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, Configuration::getGSLSTimeout());
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(	'Content-type: application/json', 
													'Content-Length: ' . strlen((string) $token)));
		curl_setopt($ch, CURLOPT_POSTFIELDS, (string) $token);
		
		$result = curl_exec($ch);
		
		if(curl_errno($ch) != CURLE_OK)
		{
			$ch = curl_init(Configuration::getSecondaryGSLSNode());
			if(Configuration::getCurlVerbose() >= 2) curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
			$result = curl_exec($ch);
			
			if(curl_errno($ch) != CURLE_OK)
			{
				throw new \Exception('Connection error: ' . curl_error($ch));
			}
		}
		
		$result = json_decode($result);
		curl_close($ch);
		
		if($result->responseCode != 200)
		{
			throw new \Exception("Error: " . $result->message);
		}
		else
		{
			return $result;
		}
	}
	
	/**
	 * Pushes an update for a SocialRecord to the GSLS. The SocialRecord will be transformed into a signed JWT, which is then stored in the GSLS
	 * 
	 * @param $sr The SocialRecord
	 * @param $personalPrivateKey The private key to sign the JWT
	 * 
	 * @throws Exception
	 * 
	 * @return result json string
	 */
	public static function putSocialRecord(SocialRecord $sr, $personalPrivateKey)
	{
		if(!$sr->verify())
			throw new \Excetion("Error: Invalid Social Record");
		
		// create and sign JWT
		$signer = new Sha512();
		
		$personalPrivateKey = PrivateKey::formatPEM($personalPrivateKey);
		
		$token = (new Builder())
			->set('socialRecord', base64_encode($sr->getJSONString()))
			->sign($signer, $personalPrivateKey)
			->getToken();
		
		$ch = curl_init(Configuration::getPrimaryGSLSNode());
		if(Configuration::getCurlVerbose() >= 2) curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, Configuration::getGSLSTimeout());
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(	'Content-type: application/json', 
													'Content-Length: ' . strlen((string) $token)));
		curl_setopt($ch, CURLOPT_POSTFIELDS, (string) $token);

		$result = curl_exec($ch);
		
		if(curl_errno($ch) != CURLE_OK)
		{
			$ch = curl_init(Configuration::getSecondaryGSLSNode());
			if(Configuration::getCurlVerbose() >= 2) curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
			$result = curl_exec($ch);
			
			if(curl_errno($ch) != CURLE_OK)
			{
				throw new \Exception('Connection error: ' . curl_error($ch));
			}
		}
		
		$result = json_decode($result);
		
		curl_close($ch);
		
		if($result->responseCode != 200)
		{
			throw new \Exception("Error: " . $result->message);
		}
		else
		{
			return $result;
		}
	}
}

?>