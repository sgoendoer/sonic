<?php namespace sgoendoer\Sonic\Date;

/**
 * Creates and verifies XSD Datetime
 * version 20160104
 * 
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class XSDDateTime
{
	private static $xsd_regex = "/[1-9][0-9]{3}\-(0[1-9]|1[1-2])\-(0[1-9]|1[1-9]|2[])T(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])(Z|\+|\-(0[1-9]|1[1-2]):(0[1-9]|1[1-2]))/";
	
	/**
	 * @ deprectaed
	 */
	public static function validateXSDDateTime($date)
	{
		return self::isValid($date);
	}
	
	/**
	 * checks whether $date is a valid date and corrently formatted
	 * @param $date string containing the date to be checked
	 * @return bool
	 */
	public static function isValid($date)
	{
		if(self::validateXSDDateTimeFormat($date) && self::validateXSDDateTimeValue($date))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function getXSDDateTime($date = false)
	{
		if($date === false)
			$date = time();
			
		return date('c', $date);
	}
	
	public static function getUnixTimestamp($xsdDateTime)
	{
		//$date = self::split($xsdDateTime);
		//$unix = date();
		return strtotime($xsdDateTime);
	}
	
	public static function getXSDDate($date = false)
	{
		if($date === false)
			$date = date('c', time());
			
		$date = self::split($date);
		
		return $date['date'] . $date['offset'];
	}
	
	public static function getXSDTime($date = false)
	{
		if($date === false)
			$date = date('c', time());
			
		$date = self::split($date);
		
		return $date['time'] . $date['offset'];
	}
	
	public static function getXSDUTCOffset($date = false)
	{
		if($date === false)
			$date = date('c', time());
			
		$date = self::split($date);
		
		return $date['offset'];
	}
	
	public static function parseXSDDateTime($date)
	{
		//$timestamp = mktime([ int $hour = date("H") [, int $minute = date("i") [, int $second = date("s") [, int $month = date("n") [, int $day = date("j") [, int $year = date("Y")
	}
	
	private static function validateXSDDateTimeFormat($date)
	{
		if(preg_match(self::$xsd_regex, $date) === false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	private static function validateXSDDateTimeValue($date)
	{
		$date = self::split($date);
		
		if(!isset($date['month']) || !isset($date['day']) || !isset($date['year']))
			return false;
		
		/*if(!isset($date['hour']))
			$date['hour'] = NULL;
		
		if(!isset($date['minute']))
			$date['minute'] = NULL;
		
		if(!isset($date['second']))
			$date['second'] = NULL;*/
		
		if(mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']) !== false)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function split($xsdDateTime = NULL)
	{
		if($xsdDateTime == NULL)
			return NULL;
		
		$date = array();
		$date['xsd'] = $xsdDateTime;
		
		// 1111-11-11T11:11:11Z
		// 1111-11-11Z
		if(strpos($xsdDateTime, 'Z') !== false)
		{
			if(strpos($xsdDateTime, 'T') !== false)
			{
				$xsdDateTime = explode('T', $xsdDateTime);
				$date['date'] = $xsdDateTime[0];
				$date['time'] = explode('Z', $xsdDateTime[1])[0];
				$date['offset'] = "Z";
			}
			else
			{
				$date['date'] = explode('Z', $xsdDateTime)[0];
				$date['time'] = NULL;
				$date['offset'] = "Z";
			}
		}
		
		// 1111-11-11T11:11:11+11:11
		// 1111-11-11+11:11
		else
		{
			// 1111-11-11T11:11:11+11:11
			if(strpos($xsdDateTime, 'T') !== false)
			{
				$xsdDateTime = explode('T', $xsdDateTime);
				
				if(strpos($xsdDateTime[1], '+') !== false)
				{
					$date['offset'] = '+' . explode('+', $xsdDateTime[1])[1];
					$date['time'] = explode('+', $xsdDateTime[1])[0];
				}
				else
				{
					$date['offset'] = '-' . explode('-', $xsdDateTime[1])[1];
					$date['time'] = explode('-', $xsdDateTime[1])[0];
				}
				
				$date['date'] = $xsdDateTime[0];
			}
			// 1111-11-11+11:11
			else
			{
				if(strpos($xsdDateTime, '+') !== false)
				{
					$date['offset'] = '+' . explode('+', $xsdDateTime)[1];
					$date['date'] = explode('+', $xsdDateTime)[0];
				}
				else
				{
					// 1111-11-11-11:11 :(
					$xsdDateTime = explode('-', $xsdDateTime);
					
					$date['offset'] = '-' . $xsdDateTime[3];
					$date['date'] = $xsdDateTime[0] . '-' . $xsdDateTime[1] . '-' . $xsdDateTime[2];
				}
				
				$date['time'] = NULL;
			}
		}
		
		list($date['year'], $date['month'], $date['day']) = explode('-', $date['date']);
		if(isset($date['time']) && $date['time'] != NULL)
			list($date['hour'], $date['minute'], $date['second']) = explode(':', explode($date['offset'], $date['time'])[0]);
			
		return $date;
	}
}

?>