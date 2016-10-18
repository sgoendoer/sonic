<?php namespace sgoendoer\Sonic\AccessControl;

use sgoendoer\Sonic\AccessControl\AccessControlManager;
use sgoendoer\Sonic\AccessControl\AccessControlManagerException;
use sgoendoer\Sonic\AccessControl\AccessControlException;

/**
 * Interface for ContentAccessControlManager
 * version 20161018
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
	 * @param $gid the GlobalID of the user accessing the content
	 * @param $uoid the UOID of the content being accessed
	 * 
	 * @return boolean
	 */
	public function hasContentAccessPriviledges($gid, $uoid)
	{
		$rules = $this->loadAccessControlRulesForUOID($uoid);
		
		// starting off with base directive (deny is default)
		if($this->contentAccessBaseDirective == ContentAccessControlRuleObject::ACL_DIRECTIVE_ALLOW)
			$grantAccess = true;
		else
			$grantAccess = false;
		
		// sorting rules by index: index 0 has highest priority and will overwrite all other rules
		usort($rules, function($a, $b)
		{
			if($a->getIndex() == $b->getIndex()) return 0; // should never happen!
			return ($a->getIndex() < $b->getIndex()) ? -1 : 1;
		});
		
		foreach($rules as $rule)
		{
			// checking rules in the order of friends -> groups -> individual
			// check friends
			if($rule->getScope() == AccessControlRuleObject::ACR_SCOPE_FRIENDS)
			{
				try
				{
					if(Sonic::getAccessControlManager()->getFriendManager()->isAFriend($gid))
					{
						if($rule->getDirective() == AccessControlRuleObject::ACR_DIRECTIVE_ALLOW)
							$grantAccess = true;
						elseif($rule->getDirective() == AccessControlRuleObject::ACR_DIRECTIVE_DENY)
							$grantAccess = false;
						else
							$grantAccess = false;
					}
				}
				catch(AccessControlManagerException $e)
				{}
			}
			// check groups
			elseif($rule->getScope() == AccessControlRuleObject::ACR_SCOPE_GROUP)
			{
				try
				{
					if(Sonic::getAccessControlManager()->getGroupManager()->isInGroup($gid, $rule->getID()))
					{
						if($rule->getDirective() == AccessControlRuleObject::ACR_DIRECTIVE_ALLOW)
							$grantAccess = true;
						elseif($rule->getDirective() == AccessControlRuleObject::ACR_DIRECTIVE_DENY)
							$grantAccess = false;
						else
							$grantAccess = false;
					}
				}
				catch(AccessControlManagerException $e)
				{}
			}
			// check individual
			elseif($rule->getScope() == ContentAccessControlRuleObject::ACL_SCOPE_INDIVIDUAL)
			{
				if(in_array($gid, $rule->getID()))
				{
					if($rule->getDirective() == ContentAccessControlRuleObject::ACL_DIRECTIVE_ALLOW)
						$grantAccess = true;
					elseif($rule->getDirective() == ContentAccessControlRuleObject::ACL_DIRECTIVE_DENY)
						$grantAccess = false;
					else
						$grantAccess = false;
				}
			}
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