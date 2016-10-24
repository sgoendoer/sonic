<?php namespace sgoendoer\Sonic\Identity;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Crypt\KeyPair;
use sgoendoer\Sonic\Crypt\PrivateKey;
use sgoendoer\Sonic\Crypt\PublicKey;
use sgoendoer\Sonic\Crypt\Random;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Identity\GSLS;
use sgoendoer\Sonic\Identity\EntityAuthData;
use sgoendoer\Sonic\Identity\SocialRecord;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Identity\KeyRevocationCertificateBuilder;
use sgoendoer\Sonic\Identity\SocialRecordNotFoundException;

use sgoendoer\json\JSONObject;

/**
 * SocialRecordManager
 * version 20160104
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class SocialRecordManager
{
	/**
	 * Sends a SocialRecord to the GSLS. The SocialRecord is updated/overwritten if it already exists. Otherwise, the
	 * new SocialRecord is stored. In case of a failed ateempt to (over-)write a SocialRecord, an Exception is thrown.
	 * 
	 * @param $entityAuthData EntityAuthData The EntityAuthData object for the SocialRecord to be pushed. The personal
	 * key pair must be configured
	 */
	public static function pushToGSLS(EntityAuthData $entityAuthData)
	{
		if($entityAuthData->getPersonalKeyPair() == NULL)
			throw new \Exception('SocialRecord can only be pushed with PersonalKeyPair configured');
		
		if(SocialRecordManager::socialRecordExists($entityAuthData->getGlobalID()))
			GSLS::putSocialRecord($entityAuthData->getSocialRecord(), $entityAuthData->getPersonalKeyPair()->getPrivateKey());
		else
			GSLS::postSocialRecord($entityAuthData->getSocialRecord(), $entityAuthData->getPersonalKeyPair()->getPrivateKey());
	}
	
	/**
	 * Retrieves a SocialRecord for a given GID from the GSLS or local cache
	 * throws Exception
	 * 
	 * @param $globalID String The globalID to resolve
	 * @param $skipCache Boolean Determin whether caching should be skipped or not
	 *
	 * @return SocialRecord if social record exists. Otherwise, an exception is thrown
	 */
	public static function retrieveSocialRecord($globalID, $skipCache = false)
	{
		if(Sonic::socialRecordCachingEnabled() === true && $skipCache === false)
		{
			$sr = Sonic::getSocialRecordCaching()->getSocialRecordFromCache($globalID);
			
			if($sr !== false)
			{
				$sr->verify();
				return $sr;
			}
		}
		
		return GSLS::getSocialRecord($globalID);
	}
	
	/**
	 * Checks if a SocialRecord is available in the GSLS for a given GID
	 * throws Exception
	 *
	 * return true if SocialRecord is available in the GSLS, otherwise false
	 */
	public static function socialRecordExists($globalID)
	{
		if(!GID::isValid($globalID))
		{
			throw new \Exception('Illegal GID format');
		}
		
		try
		{
			$result = GSLS::getSocialRecord($globalID);
		}
		catch (SocialRecordNotFoundException $e)
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * Exports a SocialRecord object to a serialized JSONObject
	 * 
	 * @param SocialRecord The SocialRecord to export
	 * @param KeyPair account key pair to export
	 * @param KeyPair personal key pair to export
	 * @return string The exported SocialRecord
	 */
	public static function exportSocialRecord(SocialRecord $socialRecord, KeyPair $accountKeyPair = NULL, KeyPair $personalKeyPair = NULL)
	{
		$json = new JSONObject();
		
		$json->put('socialRecord', $socialRecord->getJSONObject());
		
		if($accountKeyPair != NULL)
			$json->put('accountPrivateKey', PrivateKey::exportKey($accountKeyPair->getPrivateKey()));
		
		if($personalKeyPair != NULL)
			$json->put('personalPrivateKey', PrivateKey::exportKey($personalKeyPair->getPrivateKey()));
		
		return $json->write();
	}
	
	/**
	 * Imports a SocialRecord from a string resource
	 * 
	 * @param $source The string to parse
	 * @return array Array containting the SocialRecord object and optinally the KeyPair(s)
	 */
	public static function importSocialRecord($source)
	{
		$json = new JSONObject($source);
		
		$socialRecord = SocialRecordBuilder::buildFromJSON($json->get('socialRecord'));
		
		$result = array('socialRecord' => $socialRecord);
		
		if($json->has('accountPrivateKey'))
			$result['accountKeyPair'] = new KeyPair($json->get('accountPrivateKey'), $socialRecord->getAccountPublicKey());
		if($json->has('personalPrivateKey'))
			$result['personalKeyPair'] = new KeyPair($json->get('personalPrivateKey'), $socialRecord->getPersonalPublicKey());
		
		return $result;
	}
}

?>