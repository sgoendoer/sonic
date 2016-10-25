# Sonic

### SOcial Network InterConnect

The Sonic SDK provides a complete toolset to ease the integration of the Sonic protocol into new and existing Online Social Network (OSN) platforms. It is written in PHP 5.6 and is fully compatible with PHP 7.0. The Sonic SDK can be installed via Composer or fetched directly from GitHub.

## System requirements

- PHP 5.6+ or PHP 7.0+
- OpenSSL 1.0.0+
- cURL 7.20.0+
- composer

## Changelog

#### 0.2.5
- Added more unit tests
- Added feature negotiation functionality
- Various fixes and improvements

#### 0.2.0
- Added more unit tests
- Added migration functionality
- Added search functionality
- Various fixes and improvements

#### 0.1.9
- Added unit tests
- Added logging support
- Code cleanup
- Removed unused classes/functions
- Various fixes and improvements

#### 0.1.0
- Initial release

## Installation

Install via composer with

```bash
$ composer require sgoendoer/sonic
````

or configure your ```composer.json``` like this:

```json
"require": {
	"sgoendoer/sonic": "0.2.5"
}
```

and run

```bash
$ composer update
```

## Configuration

For the configuration of the Sonic class, the ```sgoendoer\Sonic\Config\Configuration``` can be populated with the desired values. Values, which are not set explicitly will stay at their default values.

```php
Configuration::setTimezone('Europe/Berlin')
Configuration::setVerbose(1);
```

### Configuration values

| name | type | description | default value |
| ---- | ---- | ----------- | ------------- |
| timezone | String | Timezone of the platform | "Europe/Berlin" |
| verbose | Integer | Level of verbosity in the logs (0: nothing, 5: everything) | 0 |
| curlVerbose | Integer | Level of verbosity for curl requests (0: nothing, 1: everything, 2: also connections to third party services) | 0 |
| requestTimeout | Integer | Timeout for Sonic requests in seconds | 10 |
| gslsTimeout | Integer | Timeout for GSLS requests in seconds | 4 |
| logfile | String | filename to write logs to | "sonic.log" |
| apiPath | String | Path to the API endpoint | "/sonic/" |
| primaryGSLSNode | String | IP address of the primary GSLS node | "130.149.22.135:4002" |
| secondaryGSLSNode | String | IP address of the secondary GSLS node | "130.149.22.133:4002" |

## Initialization

```php
<?php

require_once(__DIR__ . '/vendor/autoload.php');

use sgoendoer\Sonic\Sonic;

use sgoendoer\Sonic\Config\Configuration;

use sgoendoer\Sonic\Identity\EntityAuthData;
use sgoendoer\Sonic\Identity\SocialRecord;
use sgoendoer\Sonic\Identity\SocialRecordManager;

try {
	// Sonic requires the \Sonic\Identity\SocialRecord of the Sonic platform for 
	// initialization. Here, we import one from a String resource using 
	// \Sonic\Identity\SocialRecordManager
	
	$platformSR = '{"socialRecord":{"@context": --truncated-- }';
	
	$sr = SocialRecordManager::importSocialRecord($platformSR);
	$platformSocialRecord = $sr['socialRecord'];
	$platformAccountKeyPair = $sr['accountKeyPair'];
	$platformPersonalKeyPair = $sr['personalKeyPair'];
	
	// We do the same for a user "Alice's" \Sonic\Identity\SocialRecord
	
	$aliceSR = '{"socialRecord":{"@context": --truncated -- }';
	
	$sr = SocialRecordManager::importSocialRecord($aliceSR);
	$userSocialRecord = $sr['socialRecord'];
	$userAccountKeyPair = $sr['accountKeyPair'];
	$userPersonalKeyPair = $sr['personalKeyPair'];
	
	// Before we can initialize the Sonic SDK, we need to pass a few configuration parameters.
	// Parameters we don't set explicitly will be set to default values.
	
	Configuration::setTimezone('Europe/Berlin');
	Configuration::setVerbose(1);
	
	// Now, we can initialize the Sonic SDK. The SDK's context will be set to "platform" 
	// automatically
	
	$sonic = Sonic::initInstance(new EntityAuthData(
									$platformSocialRecord,
									$platformAccountKeyPair,
									$platformPersonalKeyPair));
											
	// From this point on, the Sonic SDK is fully initialized. Anyhow, the context must be
	// set to "user" in order to perform requests in the context of a user:
	
	Sonic::setUserAuthData(
		new EntityAuthData($userSocialRecord, $userAccountKeyPair));
	Sonic::setContext(Sonic::CONTEXT_USER);
	
	// Now we can perform a request to another user's profile using a GlobalID
	
	$globalID = '28B6TE8T9NUO202C5NZIUTNQSP88E70B8JAWH4FQ58OJOB8LIF';
	
	$response = (new ProfileRequestBuilder($globalID))
				->createGETProfile()
				->dispatch();
	$profile = ProfileObjectBuilder::buildFromJSON($response->getPayload());
	
	echo $profile->getJSONString() . "\n\n";
} catch (\Exception $e) {
}
?>
```

## Documentation

- [Sonic introduction](docs/Sonic.md)
- [Sonic architecture](docs/Architecture.md)
- [User identification](docs/UserIdentification.md)
- [Sonic content model](docs/ContentModel.md)

## Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/sgoendoer/sonic/issues)
