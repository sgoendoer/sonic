<?php namespace sgoendoer\Sonic\AccessControl;

use sgoendoer\Sonic\AccessControl\AccessControlGroupManager;
use sgoendoer\Sonic\AccessControl\AccessControlException;
use sgoendoer\Sonic\AccessControl\AccessControlManagerException;
use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Model\AccessControlRuleObject;

/**
 * Abstract AccessControlManager
 * version 20161021
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
abstract class AccessControlManager
{
	const DIRECTIVE_DENY						= 'DENY';
	const DIRECTIVE_ALLOW						= 'ALLOW';
	
	private $accessControlGroupManager			= NULL;
	
	private $baseDirectiveInterface				= NULL;
	private $baseDirectiveContent				= NULL;
	
	/**
	 * constructor for AccessControlManager
	 */
	public function __construct($baseDirectiveInterface, $baseDirectiveContent, $acGroupManager = NULL)
	{
		if($baseDirectiveInterface == AccessControlManager::DIRECTIVE_DENY)
			$this->baseDirectiveInterface = $baseDirectiveInterface;
		else
			$this->baseDirectiveInterface = AccessControlManager::DIRECTIVE_ALLOW;
		
		if($baseDirectiveContent == AccessControlManager::DIRECTIVE_ALLOW)
			$this->baseDirectiveContent = $baseDirectiveContent;
		else
			$this->baseDirectiveContent = AccessControlManager::DIRECTIVE_DENY;
		
		if($acGroupManager != NULL)
			$this->setAccessControlGroupManager($acGroupManager);
	}
	
	public function setAccessControlGroupManager(AccessControlGroupManager $acGroupManager)
	{
		if(!array_key_exists('sgoendoer\Sonic\AccessControl\AccessControlGroupManager', class_parents($acGroupManager)))
		{
			throw new SonicRuntimeException('acGroupManager must extend goendoer\Sonic\AccessControl\AccessControlGroupManager');
		}
		else
			$this->accessControlGroupManager = $$acGroupMapaner;
		return $this;
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
		
		try
		{
			$rules = $this->loadAccessControlRulesForUOID($gid, $uoid);
			
			return $this->executeAccessControlRules($gid, $rules);
		}
		catch (AccessControlManagerException $e)
		{
			if($this->baseDirectiveInterface == AccessControlManager::DIRECTIVE_DENY)
				return false;
			else
				return true;
		}
	}
	
	/**
	 * determines if a globalID has access priviledges for a specific interface
	 * 
	 * @param $gid the GlobalID of the user accessing the interface
	 * @param $interface name of the interface being accessed or wildcard (*)
	 * 
	 * @return boolean
	 */
	public function hasInterfaceAccessPriviledges($gid, $interface, $accessMethod = '*')
	{
		if($interface == '')
			$interface = '*';
		
		// TODO implement checks for distiction for R/W/*
		
		try
		{
			$rules = $this->loadAccessControlRulesForInterface($gid, $interface);
			
			// filter out rules with wrong access type
			if($accessMethod == AccessControlRuleObject::ACCESS_TYPE_WRITE)
			{
				foreach($rules as $id => $rule)
				{
					if($rule->getAccessType() == AccessControlRuleObject::ACCESS_TYPE_READ)
						unset($rules[$id]);
				}
			}
			elseif($accessMethod == AccessControlRuleObject::ACCESS_TYPE_READ)
			{
				foreach($rules as $id => $rule)
				{
					if($rule->getAccessType() == AccessControlRuleObject::ACCESS_TYPE_WRITE)
						unset($rules[$id]);
				}
			}
			
			return $this->executeAccessControlRules($gid, $rules);
		}
		catch (AccessControlManagerException $e)
		{
			if($this->baseDirectiveInterface == AccessControlManager::DIRECTIVE_DENY)
				return false;
			else
				return true;
		}
	}
	
	private function executeAccessControlRules($gid, $rules)
	{
		$grantAccess = NULL;
		
		// if no rules were found, use base directive
		if(!is_array($rules) || count($rules) == 0)
			throw new AccessControlManagerException('No AccessControlRules');
		
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
					if($this->accessControlGroupManager == NULL) break;
					
					try
					{
						if($this->accessControlGroupManager->isInGroup($gid, $rule->getEntityID()))
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
	 * (access_type = 'R' OR access_type = '*')
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
	 * determines if a user is a friend
	 *
	 * @param $gid1 The GlobalID 
	 * @param $gid2 The GlobalID
	 * 
	 * @return boolean True if user $gid is a friend, else false
	 */
	abstract public function isAFriend($gid1, $gid2);
	
	/**
	 * determines if a user is a friend of a friend
	 * 
	 * @param $gid The GlobalID
	 * 
	 * @return boolean True if user $gid is a friend of a friend, else false
	 */
	//abstract public function isAFriendOfAFriend($gid);
}

?>