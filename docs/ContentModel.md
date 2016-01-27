# Unique Object IDs (UOID)

In Sonic, a unique object identifier (UOID) is assigned to every object. Each UOID comprises a global and a local part. The global part is the GlobalID of the object's creator, while the local part is a 16 character long random number, which is unique for the domain of the creator. This makes every object globally identifiable and addressable even after a profile migration.

	2UZCAI2GM45T160MDN44OIQ8GKN5GGCKO96LC9ZOQCAEVAURA8:a45d19hg0tf64jv8

The class ```\Sonic\Identity\UOID``` manages creation and validation of UOIDs.

## Example usage

```php
// create and validate a UOID
$uoid = UOID::createUOID();
UOID::isValid($uoid);
```
