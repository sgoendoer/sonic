<?php namespace sgoendoer\Sonic\AccessControl;

use sgoendoer\Sonic\AccessControl\AccessControlManager;
use sgoendoer\Sonic\AccessControl\AccessControlManagerException;
use sgoendoer\Sonic\AccessControl\AccessControlException;

/**
 * Interface for ContentAccessControlManager
 * version 20161017
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
abstract class ContentAccessController
{
	private $contentAccessBaseDirective			= NULL;
	
	public function __construct($baseDirective)
	{
		if($baseDirective == AccessControlManager::ACL_DIRECTIVE_ALLOW)
			$this->contentAccessBaseDirective = $baseDirective;
		else
			$this->contentAccessBaseDirective = AccessControlManager::ACL_DIRECTIVE_DENY;
	}
	
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
		
		if($aco->getDirective() == ContentAccessControlRuleObject::ACL_DIRECTIVE_ALLOW)
			$grantAccess = true;
		else
			$grantAccess = false;
		
		$ruleKeys = array_keys($aco->getRules());
		asort($ruleKeys);
		
		foreach($ruleKeys as $index)
		{
			$rule = $aco->getRule($index);
			
			// check individual
			if($rule->getScope() == ContentAccessControlRuleObject::ACR_SCOPE_INDIVIDUAL)
			{
				if(in_array($gid, $rule->getIDs()))
				{
					if($rule->getDirective() == ContentAccessControlRuleObject::ACR_DIRECTIVE_ALLOW)
						$grantAccess = true;
					elseif($rule->getDirective() == ContentAccessControlRuleObject::ACR_DIRECTIVE_DENY)
						$grantAccess = false;
				}
			}
			// check friends
			/*elseif($rule->getScope() == AccessControlRuleObject::ACR_SCOPE_FRIENDS)
			{
				if(in_array($gid, $rule->getIDs()))
				{
					if($rule->getDirective() == AccessControlRuleObject::ACR_DIRECTIVE_ALLOW)
						$grantAccess = true;
					elseif($rule->getDirective() == AccessControlRuleObject::ACR_DIRECTIVE_DENY)
						$grantAccess = false;
				}
			}*/
			// check groups
			/*elseif($rule->getScope() == AccessControlRuleObject::ACR_SCOPE_GROUP)
			{
				if(in_array($gid, $rule->getIDs()))
				{
					if($rule->getDirective() == AccessControlRuleObject::ACR_DIRECTIVE_ALLOW)
						$grantAccess = true;
					elseif($rule->getDirective() == AccessControlRuleObject::ACR_DIRECTIVE_DENY)
						$grantAccess = false;
				}
			}*/
		} 
		
		return $grantAccess;
	}
	
	/**
	 * loads the ContentAccessControlRuleObjects for a given $uoid from the data storage
	 * 
	 * @param $uoid The UOID of the content
	 * 
	 * @return array of ContentAccessControlRuleObjects, NULL if no rules were found
	 */
	protected abstract function loadAccessControlRulesForUOID($uoid);
	
	
	
	/**
	 * loads the ContentAccessControlRuleObjects for a given $gid from the data storage
	 * 
	 * @param $gid The GlobalID
	 * 
	 * @return array of ContentAccessControlRuleObjects, NULL if no rules were found
	 */
	protected abstract function loadAccessControlRulesForGID($gid);
}

?>