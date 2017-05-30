<?php namespace sgoendoer\Sonic\examples;

require_once(__DIR__ . '/../vendor/autoload.php');

use sgoendoer\Sonic\Crypt\KeyPair;
use sgoendoer\Sonic\Crypt\Random;
use sgoendoer\Sonic\Identity\EntityAuthData;
use sgoendoer\Sonic\Identity\SocialRecord;
use sgoendoer\Sonic\Identity\SocialRecordBuilder;
use sgoendoer\Sonic\Identity\SocialRecordManager;

try
{
	//////////////////////////////////////////////////////////////////////////////
	// create a new SocialRecord for a platform
	//////////////////////////////////////////////////////////////////////////////
	
	// create new keypairs
	$personalKeyPairPlatform = new KeyPair();
	$accountKeyPairPlatform = new KeyPair();
	
	// use SocialRecordBuilder to create new SocialRecord object
	$socialRecordPlatform = (new SocialRecordBuilder())
		->type(SocialRecord::TYPE_PLATFORM)
		->salt(Random::getRandom())
		->accountPublicKey($accountKeyPairPlatform->getPublicKey())
		->personalPublicKey($personalKeyPairPlatform->getPublicKey())
		->displayName('Platform A')
		->profileLocation('http://sonic-project.net/sonic/')
		->build();
	
	echo "Your new platform SocialRecord is:\n----------\n" . $socialRecordPlatform . "\n----------\n";
	
	//////////////////////////////////////////////////////////////////////////////
	// create a new SocialRecord for a user Alice
	//////////////////////////////////////////////////////////////////////////////
	
	// create new keypairs
	$personalKeyPair = new KeyPair();
	$accountKeyPair = new KeyPair();
	
	// use SocialRecordBuilder to create new SocialRecord object
	$socialRecord = (new SocialRecordBuilder())
		->type(SocialRecord::TYPE_USER)
		->salt(Random::getRandom())
		->platformGID($socialRecordPlatform->getGlobalID()) // using the platform SocialRecord's GlobalID
		->accountPublicKey($accountKeyPair->getPublicKey())
		->personalPublicKey($personalKeyPair->getPublicKey())
		->displayName('Alice')
		->profileLocation('http://sonic-project.net/sonic/alice/')
		->build();
	
	echo "Your new user SocialRecord is:\n----------\n" . $socialRecord . "\n----------\n";
	
	// export the Social Record instance with keys to a JSONObject
	$exportedFull = SocialRecordManager::exportSocialRecord($socialRecord, $accountKeyPair, $personalKeyPair);
	
	// export only the public part (no keys)
	$exportedPublic = SocialRecordManager::exportSocialRecord($socialRecord);
	
	// in order to upload the SocialRecord to the GSLS, it needs to be passed as a EntityAuthData object. EntityAuthData objects are containers for data and keypairs.
	$entityAuthData = new EntityAuthData($socialRecord, $accountKeyPair, $personalKeyPair);
	
	// the data stored in the GSLS is the SocialRecord formatted as a signed JWT. In case you need to access it, you can do it via
	$rawJWT = $entityAuthData->getJWT();
	
	echo "The JWT for your user SocialRecord is:\n----------\n" . $rawJWT . "\n----------\n";
	
	// upload to GSLS
	SocialRecordManager::pushToGSLS($entityAuthData);
	
	// retrieve SocialRecord from GSLS while ignoring local caches
	$retrievedSocialRecord = SocialRecordManager::retrieveSocialRecord($socialRecord->getGlobalID(), true);
}
catch (\Exception $e)
{
	echo "There has been an error: " . $e->getMessage() . "\n\n" . $e->getTraceAsString();
}
?>
