<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use sgoendoer\Sonic\Crypt\KeyPair;
use sgoendoer\Sonic\Crypt\PublicKey;
use sgoendoer\Sonic\Crypt\PrivateKey;

date_default_timezone_set('Europe/Berlin');

class KeyPairUnitTest extends PHPUnit_Framework_TestCase
{
	public function testKeyPair()
	{
		$keypair1 = new Keypair();
		$private1 = $keypair1->getPrivateKey();
		$public1 = $keypair1->getPublicKey();
		
		$keypair2 = new KeyPair($private1, $public1);
		
		$this->assertEquals($private1, $keypair2->getPrivateKey());
		$this->assertEquals($public1, $keypair2->getPublicKey());
	}
}

?>