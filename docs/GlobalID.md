# GlobalID

In SONIC, every user and every platform is identified by a globally unique identifier, the GlobalID. GlobalIDs are domain and platform independent and remain unchanged even when a user account is moved to a new domain. This way, user accounts can be addressed regardless of where it is actually hosted.

## GlobalID creation

A GlobalID is derived from an PKCS#8-formatted RSA public key (linebreaks have to be removed) and a salt of 8 bytes length (16 characters) using the key derivation function PBKDF#2 with settings SHA256, 10000 iterations, 256bit output length. The result is converted to base36 (A-Z0-9).

> 2UZCAI2GM45T160MDN44OIQ8GKN5GGCKO96LC9ZOQCAEVAURA8

The class \Sonic\Identity\GID manages creation and validation of GlobalIDs.

## Example usage

	// retrieve the current user's GlobalID
	Sonic::getUserAuthData()->getGlobalID();