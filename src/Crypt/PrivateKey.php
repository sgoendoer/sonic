<?php namespace sgoendoer\Sonic\Crypt;

/**
 * PrivateKey class
 * version 20160104
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class PrivateKey
{
	private $privateKey = NULL;
	
	public function __construct($privateKey)
	{
		$this->privateKey = self::formatPEM($privateKey);
	}
	
	public function getPrivateKey()
	{
		return $this->privateKey;
	}
	
	public function setPrivateKey($privateKey)
	{
		$this->privateKey = self::formatPEM($privateKey);
	}
	
	public function decrypt($crypted)
	{
		$message = NULL;
		
		openssl_private_decrypt(base64_decode($crypted), $message, $this->privateKey);
		
		return $message;
	}
	
	/**
	 * gets the linebreaks right
	 */
	public static function formatPEM($key)
	{
		$prefix = '-----BEGIN PRIVATE KEY-----';
		$postfix = '-----END PRIVATE KEY-----';
		
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
	
	public static function exportKey($key)
	{
		$key = str_replace("\r", "", $key);
		$key = str_replace("\n", "", $key);
		$key = trim($key);
		
		return $key;
	}
}

?>