<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use sgoendoer\Sonic\Crypt\KeyPair;
use sgoendoer\Sonic\Crypt\PublicKey;
use sgoendoer\Sonic\Crypt\PrivateKey;
use sgoendoer\Sonic\Crypt\Random;
use sgoendoer\Sonic\Crypt\Signature;

date_default_timezone_set('Europe/Berlin');

class CryptUnitTest extends PHPUnit_Framework_TestCase
{
	public $keypair1 = NULL;
	public $private1 = NULL;
	public $public1 = NULL;
	public $keypair2 = NULL;
	
	public function testKeyPair()
	{
		$this->keypair1 = new Keypair();
		$this->private1 = $this->keypair1->getPrivateKey();
		$this->public1 = $this->keypair1->getPublicKey();
		
		$this->keypair2 = new KeyPair($this->private1, $this->public1);
		
		$this->assertEquals($this->private1, $this->keypair2->getPrivateKey());
		$this->assertEquals($this->public1, $this->keypair2->getPublicKey());
	}
	
	public function testRandom()
	{
		$this->assertTrue(strlen(Random::getRandom()) == 16);
		$this->assertTrue(strlen(Random::getRandom(2)) == 2);
	}
	
	public function testSignature()
	{
		$this->keypair1 = new Keypair();
		$this->private1 = $this->keypair1->getPrivateKey();
		$this->public1 = $this->keypair1->getPublicKey();
		
		$this->keypair2 = new KeyPair($this->private1, $this->public1);
		
		$this->assertEquals(1, Signature::verifySignature('testmessage', $this->public1, Signature::createSignature('testmessage', $this->private1)));
		$this->assertEquals(1, Signature::verifySignature('testmessage', $this->keypair2->getPublicKey(), Signature::createSignature('testmessage', $this->private1)));
		$this->assertEquals(0, Signature::verifySignature('othertestmessage', $this->public1, Signature::createSignature('testmessage', $this->private1)));
	}
}

?>