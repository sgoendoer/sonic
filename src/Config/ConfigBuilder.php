<?php namespace sgoendoer\Sonic\Config;

use sgoendoer\Sonic\Config\Config;

/**
 * Config
 * version 20160122
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
		'APIPath' => '/api/',
		'verbose' => 0,
		'logfile' => 'sonic.log'
	);
	
	private $primaryGSLSNode	= NULL;
	private $secondaryGSLSNode	= NULL;
	private $APIPath			= NULL;
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
		$this->SecondaryGSLSNode = $ipAddress;
		return $this;
	}
	
	public function getAPIPath()
	{
		return $this->APIPath;
	}
	
	public function APIPath($path)
	{
		$this->APIPath = $path;
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
		if($this->APIPath === NULL)
			$this->APIPath = $this->defaults['APIPath'];
		if($this->verbose === NULL)
			$this->verbose = $this->defaults['verbose'];
		if($this->logfile === NULL)
			$this->logfile = $this->defaults['logfile'];
		
		return new Config($this);
	}
}

?>