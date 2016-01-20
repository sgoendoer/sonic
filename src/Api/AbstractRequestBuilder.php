<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Request\IncomingResponse;

/**
 * Creates and verifies signatures
 * version 20150818
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
abstract class AbstractRequestBuilder
{
	protected $request = NULL;
	protected $response = NULL;
	
	protected function sendHttpGETRequest($host, $path, $headers = NULL)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($host);
		$this->request->setPath($path);
		$this->request->setRequestMethod('GET');
		//die($body);
		//echo $this->request->toString();die();
		return $this;
	}
	
	protected function sendHttpPOSTRequest($host, $path, $body = NULL, $headers = NULL)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($host);
		$this->request->setPath($path);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($body);
		//die($body);
		//echo $this->request->toString();die();
		return $this;
	}
	
	protected function sendHttpPUTRequest($host, $path, $body = NULL, $headers = NULL)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($host);
		$this->request->setPath($path);
		$this->request->setRequestMethod('PUT');
		$this->request->setRequestBody($body);
		//die($body);
		//echo $this->request->toString();die();
		return $this;
	}
	
	protected function sendHttpDELETERequest($host, $path, $headers = NULL)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($host);
		$this->request->setPath($path);
		$this->request->setRequestMethod('DELETE');
		//die($body);
		//echo $this->request->toString();die();
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
		$this->response = new IncomingResponse($this->request->send());
		
		return $this->response;
	}
}

?>