<?php namespace sgoendoer\Sonic\Config;

/**
 * Config
 * version 20160121
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class Config
{
	const DEFAULT_PRIMARY_GSLS_ADDRESS	= '130.149.22.135:4002';
	const DEFAULT_SECONDARY_GSLS_ADDRESS	= '130.149.22.135:4002';
	
	private $primaryGSLSNode;
	private $secondaryGSLSNode;
	private $APIPath;
	private $timezone;
	
	public function __construct()
	{
		$this->primaryGSLSNode = $builder->getPrimaryGSLSNode();
		$this->secondaryGSLSNode = $builder->getSecondaryGSLSNode();
		$this->timezone = $builder->getTimezone();
		$this->APIPath = $builder->getAPIPath();
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
		return $this->GSLSAddress;
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
		return $this->APIPath;
	}
	
	public function timezone($tz)
	{
		$this->timezone = $tz;
		return $this;
	}
	
	public function build()
	{
		return new Config($this);
	}
}

?>