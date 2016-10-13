<?php namespace sgoendoer\Sonic\Permission;

use sgoendoer\Sonic\Config\Configuration;

class PermissionManagerException extends \Exception
{
	public function __construct($message = null, $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
		
		if(Configuration::getVerbose() >= 1)
			Sonic::getLogger()->addError($message);
	}
}

?>