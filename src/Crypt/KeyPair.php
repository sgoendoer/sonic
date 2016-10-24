<?php namespace sgoendoer\Sonic\Crypt;

use sgoendoer\Sonic\Crypt\PublicKey;
use sgoendoer\Sonic\Crypt\PrivateKey;

/**
 * KeyPair class
 * version 20160104
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class KeyPair
{
	const DIGEST_ALG	= 'sha512';
	const KEY_BITS		= 4096;
	const KEY_TYPE		= OPENSSL_KEYTYPE_RSA;
	
	private $privateKey = NULL;
	private $publicKey  = NULL;
	
	public function __construct($privateKey = NULL, $publicKey = NULL)
	{
		if($privateKey == NULL && $publicKey == NULL)
			$this->createKeyPair();
		else
		{
			// TODO check for validity?
			$this->privateKey = new PrivateKey($privateKey);
			$this->publicKey = new PublicKey($publicKey);
		}
	}
	
	public function createKeyPair()
	{
		$config = array(
			"digest_alg" => KeyPair::DIGEST_ALG,
			"private_key_bits" => KeyPair::KEY_BITS,
			"private_key_type" => KeyPair::KEY_TYPE
		);
		
		$keyPair = openssl_pkey_new($config);
		$tmpPrivateKey = NULL;
		openssl_pkey_export($keyPair, $tmpPrivateKey);
		$this->privateKey = new PrivateKey($tmpPrivateKey);
		$keyDetails = openssl_pkey_get_details($keyPair);
		$this->publicKey = new PublicKey($keyDetails['key']);
	}
	
	public function getPublicKey()
	{
		if($this->publicKey == NULL)
			throw new \Exception('Error: KeyPair not initialized correctly');
		else return $this->publicKey->getPublicKey();
	}
	
	public function getPrivateKey()
	{
		if($this->privateKey == NULL)
			throw new \Exception('Error: KeyPair not initialized correctly');
		else return $this->privateKey->getPrivateKey();
	}
	
	public function setPublicKey($publicKey)
	{
		$this->publicKey = new PublicKey($publicKey);
	}
	
	public function setPrivateKey($privateKey)
	{
		$this->privateKey = new PrivateKey($privateKey);
	}
	
	public function encrypt($message)
	{
		$crypted = NULL;
		
		openssl_public_encrypt($message, $crypted, $this->publicKey->getPublicKey());
		
		return base64_encode($crypted);
	}
	
	public static function decrypt($crypted)
	{
		$message = NULL;
		
		openssl_private_decrypt(base64_decode($crypted), $message, $this->privateKey->getPrivateKey());
		
		return $message;
	}
	
	/**
	 * gets the linebreaks right
	 * UNUSED / DEPRECATED!
	 */
	public static function formatPEM($key, $type = 'public')
	{
		($type == 'public') ? $prefix = '-----BEGIN PUBLIC KEY-----' : '-----BEGIN PRIVATE KEY-----';
		($type == 'public') ? $postfix = '-----END PUBLIC KEY-----' : '-----END PRIVATE KEY-----';
		
		$key = str_replace($prefix, '', $key);
		$key = str_replace($postfix, '', $key);
		
		$lines = str_split($key, 65);
		$body = implode("\n", $lines);
		$result = $prefix . "\n";
		$result .= $body . "\n";
		$result .= $postfix;
		
		return $result;
	}
}

?>