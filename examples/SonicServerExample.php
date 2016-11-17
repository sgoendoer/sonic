<?php namespace sgoendoer\Sonic\examples;

require_once(__DIR__ . '/../vendor/autoload.php');

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Configuration\Configuration;

use sgoendoer\Sonic\Identity\EntityAuthData;
use sgoendoer\Sonic\Identity\SocialRecordManager;

use sgoendoer\Sonic\AccessControl\AccessControlManager;
use sgoendoer\Sonic\AccessControl\AccessControlException;

use sgoendoer\Sonic\Request\IncomingRequest;

use sgoendoer\Sonic\Api\ResponseBuilder;
use sgoendoer\Sonic\Api\MethodNotAllowedException;

use sgoendoer\Sonic\Model\PersonObjectBuilder;
use sgoendoer\Sonic\Model\LikeObjectBuilder;

use sgoendoer\Sonic\examples\SocialRecordCachingExample;
use sgoendoer\Sonic\examples\AccessControlManagerExample;
use sgoendoer\Sonic\examples\UniqueIDManagerExample;

// all requests to the Sonic API need to be redirected to be handled by this script

// clean url functionality: the script expects the request path to be passed as a variable $urlpath
$targetedGID	= NULL;
$resource		= NULL;
$resourceID		= NULL;
$subresource	= NULL;
$subresourceID	= NULL;

if($_REQUEST['urlpath'] != '')
{
	$requestURL = explode('/', $_REQUEST['urlpath']);
	
	$targetedGID	= $requestURL[0];
	
	if(count($requestURL) >= 2)
		$resource	= strtoupper($requestURL[1]);
	
	if(count($requestURL) >= 3)
		$resourceID	= $requestURL[2];
	
	if(count($requestURL) >= 4)
		$subresource	= strtolower($requestURL[3]);
	
	if(count($requestURL) >= 5)
		$subresourceID	= $requestURL[4];
	
	if(count($requestURL) >= 6)
		$subsubresource	= $requestURL[5];
}

try
{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// setting Configuration
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	Configuration::setLogfile('sonic.log');
	Configuration::setApiPath('/sonic-api-endpoint/');
	Configuration::setTimezone('Europe/Berlin');
	Configuration::setPrimaryGSLSNode('130.149.22.135:4002');
	
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
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// setting up managers
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// setting a AccessPermissionManager
	$sonic->setAccessControlManager(new AccessControlManagerExample(AccessControlManager::DIRECTIVE_ALLOW, AccessControlManager::DIRECTIVE_DENY));
	
	// setting up SocialRecordCaching
	$sonic->setSocialRecordCaching(new SocialRecordCacheExample());
	
	// setting up UniqueIDManagement
	$sonic->setUniqueIDManager(new UniqueIDManagerExample());
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// request handling
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$request = new IncomingRequest();
	
	$globalIDAlice = '4802C8DE6UZZ5BICQI830A8P8BW3YB5EBPGXWNRH1EP7H838V7';
	
	// for demo purposes, we are only handling requests for Alice
	if($targetedGID != $globalIDAlice)
	{
		$response = new ResponseBuilder(404);
		$response->init('Unknown GlobalID: ' . $targetedGID);
		$response->dispatch();
		die();
	}
	
	// check access permissions for the addressed interface
	if(!Sonic::getAccessControlManager()->hasInterfaceAccessPriviledges($request->getHeaderSourceGID(), $resource, $request->getMethod()))
		throw new AccessControlException();
	
	switch(strtolower($resource))
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// resource PERSON
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		case 'person':
			if($request->getMethod() == 'GET')
			{
				$personObject = PersonObjectBuilder::buildFromJSON(file_get_contents(__DIR__ . '/data/AlicePerson.json'));
				
				// check access permissions for the content object
				if(!Sonic::getAccessControlManager()->hasContentAccessPriviledges($request->getHeaderSourceGID(), $personObject->getObjectID()))
					throw new AccessControlException();
				
				$response = new ResponseBuilder(200);
				$response->init()->setBody($personObject->getJSON());
				$response->dispatch();
			}
			else
			{
				throw new MethodNotAllowedException();
			}
		break;
		
		case 'like':
			if($request->getMethod() == 'POST')
			{
				// building LIKE object from request body data
				$likeObject = LikeObjectBuilder::buildFromJSON($request->getBody());
				
				// here, we would store the received object in the database
				
				$response = new ResponseBuilder(200);
				$response->init('like object received: [' . $likeObject->getObjectID() . ']');
				$response->dispatch();
			}
			else
			{
				throw new MethodNotAllowedException();
			}
		break;
		
		default:
			$response = new ResponseBuilder(404);
			$response->init('Resource ' . $resource . ' not found');
			$response->dispatch();
		break;
	}
}
catch (AccessControlException $e)
{
	$response = new ResponseBuilder(403);
	$response->init('access denied');
	$response->dispatch();
}
catch (MethodNotAllowedException $e)
{
	$response = new ResponseBuilder(405);
	$response->init('method not allowed for resource ' . $resource);
	$response->dispatch();
}
catch (\Exception $e)
{
	$response = new ResponseBuilder(500);
	$response->init('internal server error');
	$response->dispatch();
}

?>