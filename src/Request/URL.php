<?php namespace sgoendoer\Sonic\Request;

/**
 * URL helper class
 * version 20160125
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class URL
{
	/**
	 * Extracts the port from a given $url
	 * 
	 * @param $url string The URL
	 * 
	 * @return integer the port number. If not specified or invalid, the default http port 80 is returned.
	 */
	public static function getPortFromURL($url)
	{
		$url = self::getDomainFromProfileLocation($url);
		$url = explode(':', $url);
		
		if(count($url) == 1)
		{
			return 80;
		}
		else
		{
			if(!is_numeric($url[1]))
				return 80;
			else
				return (integer) $url[1];
		}
	}
	
	/**
	 * Extracts the domain from a URL
	 * 
	 * @param $url string The URL
	 * 
	 * @return string The domain
	 */
	public static function getDomainFromURL($url)
	{
		$url = str_replace(array('http://', 'https://'), '', $url);
		return explode('/', $url)[0];
	}
	
	/**
	 * Extracts the path from a URL
	 * 
	 * @param $url string The URL
	 * 
	 * @return string The path
	 */
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