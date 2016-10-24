<?php namespace sgoendoer\Sonic\Model;

/**
 * Abstract class Builder
 * version 20151020
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
abstract class BasicObjectBuilder
{
	public function __construct()
	{}
	
	public abstract function build();
}

?>