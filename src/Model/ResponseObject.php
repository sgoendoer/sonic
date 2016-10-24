<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\BasicObject;
use sgoendoer\Sonic\Model\ResponseObjectBuilder;

/**
 * Represents a Response object
 * version 20151021
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ResponseObject extends BasicObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'response';
	
	protected $responseCode = NULL;
	protected $message = NULL;
	protected $errorCode = NULL;
	protected $body = NULL; // JSON String !!!
	
	public function __construct(ResponseObjectBuilder $builder)
	{
		$this->setResponseCode($builder->getResponseCode());
		$this->setMessage($builder->getMessage());
		$this->setErrorCode($builder->getErrorCode());
		
		if($builder->getBody() != NULL)
			$this->setBody($builder->getBody());
	}
	
	public function setResponseCode($responseCode)
	{
		$this->responseCode = $responseCode;
	}
	
	public function getResponseCode()
	{
		return $this->responseCode;
	}
	
	public function setMessage($message)
	{
		$this->message = $message;
	}
	
	public function getMessage()
	{
		return $this->message;
	}
	
	public function setErrorCode($errorCode)
	{
		$this->errorCode = $errorCode;
	}
	
	public function getErrorCode()
	{
		return $this->errorCode;
	}
	
	public function setBody($body)
	{
		$this->body = $body;
	}
	
	public function getBody()
	{
		return json_encode($this->body, JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES);//$this->body;
	}
	
	public function getJSONString()
	{
		$json =  '{'
			. '"@context":"' . ResponseObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . ResponseObject::JSONLD_TYPE . '",'
			. '"responseCode":' . $this->responseCode . '';
		if($this->message != NULL) $json .= ', "message":"' . $this->message . '"';
		if($this->errorCode != NULL) $json .= ', "errorCode":' . $this->errorCode . '';
		if($this->body != NULL) $json .= ', "body":' . $this->body;
		$json .= '}';
		
		return $json;
	}
}

?>