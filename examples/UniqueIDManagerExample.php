<?php namespace sgoendoer\Sonic\examples;

use sgoendoer\Sonic\Crypt\IUniqueIDManager;

class UniqueIDManagerExample implements IUniqueIDManager
{
	private $registeredIDs = array(1, 2, 3, 4, 5);
	
	public function isIDRegistered($id)
	{
		if(in_array($id, $this->registeredIDs))
			return true;
		else
			return false;
	}
	
	public function registerID($id)
	{
		$this->registeredIDs[] = $id;
		
		return true;
	}
}

?>