<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Request\MalformedRequestException;
use sgoendoer\Sonic\Request\IncomingResponse;
use sgoendoer\Sonic\Request\URL;

/**
 * Creates and verifies signatures
 * version 20160129
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
abstract class AbstractRequestBuilder
{
	protected $targetGID = NULL;
	protected $targetSocialRecord = NULL;
	
	protected $request = NULL;
	protected $response = NULL;
	
	public function __construct($targetGID)
	{
		if(!GID::isValid($targetGID))
			throw new MalformedRequestException('Invalid GlobalID: [' . $targetGID . ']');
		
		$this->targetGID = $targetGID;
		
		try
		{
			$this->targetSocialRecord = SocialRecordManager::retrieveSocialRecord($this->targetGID);
		}
		catch (\Exception $e)
		{
			throw new MalformedRequestException('Could not resolve GlobalID: [' . $this->targetGID . ']');
		}
	}
	
	protected function sendHttpGETRequest($host, $path, $headers = NULL)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($host);
		$this->request->setPath($path);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	protected function sendHttpPOSTRequest($host, $path, $body = NULL, $headers = NULL)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($host);
		$this->request->setPath($path);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($body);
		
		return $this;
	}
	
	protected function sendHttpPUTRequest($host, $path, $body = NULL, $headers = NULL)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($host);
		$this->request->setPath($path);
		$this->request->setRequestMethod('PUT');
		$this->request->setRequestBody($body);
		
		return $this;
	}
	
	protected function sendHttpDELETERequest($host, $path, $headers = NULL)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($host);
		$this->request->setPath($path);
		$this->request->setRequestMethod('DELETE');
		
		return $this;
	}
	
	/**
	 * extracts the domain from a URL
	 */
	protected function getDomainFromProfileLocation($profileLocation)
	{
		$profileLocation = str_replace(array('http://', 'https://'), '', $profileLocation);
		return explode('/', $profileLocation)[0];
	}
	
	protected function getPathFromProfileLocation($profileLocation)
	{
		$domain = $this->getDomainFromProfileLocation($profileLocation);
		
		$path = str_replace($domain, '', $profileLocation);
		$path = str_replace(array('http://', 'https://'), '', $path);
		
		if($path == '') return '/';
		
		return $path;
	}
	
	public function dispatch()
	{
		$this->request->signRequest(Sonic::getContextAccountKeyPair()->getPrivateKey());
		$this->response = new IncomingResponse($this->request->send(), $this->targetGID);
		
		return $this->response;
	}
}

?>