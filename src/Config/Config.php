<?php namespace sgoendoer\Sonic\Config;

/**
 * Config
 * version 20160122
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class Config
{
	private static $_instance	= NULL;
	
	private $primaryGSLSNode	= NULL;
	private $secondaryGSLSNode	= NULL;
	private $APIPath			= NULL;
	private $timezone			= NULL;
	private $verbose			= NULL; // 0: log nothing, 1: log errors, 2: log info, 3: log everything
	private $logfile			= NULL;
	
	public function __construct(ConfigBuilder $builder)
	{
		$this->primaryGSLSNode = $builder->getPrimaryGSLSNode();
		$this->secondaryGSLSNode = $builder->getSecondaryGSLSNode();
		$this->timezone = $builder->getTimezone();
		$this->APIPath = $builder->getAPIPath();
		$this->verbose = $builder->getVerbose();
		$this->logfile = $builder->getLogfile();
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
		return Config::getInstance()->GSLSAddress;
	}
	
	public function setSecondaryGSLSNode($ipAddress)
	{
		$this->SecondaryGSLSNode = $ipAddress;
		return $this;
	}
	
	public static function APIPath()
	{
		return Config::getInstance()->APIPath;
	}
	
	public function setAPIPath($path)
	{
		$this->APIPath = $path;
		return $this;
	}
	
	public static function timezone()
	{
		return Config::getInstance()->APIPath;
	}
	
	public function setTimezone($tz)
	{
		$this->timezone = $tz;
		return $this;
	}
	
	public function verbose()
	{
		return $this->verbose;
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