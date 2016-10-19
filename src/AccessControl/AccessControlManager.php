<?php namespace sgoendoer\Sonic\AccessControl;

use sgoendoer\Sonic\AccessControl\AccessControlException;
use sgoendoer\Sonic\AccessControl\AccessControlManagerException;
use sgoendoer\Sonic\Model\AccessControlRuleObject;

/**
 * Abstract AccessControlManager
 * version 20161019
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
abstract class AccessControlManager
{
	const DIRECTIVE_DENY						= 'DENY';
	const DIRECTIVE_ALLOW						= 'ALLOW';
	
	private $baseDirectiveInterface				= NULL;
	private $baseDirectiveContent				= NULL;
	
	/**
	 * constructor for AccessControlManager
	 */
	public function __construct($baseDirectiveInterface, $baseDirectiveContent)
	{
		if($baseDirectiveInterface == AccessControlManager::DIRECTIVE_DENY)
			$this->baseDirectiveInterface = $baseDirectiveInterface;
		else
			$this->baseDirectiveInterface = AccessControlManager::DIRECTIVE_ALLOW;
		
		if($baseDirectiveContent == AccessControlManager::DIRECTIVE_ALLOW)
			$this->baseDirectiveContent = $baseDirectiveContent;
		else
			$this->baseDirectiveContent = AccessControlManager::DIRECTIVE_DENY;
	}
	
	/**
	 * determines if a globalID has access priviledges for a specific resource
	 * 
	 * @param $gid the GlobalID of the user accessing the content
	 * @param $uoid UOID of the resource being accessed
	 * 
	 * @return boolean
	 */
	public function hasContentAccessPriviledges($gid, $uoid)
	{
		if(!UOID::isValid($uoid) && $uoid)
			throw new AccessControlManagerException('Illegal argument UOID: ' . $uoid);
		
		$rules = $this->loadAccessControlRulesForUOID($gid, $uoid);
		
		// starting off with base directive (deny is default)
		if($this->baseDirective == AccessControlRuleObject::DIRECTIVE_ALLOW)
			$grantAccess = true;
		else
			$grantAccess = false;
		
		// if no rules were found, use content base directive
		if(!is_array($rules) || count($rules) == 0)
			return $grantAccess;
		
		// sorting rules by index: rules with higher indexes overwrite rules with a lower index
		usort($rules, function($a, $b)
		{
			if($a->getIndex() == $b->getIndex())
			{
				// TODO sort by ENTITY_TYPE: ALL < FRIENDS < FOF < GROUP < INDIVIDUAL
				return 0;
			}
			
			return ($a->getIndex() < $b->getIndex()) ? -1 : 1;
		});
		
		foreach($rules as $rule)
		{
			// checking rules in the order of all -> friends -> groups -> individual
			switch($rule->getEntityType())
			{
				case AccessControlRuleObject::ENTITY_TYPE_ALL:
					try
					{
						if($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_ALLOW)
							$grantAccess = true;
						elseif($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_DENY)
							$grantAccess = false;
						else
							$grantAccess = false;
					}
					catch(AccessControlManagerException $e)
					{}
				break;
				
				case AccessControlRuleObject::ENTITY_TYPE_FRIENDS:
					try
					{
						if($this->isAFriend($gid))
						{
							if($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_ALLOW)
								$grantAccess = true;
							elseif($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_DENY)
								$grantAccess = false;
							else
								$grantAccess = false;
						}
					}
					catch(AccessControlManagerException $e)
					{}
				break;
				
				case AccessControlRuleObject::ENTITY_TYPE_GROUP:
					try
					{
						if($this->isInGroup($gid, $rule->getEntityID()))
						{
							if($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_ALLOW)
								$grantAccess = true;
							elseif($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_DENY)
								$grantAccess = false;
							else
								$grantAccess = false;
						}
					}
					catch(AccessControlManagerException $e)
					{}
				break;
				
				case AccessControlRuleObject::ENTITY_TYPE_INDIVIDUAL:
					if($rule->getEntityID() == $gid)
					{
						if($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_ALLOW)
							$grantAccess = true;
						elseif($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_DENY)
							$grantAccess = false;
						else
							$grantAccess = false;
					}
				break;
			}
		} 
		
		return $grantAccess;
	}
	
	/**
	 * determines if a globalID has access priviledges for a specific interface
	 * 
	 * @param $gid the GlobalID of the user accessing the interface
	 * @param $interface name of the interface being accessed or wildcard (*)
	 * 
	 * @return boolean
	 */
	public function hasInterfaceAccessPriviledges($gid, $interface)
	{
		if($interface == '')
			$interface = '*';
		
		$rules = $this->loadAccessControlRulesForInterface($gid, $interface);
		
		// starting off with base interface directive (allow is default)
		if($this->baseDirectiveInterface == AccessControlRuleObject::DIRECTIVE_DENY)
			$grantAccess = false;
		else
			$grantAccess = true;
		
		// if no rules were found, use interface base directive
		if(!is_array($rules) || count($rules) == 0)
			return $grantAccess;
		
		// sorting rules by index: rules with higher indexes overwrite rules with a lower index
		usort($rules, function($a, $b)
		{
			if($a->getIndex() == $b->getIndex())
			{
				// TODO sort by ENTITY_TYPE: ALL < FRIENDS < FOF < GROUP < INDIVIDUAL
				return 0;
			}
			
			return ($a->getIndex() < $b->getIndex()) ? -1 : 1;
		});
		
		foreach($rules as $rule)
		{
			// checking rules in the order of all -> friends -> groups -> individual
			switch($rule->getEntityType())
			{
				case AccessControlRuleObject::ENTITY_TYPE_ALL:
					try
					{
						if($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_ALLOW)
							$grantAccess = true;
						elseif($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_DENY)
							$grantAccess = false;
						else
							$grantAccess = false;
					}
					catch(AccessControlManagerException $e)
					{}
				break;
				
				case AccessControlRuleObject::ENTITY_TYPE_FRIENDS:
					try
					{
						if($this->isAFriend($gid))
						{
							if($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_ALLOW)
								$grantAccess = true;
							elseif($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_DENY)
								$grantAccess = false;
							else
								$grantAccess = false;
						}
					}
					catch(AccessControlManagerException $e)
					{}
				break;
				
				case AccessControlRuleObject::ENTITY_TYPE_GROUP:
					try
					{
						if($this->isInGroup($gid, $rule->getEntityID()))
						{
							if($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_ALLOW)
								$grantAccess = true;
							elseif($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_DENY)
								$grantAccess = false;
							else
								$grantAccess = false;
						}
					}
					catch(AccessControlManagerException $e)
					{}
				break;
				
				case AccessControlRuleObject::ENTITY_TYPE_INDIVIDUAL:
					if($rule->getEntityID() == $gid)
					{
						if($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_ALLOW)
							$grantAccess = true;
						elseif($rule->getDirective() == AccessControlRuleObject::DIRECTIVE_DENY)
							$grantAccess = false;
						else
							$grantAccess = false;
					}
				break;
			}
		} 
		
		return $grantAccess;
	}
	
	/**
	 * returns an array of all available AccessControlRuleObjects with TARGET_TYPE "CONTENT" and TARGET being $uoid 
	 * from data storage with ENTITY_ID matching $gid or wildcard (*). 
	 * 
	 * SELECT * FROM rules WHERE 
	 * target_type = 'CONTENT' 
	 * AND 
	 * (entity_id = '$gid' OR entity_id = '*')
	 * AND
	 * target = '$uoid'
	 * ORDER BY index ASC
	 * 
	 * @param $gid The GlobalID requesting entitiy
	 * @param $uoid The UOID of the content or wildcard (*)
	 * 
	 * @return array of AccessControlRuleObjects, NULL if no rules were found
	 */
	protected abstract function loadAccessControlRulesForUOID($gid, $uoid);
	
	/**
	 * returns an array of all available AccessControlRuleObjects with TARGET_TYPE "INTERFACE" and TARGET being 
	 * $interface from data storage with ENTITY_ID matching $gid or wildcard (*). 
	 * 
	 * SELECT * FROM rules WHERE 
	 * target_type = 'INTERFACE' 
	 * AND 
	 * (entity_id = '$gid' OR entity_id = '*')
	 * AND
	 * target = '$interface'
	 * ORDER BY index ASC
	 * 
	 * @param $gid The GlobalID requesting entitiy
	 * @param $interface The interface name of the content or wildcard (*)
	 * 
	 * @return array of AccessControlRuleObjects, NULL if no rules were found
	 */
	protected abstract function loadAccessControlRulesForInterface($gid, $interface);
	
	/**
	 * loads the AccessControlRuleObjects for a given $gid from the data storage
	 * 
	 * @param $gid The GlobalID
	 * 
	 * @return array of AccessControlRuleObjects, NULL if no rules were found
	 */
	//protected abstract function loadAccessControlRulesForGID($gid);
	
	
	abstract public function isAFriend($gid);
	
	//abstract public function getAllFriends();
	
	//abstract public function isAFriendOfAFriend($gid);
	
	//abstract public function getAllFriendOfFriends();
	
	//abstract public function getGroupsForGID($gid);
	
	abstract public function isInGroup($gid, $groupID);
	
	//abstract public function getMembersOfGroup($groupID);
	
	//abstract public function getGroups();
}

?>