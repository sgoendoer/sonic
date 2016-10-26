<?php namespace sgoendoer\Sonic\Config;

/**
 * Configuration
 * @version 20160201
 *
 * @author Sebastian Goendoer
 * @copyright Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
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
	
	/**
	 * Sets configuration values
	 * 
	 * @param $config Array The configuration value
	 */
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
	
	/**
	 * returns the primary GSLS node address
	 */
	public static function getPrimaryGSLSNode()
	{
		return self::$primaryGSLSNode;
	}
	
	/**
	 * Sets the primary GSLS node's IP address
	 * 
	 * @param $ipAddress String The IP Address
	 */
	public static function setPrimaryGSLSNode($ipAddress)
	{
		self::$primaryGSLSNode = $ipAddress;
	}
	
	/**
	 * returns the secondary GSLS node address
	 */
	public static function getSecondaryGSLSNode()
	{
		return self::$secondaryGSLSNode;
	}
	
	/**
	 * Sets the secondary GSLS node's IP address
	 * 
	 * @param $ipAddress String The IP Address
	 */
	public static function setSecondaryGSLSNode($ipAddress)
	{
		self::$secondaryGSLSNode = $ipAddress;
	}
	
	/**
	 * returns the API path
	 */
	public static function getApiPath()
	{
		return self::$apiPath;
	}
	
	/**
	 * Sets the API path
	 * 
	 * @param $apiPath String The API path
	 */
	public static function setApiPath($path)
	{
		self::$apiPath = $path;
	}
	
	/**
	 * returns the timezone
	 */
	public static function getTimezone()
	{
		return self::$timezone;
	}
	
	/**
	 * Sets the timezone
	 * 
	 * @param $tz String The timezone
	 */
	public static function setTimezone($tz)
	{
		self::$timezone = $tz;
	}
	
	/**
	 * returns the verbose value
	 */
	public static function getVerbose()
	{
		return self::$verbose;
	}
	
	/**
	 * Sets the verbose value
	 * 
	 * @param $verbose int The verbose value
	 */
	public static function setVerbose($verbose)
	{
		self::$verbose = $verbose;
	}
	
	/**
	 * returns the CURL verbose value
	 */
	public static function getCurlVerbose()
	{
		return self::$curlVerbose;
	}
	
	/**
	 * Sets the CURL verbose value
	 * 
	 * @param $curlVerbose int The CURL verbose value
	 */
	public static function setCurlVerbose($curlVerbose)
	{
		self::$curlVerbose = $curlVerbose;
	}
	
	/**
	 * returns the request timeout value
	 */
	public static function getRequestTimeout()
	{
		return self::$requestTimeout;
	}
	
	/**
	 * Sets the request timeout
	 * 
	 * @param $requestTimeout int The request timeout
	 */
	public static function setRequestTimeout($requestTimeout)
	{
		self::$requestTimeout = $requestTimeout;
	}
	
	/**
	 * returns the GSLS timeout value
	 */
	public static function getGSLSTimeout()
	{
		return self::$gslsTimeout;
	}
	
	/**
	 * Sets the GSLS timeout
	 * 
	 * @param $gslsTimeout int The GSLS timeout
	 */
	public static function setGSLSTimeout($gslsTimeout)
	{
		self::$gslsTimeout = $gslsTimeout;
	}
	
	/**
	 * returns the logfile path
	 */
	public static function getLogfile()
	{
		return self::$logfile;
	}
	
	/**
	 * Sets the logfile path
	 * 
	 * @param $logfile String The logfile path
	 */
	public static function setLogfile($logfile)
	{
		self::$logfile = $logfile;
	}
}

?>
