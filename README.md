# Sonic

SOcial Network InterConnect

## System requirements

The Sonic SDK provides a complete toolset to ease the integration of the Sonic protocol into new and existing Online Social Network (OSN) platforms. It is written in PHP 5.6 and is fully compatible with PHP 7.0. The Sonic SDK can be installed via Composer or fetched directly from GitHub.

- system requirements
- PHP 5.6+ or PHP 7.0+
- OpenSSL 1.0.0+
- cURL 7.20.0+
- composer

## Installation

Install via composer:

```bash
composer install sgoendoer/sonic
````

or configure your ```composer.json``` like this:

```json
"require": {
   "sgoendoer/sonic": "0.1.7"
}
```

and run

```bash
composer update
```

## Configuration

On initialization of the Sonic class, a ```sgoendoer\Sonic\Config\Config``` instance must be passed. While a Config instance must be built using a ```sgoendoer\Sonic\Config\ConfigBuilder```, values can be left unset as they are set to default values.

```php
$configuration = (new ConfigBuilder)
	->timezone('Europe/Berlin')
	->verbose(1)
	->build();
```

### Configuration values

| name | type | description | default value |
| ---- | ---- | ----------- | ------------- |
| timezone | String | Timezone of the platform | "Europe/Berlin" |
| verbose | Integer | Level of verbosity in the logs (0: nothing, 5: everything) | 0 |
| logfile | String | filename to write logs to | "sonic.log" |
| apiPath | String | Path to the API endpoint | "/sonic/" |
| primaryGSLSNode | String | IP address of the primary GSLS node | "130.149.22.135:4002" |
| secondaryGSLSNode | String | IP address of the secondary GSLS node | "130.149.22.133:4002" |

## Initialization

```php
require_once(__DIR__ . '/vendor/autoload.php');

use sgoendoer\Sonic\Sonic;

use sgoendoer\Sonic\Config\Config;

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
	
	$configuration = (new ConfigBuilder)
						->timezone('Europe/Berlin')
						->verbose(1)
						->build();
	
	// Now, we can initialize the Sonic SDK. The SDK's context will be set to "platform" 
	// automatically
	
	$sonic = Sonic::initInstance($configuration, new EntityAuthData(
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
	$response = (new ProfileRequestBuilder())
		->$profileRequest->createGETProfile($globalID)->dispatch();
	$profile = ProfileObjectBuilder::buildFromJSON($response->getPayload());
	
	echo $profile->getJSONString() . "\n\n";
} catch (\Exception $e) {}
```
