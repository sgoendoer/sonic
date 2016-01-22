<?php namespace sgoendoer\Sonic\Identity;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Config\Config;

class SocialRecordFormatException extends \Exception
{
	public function __construct($message = null, $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
		
		if(Config::verbose() <= 1)
			Sonic::getLogger()->addError($message);
	}
}

?>