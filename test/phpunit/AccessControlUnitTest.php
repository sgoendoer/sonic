<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use sgoendoer\Sonic\Sonic;

use sgoendoer\Sonic\AccessControl\AccessControlManager;

use sgoendoer\Sonic\Crypt\KeyPair;
use sgoendoer\Sonic\Crypt\PublicKey;
use sgoendoer\Sonic\Crypt\PrivateKey;
use sgoendoer\Sonic\Crypt\Random;
use sgoendoer\Sonic\Crypt\Signature;
use sgoendoer\Sonic\Crypt\IUniqueIDManager;

use sgoendoer\Sonic\Date\XSDDateTime;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Identity\GSLS;
use sgoendoer\Sonic\Identity\EntityAuthData;
use sgoendoer\Sonic\Identity\SocialRecord;
use sgoendoer\Sonic\Identity\SocialRecordBuilder;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Identity\KeyRevocationCertificate;
use sgoendoer\Sonic\Identity\KeyRevocationCertificateBuilder;
use sgoendoer\Sonic\Identity\ISocialRecordCaching;

class AccessControlUnitTest extends PHPUnit_Framework_TestCase
{
	public $acm = NULL;
	
	public function __construct()
	{
		$this->acm = new AccessControlManagerStub(AccessControlManager::DIRECTIVE_DENY, AccessControlManager::DIRECTIVE_DENY);
	}
	
	public function testAccessControlManager()
	{
		$gid = '2UZCAI2GM45T160MDN44OIQ8GKN5GGCKO96LC9ZOQCAEVAURA8';
		$uoid = '2UZCAI2GM45T160MDN44OIQ8GKN5GGCKO96LC9ZOQCAEVAURA8:a9ddbc2102bf86d1';
		
		$this->assertFalse($this->acm->hasContentAccessPriviledges($gid, $uoid));
		$this->assertFalse($this->acm->hasInterfaceAccessPriviledges($gid, 'person'));
		$this->assertTrue($this->acm->isAFriend($gid, $gid));
	}
	
	/*public function testAccessControlGroupManager()
	{
		$this->assertTrue($person->validate());
	}*/
}

class AccessControlManagerStub extends AccessControlManager
{
	protected function loadAccessControlRulesForUOID($gid, $uoid)
	{
		return array();
	}
	
	protected function loadAccessControlRulesForInterface($gid, $interface)
	{
		return array();
	}
	
	public function isAFriend($gid1, $gid2)
	{
		return true;
	}
}

?>