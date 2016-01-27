<?php namespace sgoendoer\Sonic\Config;

use sgoendoer\Sonic\Config\Config;

/**
 * Config
 * version 20160127
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class ConfigBuilder
{
	private $defaults = array(
		'primaryGSLSNode' => '130.149.22.135:4002',
		'secondaryGSLSNode' => '130.149.22.133:4002',
		'timezone' => 'Europe/Berlin',
		'apiPath' => '/sonic/',
		'verbose' => 0,
		'logfile' => 'sonic.log'
	);
	
	private $primaryGSLSNode	= NULL;
	private $secondaryGSLSNode	= NULL;
	private $apiPath			= NULL;
	private $timezone			= NULL;
	private $verbose			= NULL;
	private $logfile			= NULL;
	
	public function __construct()
	{
		
	}
	
	public function getPrimaryGSLSNode()
	{
		return $this->primaryGSLSNode;
	}
	
	public function primaryGSLSNode($ipAddress)
	{
		$this->primaryGSLSNode = $ipAddress;
		return $this;
	}
	
	public function getSecondaryGSLSNode()
	{
		return $this->secondaryGSLSNode;
	}
	
	public function secondaryGSLSNode($ipAddress)
	{
		$this->secondaryGSLSNode = $ipAddress;
		return $this;
	}
	
	public function getApiPath()
	{
		return $this->apiPath;
	}
	
	public function apiPath($path)
	{
		$this->apiPath = $path;
		return $this;
	}
	
	public function getTimezone()
	{
		return $this->timezone;
	}
	
	public function timezone($tz)
	{
		$this->timezone = $tz;
		return $this;
	}
	
	public function getVerbose()
	{
		return $this->verbose;
	}
	
	public function verbose($verbose)
	{
		$this->verbose = $verbose;
		return $this;
	}
	
	public function getLogfile()
	{
		return $this->logfile;
	}
	
	public function logfile($logfile)
	{
		$this->logfile = $logfile;
		return $this;
	}
	
	public function build()
	{
		if($this->primaryGSLSNode === NULL)
			$this->primaryGSLSNode = $this->defaults['primaryGSLSNode'];
		if($this->secondaryGSLSNode === NULL)
			$this->secondaryGSLSNode = $this->defaults['secondaryGSLSNode'];
		if($this->timezone === NULL)
			$this->timezone = $this->defaults['timezone'];
		if($this->apiPath === NULL)
			$this->apiPath = $this->defaults['apiPath'];
		if($this->verbose === NULL)
			$this->verbose = $this->defaults['verbose'];
		if($this->logfile === NULL)
			$this->logfile = $this->defaults['logfile'];
		
		return new Config($this);
	}
}

?>