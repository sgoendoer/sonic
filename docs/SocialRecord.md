# SocialRecord

In SONIC, every user and every platform is identified by a globally unique identifier, the GlobalID. by resolving the GlobalID via the GSLS, the actual location of a user's account can be determined. Information about the actual profile's location, as well as other information required for verification of authenticity and integrity are stored in a dataset, the SocialRecord.

## SocialRecord keys

Each entity in Sonic has two RSA key pairs. While the PersonalKeyPair is used to derive the GlobalID and sign content in the GSLS, the AccountKeyPair is used to sign and verify all communication data within Sonic. While the PersonalKeyPair can never be changed, AccountKeyPairs can be revoked and exchanged with a new kay pair.

## SocialRecord format

A SocialRecord comprises the following information:

| name | type | description |
| ---- | ---- | ----------- |
| @context | String | Fixed value "http://sonic-project.net/" |
| @type | String | Fixed value "socialrecord" |
| type | String | Determines whether the SocialRecord is describing a user or a platform. Can be either "user" or "platform" |
| globalID | String | The GlobalID for this SocialRecord |
| platformGID | String | The GlobalID of the associated platform. If "type" is "platform", this a self-reference |
| profileLocation | String | The URL of the Sonic platform |
| displayName | String  | Human readable displayable name of the accounts owner |
| personalPublicKey | PKCS#8 formatted RSA public key without linebreaks | |
| accountPublicKey | PKCS#8 formatted RSA public key without linebreaks | |
| datetime | String | XSDDateTime of the last change to the SocialRecord |
| salt | String | 16 character salt |
| active | Integer | 0: inactive, 1: active, 2: migration in progress |
| keyRevocationList | JSONArray | List of revoked account key pairs |

## SocialRecord example

```json
{
	"@context": "http://sonic-project.net/",
	"@type": "socialrecord",
	"type": "user",
	"globalID": "4802C8DE6UZZ5BICQI830A8P8BW3YB5EBPGXWNRH1EP7H838V7",
	"platformGID": "2UZCAI2GM45T160MDN44OIQ8GKN5GGCKO96LC9ZOQCAEVAURA8",
	"displayName": "Alice",
	"profileLocation": "http://social.snet.tu-berlin.de/sonic-sdk/",
	"personalPublicKey":"-----BEGIN PUBLIC KEY-----MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAzMp0mukaLQl2Ya0RmZKtioXx3gifTe6Bu2UUsrOgwd/SHB3g438pcJBqF8pvPKKhx0hgp8MX1W3IGyqsNsIbFF2b4r9VrtDqUUd0WBCKsvBNcqxfqWkez2kVB+Q3hQkOjyocuO8I6v1rvkFNsio0E9XLPcLOiYJL3qHrbQFI+qtshfgjeK9taZbrEX6uY4VQ602fb8dHK9ieCV/W46RCTQS4+ac1+y1CAyH7gQ5TPMZ2vraeLR4kA1r8l/u3ZhB8b8biMt81K/WVcEf+8K4LAi/Tub1uDowKU2HNveG5ov055hvbvYv/9z1kEFGpTEMOzl0hiK4DGkvpugVO9nUfyy7VA85ZgkBpY4WoHGoZQbubyBsMwqpmT1pkUwAQTKnv6ME1YLLY81YjeshQz+YezT/gqH0uC3a+ZcQotFanNyTvQrtjxQSqeOA87K1RwfJvn9QS4Lz3MMt8eSK1/H+aFavDBARgzAGPgDRBTtjKSvdImZ1g5zd9pItzGV9ZcasvTY3/m6U5L9ByiiFEHLQJr9eKBb0OAoQVG9G5vYQ1f1CF7OtYQA2L0ygc4TwwLCjILBJDoqiOuYgq/wVzE1200G1tQ504hLdaUJIETCLxvDhyMI3TbywxLSyihYjC3Tge68X+rKPgZoY4ahTok0CszOzYf4lDsYmKAPIVDH5C5AECAwEAAQ==-----END PUBLIC KEY-----",
	"accountPublicKey":"-----BEGIN PUBLIC KEY-----MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAvgeptoYIVDvxPPIk4ZBpRy/SohpJPTHSguP3VjWG0xL8zllZSkdBLS1ijBGCxG/jx5KebBSLSkIAjEdby0/FKRjLTBqC7eak6s/1zUUzoABXwu/JckAToOJK5R/iSwAd5jOa94Bl6q7Pu6kfBsDSg43JOkIU0rvfMNcgdo/9GJwI2tg6/ZjM5YWNoRcHvl2XXM0lljJrxfVlXpWNhTUUoy/IrnyPhBlhHCXtbCVo/U5gQ5O6ymqwewRyWwhfvaWrWiwAW6KnvBz3ddCmjBArerOciVtcSXRoJ01jQP4HeDTzQDxvDb4ymAewfoRuzp0ctL4tMMS8P+XxpQmNrivZP+thwEM+jB8XLkbFB1Pj0aTdQzCkrJupiSz8mK5aBBptjPsek50egoOEyf5LY3y/daup5rbLFLE58pNO13GdtDiin0NDVwrC19uKrvy5vca/+O1lZjTaVNrP9FN9ug2wZ62N7Q6vyZT0H+7wkdbGWeKhxMa1j05dx0v61dp91k6N4wWXEeAknLw06FrCKicKjA6LQIVrT9KYjWDI9ewwaoKbRyrKJrHNoZg1+Mua+x3TWsXAEGE91+Mgdd0UZQ+XBihjyq76ccZULbzJ/flGeOyVUMwXg05QNT6zXRDyNUPUyb8HoI0kHlT8nRDF9kkKu2Yx40EhsrAQogoEqgxeYoUCAwEAAQ==-----END PUBLIC KEY-----",
	"salt": "abb0afd289f102f3",
	"datetime": "2016-01-13T10:58:54+01:00",
	"active": 1,
	"keyRevocationList": []
}
```

## Example usage

```php
use sgoendoer\Sonic\Crypt\KeyPair;
use sgoendoer\Sonic\Identity\SocialRecord;
use sgoendoer\Sonic\Identity\SocialRecordBuilder;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Identity\EntityAuthData;

// Create two key pairs 
$personalKeyPair = new KeyPair();
$accountKeyPair = new KeyPair();

// Create a new SocialRecord from scratch. The parameters passed to the builder here are mandatory. 
// Other parameters not set explicitly set will be set automatically
$socialRecord = (new SocialRecordBuilder())
	->type(SocialRecord::TYPE_USER)
	->accountPublicKey($accountKeyPair->getPublicKey())
	->personalPublicKey($personalKeyPair->getPublicKey())
	->displayName('Alice')
	->profileLocation('http://sonic-project.net/path/to/api/')
	->build();

// The resulting \Sonic\Identity\SocialRecord can be exported to a serialized JSONObject
$exportedSR = SocialRecordManager::exportSocialRecord($socialRecord, $accountKeyPair, $personalKeyPair);

// The import works vice versa
$importedSR = SocialRecordManager::importSocialRecord($exported);

// From the SocialRecord and the key pairs an \Sonic\Identity\EntityAuthData object can be built.
// EntityAuthData objects hold both KeyPairs and the SocialRecord
$entityAuthData = new EntityAuthData($socialRecord, $accountKeyPair, $personalKeyPair);

// Now the result can be published via the GSLS
SocialRecordManager::pushToGSLS($entityAuthData);
```
