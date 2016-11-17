<?php namespace sgoendoer\Sonic\examples;

use sgoendoer\Sonic\AccessControl\AccessControlGroupManager;
use sgoendoer\Sonic\AccessControl\AccessControlException;
use sgoendoer\Sonic\AccessControl\AccessControlManagerException;
use sgoendoer\Sonic\Model\AccessControlRuleObject;
use sgoendoer\Sonic\AccessControl\AccessControlManager;

class AccessControlManagerExample extends AccessControlManager
{
	/**
	 * for demonstration purposes, only one rule is created for a specific data object for a specific user
	 * 
	 * @param $gid The GlobalID requesting entitiy
	 * @param $uoid The UOID of the content or wildcard (*)
	 * 
	 * @return array of AccessControlRuleObjects, NULL if no rules were found
	 */
	protected function loadAccessControlRulesForUOID($gid, $uoid)
	{
		$rules = array();
		
		if($gid == '28B6TE8T9NUO202C5NZIUTNQSP88E70B8JAWH4FQ58OJOB8LIF' && $uoid =='4802C8DE6UZZ5BICQI830A8P8BW3YB5EBPGXWNRH1EP7H838V7:a9ddbc2102bf86d1')
		{
			$rules[] = (new AccessControlRuleObjectBuilder())
							->owner(Sonic::getContextGlobalID())
							->index(1)
							->directive(AccessControlRuleObject::DIRECTIVE_ALLOW)
							->entityType(AccessControlRuleObject::ENTITY_TYPE_INDIVIDUAL)
							->entityID('28B6TE8T9NUO202C5NZIUTNQSP88E70B8JAWH4FQ58OJOB8LIF') // use Bob's GID
							->targetType(AccessControlRuleObject::TARGET_TYPE_CONTENT)
							->target('4802C8DE6UZZ5BICQI830A8P8BW3YB5EBPGXWNRH1EP7H838V7:a9ddbc2102bf86d1') // Alice's person object
							->accessType(AccessControlRuleObject::WILDCARD)
							->build();
		}
		
		return $rules;
	}
	
	/**
	 * for demonstration purposes, only a single rule is read from a file
	 * 
	 * @param $gid The GlobalID requesting entitiy
	 * @param $interface The interface name of the content or wildcard (*)
	 * 
	 * @return array of AccessControlRuleObjects, NULL if no rules were found
	 */
	protected function loadAccessControlRulesForInterface($gid, $interface)
	{
		$rules = array();
		
		if($gid == '28B6TE8T9NUO202C5NZIUTNQSP88E70B8JAWH4FQ58OJOB8LIF' && $interface == 'person')
		{
			
			$rules[] = AccessControlRuleObjectBuilder::buildFromJSON(file_get_contents('data/AliceInterfacePersonRule.json'));
		}
		
		return $rules;
	}
	
	/**
	 * hardcoded friendship of alice and bob
	 *
	 * @param $gid1 The GlobalID 
	 * @param $gid2 The GlobalID
	 * 
	 * @return boolean True if user $gid is a friend, else false
	 */
	public function isAFriend($gid1, $gid2)
	{
		if($gid1 == '4802C8DE6UZZ5BICQI830A8P8BW3YB5EBPGXWNRH1EP7H838V7' && $gid2 == '28B6TE8T9NUO202C5NZIUTNQSP88E70B8JAWH4FQ58OJOB8LIF') return true;
	}
}

?>