<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\ObjectBuilder;
use sgoendoer\Sonic\Model\SignatureObject;

/**
 * builder class for remote sonic objects
 * version 20151014
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
abstract class RemoteObjectBuilder extends ObjectBuilder
{
	protected $signature = NULL;
	
	public function __construct()
	{}
	
	public function signature(SignatureObject $signature)
	{
		// TODO check signature (but not here)
		$this->signature = $signature;
		return $this;
	}
	
	public function getSignature()
	{
		return $this->signature;
	}
}

?>