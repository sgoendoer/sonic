<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\ResponseObject;
use sgoendoer\Sonic\Model\BasicObjectBuilder;

/**
 * Builder class for a SonicResponse object
 * version 20151021
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ResponseObjectBuilder extends BasicObjectBuilder
{
	protected $responseCode = NULL;
	protected $message = NULL;
	protected $errorCode = NULL;
	protected $body = NULL; // type is JSONObject
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$jsonObject = json_decode($json);
		
		$builder = new ResponseObjectBuilder();
		
		$builder->responseCode($jsonObject->responseCode);
		
		if(property_exists($jsonObject, 'message')) $builder->message($jsonObject->message);
		if(property_exists($jsonObject, 'errorCode')) $builder->errorCode($jsonObject->errorCode);
		if(property_exists($jsonObject, 'body')) $builder->body(json_encode($jsonObject->body));
		
		return $builder->build();
	}
	
	public function responseCode($responseCode)
	{
		$this->responseCode = $responseCode;
		return $this;
	}
	
	public function getResponseCode()
	{
		return $this->responseCode;
	}
	
	public function message($message)
	{
		$this->message = $message;
		return $this;
	}
	
	public function getMessage()
	{
		return $this->message;
	}
	
	public function errorCode($errorCode)
	{
		$this->errorCode = $errorCode;
		return $this;
	}
	
	public function getErrorCode()
	{
		return $this->errorCode;
	}
	
	public function body($body)
	{
		/*if(gettype($body) == 'string')
			$body = json_decode($body);*/
		
		$this->body = $body;
		
		return $this;
	}
	
	public function getBody()
	{
		return $this->body;
	}
	
	public function build()
	{
		// TODO implement checks
		return new ResponseObject($this);
	}
}

?>