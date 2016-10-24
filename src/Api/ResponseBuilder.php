<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Request\OutgoingResponse;
use sgoendoer\Sonic\Model\ResponseObjectBuilder;
use sgoendoer\Sonic\Model\Object;

/**
 * Creates an outgoing response
 * version 20150821
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ResponseBuilder
{
	private		$initialized	= false;
	protected	$sonicResponse	= NULL;
	protected	$response		= NULL;
	
	public function __construct($responseStatusCode = 200)
	{
		$this->initialized = false;
		//$socialRecord = \Sonic\Identity\SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->response = new OutgoingResponse();
		
		$this->response->setResponseStatusCode($responseStatusCode);
		
		//die($body);
		//echo $response->toString();die();
		return $this;
	}
	
	public function init($responseObjectMessage = '', $responseObjectErrorCode = 0, $responseObjectResponseCode = 0)
	{
		if($this->initialized == true)
			throw new \Exception('ResponseBuilder already initialized');
		
		if($responseObjectResponseCode == 0)
		{
			// TODO set responseCode to 
			$this->sonicResponse = (new ResponseObjectBuilder())
									->responseCode($this->response->getResponseStatusCode())
									->build();
		}
		else
		{
			$this->sonicResponse = (new ResponseObjectBuilder())
									->responseCode($this->response->getResponseStatusCode())
									->build();
		}
		
		if($responseObjectMessage != '')
			$this->sonicResponse->setMessage($responseObjectMessage);
		if($responseObjectErrorCode != 0)
			$this->sonicResponse->setErrorCode($responseObjectErrorCode);
		
		$this->initialized = true;
		
		return $this;
	}
	
	/**
	 * Sets the payload for the response. This needs to be an object that inherits from BasicObject
	 */
	public function setBody($responseBody)
	{
		if($this->initialized !== true)
			throw new \Exception('ResponseBuilder not initialized. Call init() first');
		
		// TODO make sure, this can only be a string or a BaseObject
		// TODO throws an error when not valid JSON - how to deal with this?
		if(gettype($responseBody) != 'string')
			$responseBody = $responseBody->getJSONString();
		
		$this->sonicResponse->setBody($responseBody);
		
		return $this;
	}
	
	public function dispatch()
	{
		if(!$this->initialized)
			throw new \Exception('ResponseBuilder not initialized. Call init() first');
		
		$this->response->setResponseBody($this->sonicResponse->getJSONString());
		//echo "verifying response with key from GID ".Sonic::getContextGlobalID();
		$this->response->signResponse(Sonic::getContextAccountKeyPair()->getPrivateKey());
		$this->response->send();
	}
}

?>