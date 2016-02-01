<?php namespace sgoendoer\Sonic\Config;

/**
 * Configuration
 * version 20160201
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class Configuration
{
	private static $primaryGSLSNode		= '130.149.22.135:4002';
	private static $secondaryGSLSNode	= '130.149.22.133:4002';
	private static $apiPath				= '/sonic/';
	private static $timezone			= 'Europe/Berlin';
	private static $verbose				= 0; // 0: log nothing, 1: log errors, 2: log info, 3: log everything
	private static $curlVerbose			= 0;
	private static $requestTimeout		= 10;
	private static $gslsTimeout			= 4;
	private static $logfile				= 'sonic.log';
	
	private function __construct() {}
	private function __clone() {}
	
	public static function setConfiguration($config)
	{
		if(!is_array($config))
			throw new ConfigurationException('Configuration needs to be an array');
		
		if(array_key_exists('primaryGSLSNode')) self::$primaryGSLSNode = $config['primaryGSLSNode'];
		if(array_key_exists('secondaryGSLSNode')) self::$secondaryGSLSNode = $config['secondaryGSLSNode'];
		
		if(array_key_exists('apiPath')) self::$apiPath = $config['apiPath'];
		if(array_key_exists('timezone')) self::$timezone = $config['timezone'];
		if(array_key_exists('verbose')) self::$verbose = $config['verbose'];
		if(array_key_exists('curlVerbose')) self::$curlVerbose = $config['curlVerbose'];
		if(array_key_exists('requestTimeout')) self::$requestTimeout = $config['requestTimeout'];
		if(array_key_exists('gslsTimeout')) self::$gslsTimeout = $config['gslsTimeout'];
		if(array_key_exists('logfile')) self::$logfile = $config['logfile'];
	}
	
	public static function getPrimaryGSLSNode()
	{
		return self::$primaryGSLSNode;
	}
	
	public static function setPrimaryGSLSNode($ipAddress)
	{
		self::$primaryGSLSNode = $ipAddress;
	}
	
	public static function getSecondaryGSLSNode()
	{
		return self::$secondaryGSLSNode;
	}
	
	public static function setSecondaryGSLSNode($ipAddress)
	{
		self::$secondaryGSLSNode = $ipAddress;
	}
	
	public static function getApiPath()
	{
		return self::$apiPath;
	}
	
	public static function setApiPath($path)
	{
		self::$apiPath = $path;
	}
	
	public static function getTimezone()
	{
		return self::$timezone;
	}
	
	public static function setTimezone($tz)
	{
		self::$timezone = $tz;
	}
	
	public static function getVerbose()
	{
		return self::$verbose;
	}
	
	public static function setVerbose($verbose)
	{
		self::$verbose = $verbose;
	}
	
	public static function getCurlVerbose()
	{
		return self::$curlVerbose;
	}
	
	public static function setCurlVerbose($curlVerbose)
	{
		self::$curlVerbose = $curlVerbose;
	}
	
	public static function getRequestTimeout()
	{
		return self::$requestTimeout;
	}
	
	public static function setRequestTimeout($requestTimeout)
	{
		self::$requestTimeout = $requestTimeout;
	}
	
	public static function getGSLSTimeout()
	{
		return self::$gslsTimeout;
	}
	
	public static function setGSLSTimeout($gslsTimeout)
	{
		self::$gslsTimeout = $gslsTimeout;
	}
	
	public static function getLogfile()
	{
		return self::$logfile;
	}
	
	public static function setLogfile($logfile)
	{
		self::$logfile = $logfile;
	}
}

?>