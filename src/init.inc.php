<?php namespace sgoendoer\Sonic;

use sgoendoer\Sonic\Config\Configuration;

if(version_compare(PHP_VERSION, '5.6.0') < 0)
{
	// 5.5 introduced hash_pbkdf2(). 5.5 for some reason still incompatible
	die('SONIC SDK requires PHP 5.6 or higher to run. Your version: ' . PHP_VERSION . "\n\n");
}

if(version_compare(explode(' ', OPENSSL_VERSION_TEXT)[1], '1.0.0') < 0)
{
	// private key headers were changed after 0.9.8n
	die('SONIC SDK requires OpenSSL 1.0.0 or higher to run. Your version: ' . OPENSSL_VERSION_TEXT . "\n\n");
}

if(!function_exists('curl_version'))
{
	// we need the curl extension
	die('SONIC SDK requires cURL to be installed.'."\n\n");
}

date_default_timezone_set(Configuration::getTimezone());

define('SONIC_HEADER__TARGET_API',		'SonicTargetAPI');
define('SONIC_HEADER__DATE',			'SonicResourceDate');
define('SONIC_HEADER__PLATFORM_GID',	'SonicPlatformGID');
define('SONIC_HEADER__SOURCE_GID',		'SonicSourceGID');
define('SONIC_HEADER__SIGNATURE',		'SonicSignature');
define('SONIC_HEADER__RANDOM',			'SonicRandom');
define('SONIC_HEADER__AUTH_TOKEN',		'SonicAuthToken');

define('SONIC_SDK__APP_NAME',			'SonicSDK');
define('SONIC_SDK__APP_VERSION',		'0.3.0');
define('SONIC_SDK__APP_VERSION_NAME',	'beta3.0');
define('SONIC_SDK__API_VERSION',		'0.1.2');

define('SONIC_REQUEST__USERAGENT',		SONIC_SDK__APP_NAME . '/' . SONIC_SDK__APP_VERSION);

?>