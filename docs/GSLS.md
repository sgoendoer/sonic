# GSLS

SocialRecords in Sonic are published in a global distributed directory, the Global Social Lookup System (GSLS). The GSLS stores SocialRecords as signed JSON Web Token (JWT) files using RSA + SHA512 (RS512). Similar to a DNS server, a GSLS node needs to be configured on initialization of the Sonic SDK.

```sgoendoer\Sonic\Identity\SocialRecordManager``` provides functionality for resolving GlobalIDs. Direct communication with the GSLS is handled by ```sgoendoer\Sonic\Identity\GSLS```, which automatically signs and verifies all datasets.

## Example usage

```php
use sgoendoer\Sonic\Crypt\KeyPair;
use sgoendoer\Sonic\Identity\SocialRecord;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Identity\EntityAuthData;

// retrieve a SocialRecord from the GSLS
$socialRecord = SocialRecordManager::retrieveSocialRecord($globalID);

// Assuming, we have an exported SocialRecord with the signing keys
$importedSR = SocialRecordManager::importSocialRecord($exportedSR);

// From the SocialRecord and the key pairs an \Sonic\Identity\EntityAuthData object can be built.
// EntityAuthData objects hold both KeyPairs and the SocialRecord
$entityAuthData = new EntityAuthData($socialRecord, $accountKeyPair, $personalKeyPair);

// Now the result can be published via the GSLS
SocialRecordManager::pushToGSLS($entityAuthData);
```
