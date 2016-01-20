<?php namespace sgoendoer\Sonic\Tools;

/**
 * @deprecated
 */
class JSONTools
{
	public static function createJSONObject()
	{
		return json_decode('{}');
	}
	
	public static function createJSONArray()
	{
		return json_decode('[]');
	}
	
	public static function coerceToJSON($value)
	{
		if(gettype($value) == 'object')
		{
			return $value;
		}
		else if(gettype($value) == 'string')
		{
			if($value == '')
			{
				return self::createJSONObject();
			}
			else if(self::containsValidJSON($value))
			{
				return json_decode($value);
			}
			else
			{
				throw new \Exception('Value ' . $value . ' cannot be mapped to JSON');
			}
		}
		else if(gettype($value) == 'array')
		{
			return json_decode(json_encode($value));
		}
		else
		{
			throw new \Exception('Value ' . $value . ' cannot be mapped to JSON');
		}
	}
	
	public static function getJSONError()
	{
		switch(json_last_error())
		{
			case JSON_ERROR_NONE:
				$error =  '';
			break;
			
			case JSON_ERROR_DEPTH:
				$error = 'The maximum stack depth has been exceeded.';
			break;
				
			case JSON_ERROR_STATE_MISMATCH:
				$error = 'Invalid or malformed JSON.';
			break;

			case JSON_ERROR_CTRL_CHAR:
				$error = 'Control character error, possibly incorrectly encoded.';
			break;

			case JSON_ERROR_SYNTAX:
				$error = 'Syntax error, malformed JSON.';
			break;

			// PHP >= 5.3.3
			case JSON_ERROR_UTF8:
				$error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
			break;

			// PHP >= 5.5.0
			case JSON_ERROR_RECURSION:
				$error = 'One or more recursive references in the value to be encoded.';
			break;

			// PHP >= 5.5.0
			case JSON_ERROR_INF_OR_NAN:
				$error = 'One or more NAN or INF values in the value to be encoded.';
			break;

			case JSON_ERROR_UNSUPPORTED_TYPE:
				$error = 'A value of a type that cannot be encoded was given.';
			break;

			default:
				$error = 'Unknown JSON error occured.';
			break;
		}
		
		return $error;
	}
	
	public static function containsValidJSON($string)
	{
		// decode the JSON data
		$result = json_decode($string);
		
		if(self::getJSONError() == '')
			return true;
		else
			return false;
		
		// switch and check possible JSON errors
		/*switch(json_last_error())
		{
			case JSON_ERROR_NONE:
				$error = ''; // JSON is valid // No error has occurred
			break;
			
			case JSON_ERROR_DEPTH:
				$error = 'The maximum stack depth has been exceeded.';
			break;
				
			case JSON_ERROR_STATE_MISMATCH:
				$error = 'Invalid or malformed JSON.';
			break;

			case JSON_ERROR_CTRL_CHAR:
				$error = 'Control character error, possibly incorrectly encoded.';
			break;

			case JSON_ERROR_SYNTAX:
				$error = 'Syntax error, malformed JSON.';
			break;

			// PHP >= 5.3.3
			case JSON_ERROR_UTF8:
				$error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
			break;

			// PHP >= 5.5.0
			case JSON_ERROR_RECURSION:
				$error = 'One or more recursive references in the value to be encoded.';
			break;

			// PHP >= 5.5.0
			case JSON_ERROR_INF_OR_NAN:
				$error = 'One or more NAN or INF values in the value to be encoded.';
			break;

			case JSON_ERROR_UNSUPPORTED_TYPE:
				$error = 'A value of a type that cannot be encoded was given.';
			break;

			default:
				$error = 'Unknown JSON error occured.';
			break;
		}
		
		if ($error !== '') {
			// throw the Exception or exit // or whatever :)
			//exit($error);
			return false;
		}
		
		// everything is OK
		return true;//$result;*/
	}
}

?>