<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use sgoendoer\Sonic\Sonic;

use sgoendoer\Sonic\Crypt\KeyPair;
use sgoendoer\Sonic\Crypt\PublicKey;
use sgoendoer\Sonic\Crypt\PrivateKey;
use sgoendoer\Sonic\Crypt\Random;
use sgoendoer\Sonic\Crypt\Signature;
use sgoendoer\Sonic\Crypt\IUniqueIDManager;

use sgoendoer\Sonic\Date\XSDDateTime;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Identity\GSLS;
use sgoendoer\Sonic\Identity\SocialRecord;
use sgoendoer\Sonic\Identity\SocialRecordBuilder;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Identity\KeyRevocationCertificate;
use sgoendoer\Sonic\Identity\KeyRevocationCertificateBuilder;
use sgoendoer\Sonic\Identity\ISocialRecordCaching;

use sgoendoer\Sonic\Request\Request;

use sgoendoer\Sonic\Model\CommentObjectBuilder;
use sgoendoer\Sonic\Model\PersonObjectBuilder;
use sgoendoer\Sonic\Model\ProfileObjectBuilder;
use sgoendoer\Sonic\Model\ConversationObjectBuilder;
use sgoendoer\Sonic\Model\ConversationStatusObjectBuilder;
use sgoendoer\Sonic\Model\ConversationStatusObject;
use sgoendoer\Sonic\Model\ConversationMessageObjectBuilder;
use sgoendoer\Sonic\Model\ConversationMessageStatusObject;
use sgoendoer\Sonic\Model\ConversationMessageStatusObjectBuilder;
use sgoendoer\Sonic\Model\LikeObjectBuilder;
use sgoendoer\Sonic\Model\LinkObjectBuilder;
use sgoendoer\Sonic\Model\LinkRequestObjectBuilder;
use sgoendoer\Sonic\Model\LinkResponseObjectBuilder;
use sgoendoer\Sonic\Model\LinkRosterObjectBuilder;
use sgoendoer\Sonic\Model\StreamItemObjectBuilder;
use sgoendoer\Sonic\Model\TagObjectBuilder;
use sgoendoer\Sonic\Model\ResponseObjectBuilder;

date_default_timezone_set('Europe/Berlin');

class ModelUnitTest extends PHPUnit_Framework_TestCase
{
	public function testProfile()
	{
		
	}
}

?>