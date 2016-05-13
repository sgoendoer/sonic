# Sonic Content Model

The Sonic philosophy is based on the idea that all content of a social profile should be stored within the jurisdiction of the user who created it. This way, potentially sensitive data is not replicated to other platforms, thus allowing users to keep full control over who has access to their profile's contents. In certain scenarios anyhow, this paradigm would lead to issues regarding to data privacy and performance. Here, content is stored in the social profile the content targets, resulting in a distinction of locally and remotely stored content.

Here, a social profile describes all content associated with a user. This comprises data such as name, date of birth, or gender, but also other content such as images, exchanged messages and comments, or connections to other users' social profiles. Sonic dictates that social profiles are independent of the platform they are hosted on and hence can be migrated between platforms at any time.

## UOIDs

In Sonic, a unique object identifier (UOID) is assigned to every object. Each UOID comprises a global and a local part. The global part is the GlobalID of the object's creator, while the local part is a 16 character long random number, which is unique for the domain of the creator. This makes every object globally identifiable and addressable even after a profile migration.

	2UZCAI2GM45T160MDN44OIQ8GKN5GGCKO96LC9ZOQCAEVAURA8:c366cb50ff3b8fca

The class ```\Sonic\Identity\UOID``` manages creation and validation of UOIDs.

### Example usage

```php
use sgoendoer\Sonic\Identity\UOID;

// Create a UOID
$uoid = UOID::createUOID();
```

As the local part of a UOID might is not secure against hash collisions, Sonic provides means to ensure that a UOID is unique. Here, IUniqueIDManager needs to be implemented and registered with the Sonic instance.

```php
use sgoendoer\Sonic;
use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\IUniqueIDManager;

// Implement IUniqueIDManager to make sure a local ID is not assigned twice
class UniqueIDManager implements IUniqueIDManager {
	
	// checks existing IDs for a collision
	public function isIDRegistered($new_id) {
		if($id_is_unique) return true;
		else return false;
	}
	
	// save the new ID
	public function registerID($new_id) {
		return true;
	}
}

// Register UniqueIDManager with Sonic instance
$sonic->setUniqueIDManager(new UniqueIDManager());

// Create a UOID with unique random
$uoid = UOID::createUOID();

// check UOID format
UOID::isValid($uoid);
```

### Local Content

All data of a social profile is stored in one dataset within the domain of the platform on which the user's profile is hosted. In Sonic, this type of content is referred to as "local content". Users, who want to access local content have to request it directly from the social profile of it's creator using it's GlobalID.

### Remote Content

In contrast, content which is associated with content stored within the social profile of another user is referred to as "remote content". Remote content is stored as an attachment to its target content object, i.e. as part of the social profile of the owner of the content. This is the case, when e.g. commenting on or liking another user's content.
As this content is then owned by somebody other than the content's original author, a digital signature is created using the creator's Account KeyPair. This way, the content's integrity can be verified.

## Sonic Role Model

When creating or accessing data objects in Sonic, a user assumes a certain role. Specifically, a user acts either of the creator of content (i.e., the author), the maintainer of content (i.e., the owner), or the one accessing it (i.e., the viewer). A separate role is the one of the provider, who provides the Sonic platform.

### Author

The Author of any content is the user who initially created it. Information about authorship is indicated for every content object by a globally unique identifier, the Global ID

### Owner

Content is always stored within the social profile of a user. This user automatically gains ownership for this content. Hence, for remote content, author and owner of the content object are distinct persons.

### Viewer

A Viewer is a user accessing any content object. Depending on the access permissions set by the content's Owner, different Viewers will be provided with different content.

### Provider

The Provider runs a Sonic Platform, on which one or more social profiles are hosted.
