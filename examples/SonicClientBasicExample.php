<?php namespace sgoendoer\Sonic\examples;

require_once(__DIR__ . '/../vendor/autoload.php');

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Identity\EntityAuthData;
use sgoendoer\Sonic\Identity\SocialRecordManager;

try
{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// importing SocialRecord objects to work with
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// load SocialRecords from files to instatiaze the Sonic framework
	$srp = SocialRecordManager::importSocialRecord(file_get_contents(__DIR__ . '/data/SRPlatform.json'));
	$platformSocialRecord = $srp['socialRecord'];
	$platformAccountKeyPair = $srp['accountKeyPair'];
	$platformPersonalKeyPair = $srp['personalKeyPair'];
	
	$sra = SocialRecordManager::importSocialRecord(file_get_contents(__DIR__ . '/data/SRAlice.json'));
	$userSocialRecord = $sra['socialRecord'];
	$userAccountKeyPair = $sra['accountKeyPair'];
	$userPersonalKeyPair = $sra['personalKeyPair'];
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// initializing Sonic SDK
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// instantiaze the Sonic framework with the platform's SocialRecord
	$sonic = Sonic::initInstance(new EntityAuthData($platformSocialRecord, $platformAccountKeyPair, $platformPersonalKeyPair));
	Sonic::setUserAuthData(new EntityAuthData($userSocialRecord, $userAccountKeyPair));
	Sonic::setContext(Sonic::CONTEXT_USER);
}
catch (\Exception $e)
{
	die($e->getMessage() . "\n\n" . $e->getTraceAsString());
}

?>