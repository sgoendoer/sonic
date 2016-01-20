<?php namespace sgoendoer\Sonic\Crypt;

/**
 * PublicKey class
 * version 20160104
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class PublicKey
{
	private $publicKey = NULL;

	public function __construct($publicKey)
	{
		$this->publicKey = self::formatPEM($publicKey);
	}

	public function getPublicKey()
	{
		return $this->publicKey;
	}

	public function setPublicKey($publicKey)
	{
		$this->publicKey = self::formatPEM($publicKey);
	}

	public function encrypt($message)
	{
		$crypted = NULL;

		openssl_public_encrypt($message, $crypted, $this->publicKey);

		return base64_encode($crypted);
	}
	
	/**
	 * Exports the key in PKCS#1 PEM format
	 */
	public static function getPKCS1($key)
	{
		$prefix = '-----BEGIN RSA PUBLIC KEY-----';
		$postfix = '-----END RSA PUBLIC KEY-----';

		$key = str_replace($prefix, '', $key);
		$key = str_replace($postfix, '', $key);
		$key = str_replace("\n", '', $key);

		$lines = str_split($key, 65);
		$body = implode("\n", $lines);
		$result = $prefix . "\n";
		$result .= $body . "\n";
		$result .= $postfix;

		return $result;
	}
	
	/**
	 * Exports the key in PKCS#8 PEM format
	 */
	public static function getPKCS8($key)
	{
		$prefix = '-----BEGIN RSA PUBLIC KEY-----';
		$postfix = '-----END RSA PUBLIC KEY-----';

		$key = str_replace($prefix, '', $key);
		$key = str_replace($postfix, '', $key);
		$key = str_replace("\n", '', $key);

		$lines = str_split($key, 65);
		$body = implode("\n", $lines);
		$result = $prefix . "\n";
		$result .= $body . "\n";
		$result .= $postfix;

		return $result;
	}
	
	/**
	 * gets the linebreaks right
	 */
	public static function formatPEM($key)
	{
		$prefix = '-----BEGIN PUBLIC KEY-----';
		$postfix = '-----END PUBLIC KEY-----';

		$key = str_replace($prefix, '', $key);
		$key = str_replace($postfix, '', $key);
		$key = str_replace("\n", '', $key);

		$lines = str_split($key, 65);
		$body = implode("\n", $lines);
		$result = $prefix . "\n";
		$result .= $body . "\n";
		$result .= $postfix;

		return $result;
	}

	/*
	 * remove linebreaks for json export
	 */
	public static function exportKey($key)
	{
		//$key = str_replace("-----BEGIN PUBLIC KEY-----", "", $key);
		//$key = str_replace("-----END PUBLIC KEY-----", "", $key);
		$key = str_replace("\r", "", $key);
		$key = str_replace("\n", "", $key);
		$key = trim($key);

		return $key;
	}
}

?>
