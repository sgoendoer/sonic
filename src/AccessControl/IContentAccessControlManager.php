<?php namespace sgoendoer\Sonic\AccessControl;

use sgoendoer\Sonic\AccessControl\AccessControlManager;
use sgoendoer\Sonic\AccessControl\AccessControlManagerException;

/**
 * Interface for ContentAccessControlManager
 * version 20161013
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
abstract class AContentAccessController
{
	/**
	 * determines if a globalID has access priviledges for a specific content object
	 * 
	 * @param $gid the GlobalID
	 * @param $resource The resource name as a string
	 * 
	 * @return boolean
	 */
	public function hasContentAccessPriviledges($gid, $uoid)
	{
		$aco = $this->loadAccessControlRulesForUOID($uoid);
		//if false -> exception
		
		if($aco->getDirective() == ContentAccessControlObject::ACL_DIRECTIVE_ALLOW)
			$grantAccess = true;
		else
			$grantAccess = false;
		
		$ruleKeys = array_keys($aco->getRules());
		asort($ruleKeys);
		
		foreach($ruleKeys as $index)
		{
			$rule = $aco->getRule($index);
			
			// check individual
			if($rule->getScope() == AccessControlRuleObject::ACR_SCOPE_INDIVIDUAL)
			{
				if(in_array($gid, $rule->getIDs()))
				{
					if($rule->getDirective() == AccessControlRuleObject::ACR_DIRECTIVE_ALLOW)
						$grantAccess = true;
					elseif($rule->getDirective() == AccessControlRuleObject::ACR_DIRECTIVE_DENY)
						$grantAccess = false;
				}
			}
			// check friends
			elseif($rule->getScope() == AccessControlRuleObject::ACR_SCOPE_FRIENDS)
			{
				if(in_array($gid, $rule->getIDs()))
				{
					if($rule->getDirective() == AccessControlRuleObject::ACR_DIRECTIVE_ALLOW)
						$grantAccess = true;
					elseif($rule->getDirective() == AccessControlRuleObject::ACR_DIRECTIVE_DENY)
						$grantAccess = false;
				}
			}
			// check groups
			elseif($rule->getScope() == AccessControlRuleObject::ACR_SCOPE_GROUP)
			{
				if(in_array($gid, $rule->getIDs()))
				{
					if($rule->getDirective() == AccessControlRuleObject::ACR_DIRECTIVE_ALLOW)
						$grantAccess = true;
					elseif($rule->getDirective() == AccessControlRuleObject::ACR_DIRECTIVE_DENY)
						$grantAccess = false;
				}
			}
		} 
		
		return $grantAccess;
	}
	
	/**
	 * loads the AccessControlObject for a given $uoid from the data storage
	 * 
	 * @param $uoid The UOID the AccessControlObject targets
	 * 
	 * @return AccessControlObject, NULL if no rule was founf
	 */
	protected abstract function loadAccessControlRulesForUOID($uoid);
}

?>