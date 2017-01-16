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
	// create new keypairs
	$personalKeyPair = new KeyPair();
	$accountKeyPair = new KeyPair();
	
	// use SocialRecordBuilder to create new SocialRecord object
	$socialRecord = (new SocialRecordBuilder())
		->type(SocialRecord::TYPE_USER) // alternative: SocialRecord::TYPE_PLATFORM
		->salt(Random::getRandom)
		->accountPublicKey($accountKeyPair->getPublicKey())
		->personalPublicKey($personalKeyPair->getPublicKey())
		->displayName('Alice')
		->profileLocation('http://sonic-project.net/user/alice/')
		->build();
	
	// export the Social Record instance with keys to a JSONObject
	$exportedFull = SocialRecordManager::exportSocialRecord($socialRecord, $accountKeyPair, $personalKeyPair);
	
	// export only the public part (no keys)
	$exportedPublic = SocialRecordManager::exportSocialRecord($socialRecord);
	
	// in order to upload the SocialRecord to the GSLS, it needs to be passed as a EntityAuthData object
	$entityAuthData = new EntityAuthData($socialRecord, $accountKeyPair, $personalKeyPair);
	
	// upload to GSLS
	SocialRecordManager::pushToGSLS($entityAuthData);
	
	// retrieve SocialRecord from GSLS while ignoring local caches
	SocialRecordManager::retrieveSocialRecord($sociaLRecord->getGlobalID(), true);
}
catch (\Exception $e)
{
	echo "There has been an error: " . $e->getMessage() . "\n\n" . $e->getTraceAsString();
}

?>