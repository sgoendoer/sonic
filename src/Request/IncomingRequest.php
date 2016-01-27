<?php namespace sgoendoer\Sonic\Request;

use sgoendoer\Sonic\Config\Configuration;
use sgoendoer\Sonic\Request\AbstractRequest;

/**
 * IncomingRequest
 * version 20160104
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class IncomingRequest extends AbstractRequest
{
	public function __construct()
	{
		$this->server = $_SERVER['SERVER_NAME']; // <- domain
		if($_SERVER['SERVER_PORT'] != 80)
 			$this->server .= ':' . $_SERVER['SERVER_PORT'];
		$this->port = $_SERVER['SERVER_PORT'];
		$this->path = $_SERVER['REQUEST_URI']; // PATH
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->headers = getallheaders();
		//$this->body = http_get_request_body();
		$this->body = @file_get_contents('php://input');

		$this->verifyRequest();
	}

	/**
	 * extracts the GID from the path
	 */
	public function getTargetedGID()
	{
		return explode('/', str_replace(Configuration::getApiPath(), '', $this->path))[0];
	}

	/**
	 * extracts the API call from the path
	 */
	/*public function getAPICall()
	{
		// we assume that the API root is known
		$api_root = '/path/to/api/';// <-- should be configured somewhere

		$tmp = str_replace($APIRoot, '', $this->path);
		$tmp = explode('/', $tmp);
		unset($tmp[0]);

		return implode('/', $tmp);
	}*/
}

?>
