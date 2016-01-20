<?php namespace sgoendoer\Sonic\Model;

/**
 * Abstract class Builder
 * version 20151020
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
abstract class BasicObjectBuilder
{
	public function __construct()
	{}
	
	public abstract function build();
}

?>