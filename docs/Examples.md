# Sonic Server Example

- [SonicServerExample.php](../examples/SonicServerExample.php)

The SonicServerExample.php demonstrates a very basic implementation of a Sonic Platform. Requests to the Sonic API's endpoint are redirected to this file using Apache's ModRewrite (see .htaccess).

The code first parses the request's path into variables, then reads the SocialRecord examples from the data folder before initializing the Sonic class.

For simplicity, only requests for a user "Alice" are handled and only requests for the resources LIKE and PROFILE are implemented.

# Sonic Client Example

- [SonicClientBasicExample.php](../examples/SonicClientBasicExample.php)
- [SonicClientExample.php](../examples/SonicClientExample.php)

The SonicClientExample.php demonstrates the use of the Sonic SDK for consuming a Sonic Platform's REST interface.

The code first reads the SocialRecord examples from the data folder before initializing the Sonic class.

For simplicity, only two requests (GET PROFILE and POST LIKE) are performed by the code.

# API Examples

- [PersonAPIExample.php](../examples/PersonAPIExample.php)
- [LikeAPIExample.php](../examples/LikeAPIExample.php)

Simple examples demonstrating how to use the request builders to request or send data in the Sonic federation.

# Create Social Record Example

- [CreateNewSocialRecordExample.php](../examples/CreateNewSocialRecordExample.php)

Simple example how to use SocialRecordBuilder and SocialRecordManager to create, upload, and retrieve Social Records.
