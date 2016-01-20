<?php namespace sgoendoer\Sonic\Tools;

/**
 * URL helper class
 * version 20160104
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class URL
{
	public static function getPortFromURL($url)
	{
		$url = self::getDomainFromProfileLocation($url);
		$url = explode(':', $url);
		
		if(count($url) == 1)
			return 80;
		else
			return $url[1];
	}
	
	/**
	 * extracts the domain from a URL
	 */
	public static function getDomainFromURL($url)
	{
		$url = str_replace(array('http://', 'https://'), '', $url);
		return explode('/', $url)[0];
	}
	
	public static function getPathFromURL($url)
	{
		$domain = self::getDomainFromProfileLocation($url);
		
		$path = str_replace($domain, '', $url);
		$path = str_replace(array('http://', 'https://'), '', $path);
		
		if($path == '') return '/';
		
		return $path;
	}
}

?>