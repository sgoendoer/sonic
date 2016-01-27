<?php namespace sgoendoer\Sonic\Config;

use sgoendoer\Sonic\Config\ConfigBuilder;

/**
 * Config
 * version 20160127
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class Config
{
	private static $_instance	= NULL;
	
	private $primaryGSLSNode	= NULL;
	private $secondaryGSLSNode	= NULL;
	private $apiPath			= NULL;
	private $timezone			= NULL;
	private $verbose			= NULL; // 0: log nothing, 1: log errors, 2: log info, 3: log everything
	private $logfile			= NULL;
	
	public function __construct(ConfigBuilder $builder)
	{
		$this->primaryGSLSNode = $builder->getPrimaryGSLSNode();
		$this->secondaryGSLSNode = $builder->getSecondaryGSLSNode();
		$this->timezone = $builder->getTimezone();
		$this->apiPath = $builder->getApiPath();
		$this->verbose = $builder->getVerbose();
		$this->logfile = $builder->getLogfile();
	}
	
	public static function getInstance()
	{
		if(self::$_instance === NULL)
			self::$_instance = (new ConfigBuilder())->build();
		
		return self::$_instance;
	}
	
	public static function primaryGSLSNode()
	{
		return Config::getInstance()->primaryGSLSNode;
	}
	
	public function setPrimaryGSLSNode($ipAddress)
	{
		$this->primaryGSLSNode = $ipAddress;
		return $this;
	}
	
	public static function secondaryGSLSNode()
	{
		return Config::getInstance()->secondaryGSLSNode;
	}
	
	public function setSecondaryGSLSNode($ipAddress)
	{
		$this->secondaryGSLSNode = $ipAddress;
		return $this;
	}
	
	public static function apiPath()
	{
		return Config::getInstance()->apiPath;
	}
	
	public function setApiPath($path)
	{
		$this->apiPath = $path;
		return $this;
	}
	
	public static function timezone()
	{
		return Config::getInstance()->timezone;
	}
	
	public function setTimezone($tz)
	{
		$this->timezone = $tz;
		return $this;
	}
	
	public static function verbose()
	{
		return Config::getInstance()->verbose;
	}
	
	public function setVerbose($verbose)
	{
		$this->verbose = $verbose;
		return $this;
	}
	
	public static function logfile()
	{
		return Config::getInstance()->logfile;
	}
	
	public function setLogfile($logfile)
	{
		$this->logfile = $logfile;
		return $this;
	}
}

?>