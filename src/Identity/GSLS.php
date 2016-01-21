<?php namespace sgoendoer\Sonic\Identity;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Config\Config;
use sgoendoer\Sonic\Crypt\PublicKey;
use sgoendoer\Sonic\Crypt\PrivateKey;

use sgoendoer\Sonic\Identity\SocialRecord;
use sgoendoer\Sonic\Identity\SocialRecordBuider;
use sgoendoer\Sonic\Identity\SocialRecordNotFoundException;
use sgoendoer\Sonic\Identity\SocialRecordIntegrityException;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Rsa\Sha512;
use Lcobucci\JWT\Parser;

/**
 * GSLS API
 * version 20160121
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class GSLS
{
	public static function getSocialRecord($gid, $raw = false)
	{
		$ch = curl_init(Config::primaryGSLSNode() . '/' . $gid);
		if(Config::verbose() == 1) curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		$result = curl_exec($ch);
		
		if(curl_errno($ch) != CURLE_OK)
		{
			throw new \Exception('Connection error: ' . curl_error($ch));
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
		
		$ch = curl_init(Config::primaryGSLSNode());
		if(Config::verbose() == 1) curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(	'Content-type: application/json', 
													'Content-Length: ' . strlen((string) $token)));
		curl_setopt($ch, CURLOPT_POSTFIELDS, (string) $token);
		
		$result = curl_exec($ch);
		
		if(curl_errno($ch) != CURLE_OK)
		{
			throw new \Exception('Connection error: ' . curl_error($ch));
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
		
		$ch = curl_init(Config::primaryGSLSNode());
		if(Config::verbose() == 1) curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(	'Content-type: application/json', 
													'Content-Length: ' . strlen((string) $token)));
		curl_setopt($ch, CURLOPT_POSTFIELDS, (string) $token);

		$result = curl_exec($ch);
		
		if(curl_errno($ch) != CURLE_OK)
		{
			throw new \Exception('Connection error: ' . curl_error($ch));
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