# SocialRecord

In SONIC, every user and every platform is identified by a globally unique identifier, the GlobalID. by resolving the GlobalID via the GSLS, the actual location of a user's account can be determined. Information about the actual profile's location, as well as other information required for verification of authenticity and integrity are stored in a dataset, the SocialRecord.

# GlobalID

In SONIC, every user and every platform is identified by a globally unique identifier, the [GlobalID](UserIdentification.md#globalid). GlobalIDs are domain and platform independent and remain unchanged even when a user account is moved to a new domain. This way, user accounts can be addressed regardless of where it is actually hosted.

## GlobalID creation

A GlobalID is derived from an PKCS#8-formatted RSA public key (linebreaks have to be removed) and a salt of 8 bytes length (16 characters) using the key derivation function PBKDF#2 with settings SHA256, 10000 iterations, 256bit output length. The result is converted to base36 (A-Z0-9). An example of a GlobalID is ```2UZCAI2GM45T160MDN44OIQ8GKN5GGCKO96LC9ZOQCAEVAURA8```

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

# GSLS

SocialRecords in Sonic are published in a global distributed directory, the [Global Social Lookup System (GSLS)](UserIdentification.md#gsls). The GSLS stores SocialRecords as signed JSON Web Token (JWT) files using RSA + SHA512 (RS512). Similar to a DNS server, a GSLS node needs to be configured on initialization of the Sonic SDK.