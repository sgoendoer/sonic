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
	public function testModel()
	{
		// using this previousy exported SocialRecord
		$sr = '{"globalID":"3GZRKZLAYSDKJS81L5XVHGG4LYBGOBXSQQINQYQ9456FFV9CFE","displayName":"-----test-----","profileLocation":"http://test.com-----","personalPubKey":"-----BEGIN PUBLIC KEY-----MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAx1f+8QsXuMzms5N0n8RQk5sacKIW1mBggnDbYM9AJp2dKdFT2xNufG+holIh/+hskgosG41IkdlzBJZdSTB4B2+ZCWvsJ63WGkeLgb1Efqze/uBKQpXvMOu6LqLK7mJUeN3PRPCvVS/lISz9XMsvQpxpgw0j/A2R1vaxlibJw9EZ7Zp1+jN9ZxVal15k0uJJUnOnjMA7o++xiyPvPmssL+fA/zY6R+BhuNi4HADQkwy5NWi2FbuVicrGdxHtTasrdGEh5jeLUU3FLRkCiO7YeWRr52M1bPTUEylqIqParzQfm8SIa0tO1CXEL5cDkFLr4rxaCHaxW0beXMnL0Xk8geLbdCEKihPQgDJoYzb0XxkshdwZCrdR35jpPYRY3h1ChCPP13qt+l2zfgMBx9mAEWr2kr5oeKU0J1lbksc9k6RgDWkw7pztQg5atd4MfxbQbYDiKZ4VMhVZLbErUwZF1L7oVFuR1Ojq1MxesFxi+9zYAK6ik1lfgkmvhP9BsCv3VRRIRRfAGJRpdDnGrkm/rn6Xt9tcYakJzP3m58JGSnayhoSziGmKywYCr4xDluCsXkT1C6Bw6MY5qDrXDwrnAEQGnzPLn/fEP5jDrgC8JPDwKRz7PUZFc+PEKyn8NAEDUi/72Kh7cqcdjq/jyf+FYYQVkvQB4TLR1pkZ8wnrMXsCAwEAAQ==-----END PUBLIC KEY-----","accountPubKey":"-----BEGIN PUBLIC KEY-----MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAw7DdDnckSvXIIQfszDUxBjjvW8QJ7ZLi+D8sWx3wycYCPp1t8UF/U81W7puL/3+Ptz5pShQy80uvIOaA6QFuQRp97zSgM+mNkUAigPAdOgnlgZ6Ul3q3rnt9F6FnADfjAaFnkKUshx1XFoOw2Ugj5ne3s/d/eUbuC+gWg2GTfMC0xOworO6eNY38io3nmA3FMN5LrfOiksWYc8uJFBta2/YMfzKxwR0Z4dVXSxQGKjfYSyqgXJyXEQL+xXxXJmNjSdCdCYDXOOLpVdQ5aSwER4qJJcylBbEa0v7E56tyxX/UJyGF5m/6QC4GqdsGWc9k+IWo+KVNspZzamoUm8CHkJORfgC8BZ43mb2KgksMQhwDckbczS6tfY1EYIAdRzQRMOYCPtRxWMDFb/kq2ZWX8EAAyB6FUSff37twod+zyyUwOvVeVLQ+WPvpD1EwU7E2FpF4t/bMwMQ8LZ2UBQpiNIrlF36E2QVCXdohlMeyIZOvOc5DgmMQ7pDLDS4Chf5RN+gzpd04UkpBqJ9+HSFCit28Qj723AV1PAm4rT8maJvxgdSM71yyzP0xA7+kLun8TMHLTPi3JXYXrqKuNS9F0oTqh8L+xakE8FTDWP+8jm0rb/9WQH5Va1ythHV8nvDU8ktOFkPTKke5fPUvJ8HO01l/6es45fvtc+2WELBORecCAwEAAQ==-----END PUBLIC KEY-----","salt":"2731bc77b70452bb","datetime":"2015-08-18T13:50:53+02:00","active":1,"keyRevocationList":[]}';

		$personalPrivateKey = '-----BEGIN PRIVATE KEY-----MIIJQgIBADANBgkqhkiG9w0BAQEFAASCCSwwggkoAgEAAoICAQDHV/7xCxe4zOazk3SfxFCTmxpwohbWYGCCcNtgz0AmnZ0p0VPbE258b6GiUiH/6GySCiwbjUiR2XMEll1JMHgHb5kJa+wnrdYaR4uBvUR+rN7+4EpCle8w67ouosruYlR43c9E8K9VL+UhLP1cyy9CnGmDDSP8DZHW9rGWJsnD0RntmnX6M31nFVqXXmTS4klSc6eMwDuj77GLI+8+aywv58D/NjpH4GG42LgcANCTDLk1aLYVu5WJysZ3Ee1Nqyt0YSHmN4tRTcUtGQKI7th5ZGvnYzVs9NQTKWoio9qvNB+bxIhrS07UJcQvlwOQUuvivFoIdrFbRt5cycvReTyB4tt0IQqKE9CAMmhjNvRfGSyF3BkKt1HfmOk9hFjeHUKEI8/Xeq36XbN+AwHH2YARavaSvmh4pTQnWVuSxz2TpGANaTDunO1CDlq13gx/FtBtgOIpnhUyFVktsStTBkXUvuhUW5HU6OrUzF6wXGL73NgArqKTWV+CSa+E/0GwK/dVFEhFF8AYlGl0OcauSb+ufpe321xhqQnM/ebnwkZKdrKGhLOIaYrLBgKvjEOW4KxeRPULoHDoxjmoOtcPCucARAafM8uf98Q/mMOuALwk8PApHPs9RkVz48QrKfw0AQNSL/vYqHtypx2Or+PJ/4VhhBWS9AHhMtHWmRnzCesxewIDAQABAoICAD2vhTyh6h1NirZXEue9oBGNFYl1HwwHltXGhpxWhcttSoz+MazBPpdTE/le4RaHndEzRKLh/Wrklyr8PLHBReNHLVw1AUbmDDLloM3s+50XcYn9bf1c2D+TMnHr/k/2LZ2ZReJXeGosAANcIKruVRmPvTbIgjIlINQcUqfcKONneVaFTay28j9L5FSi6SA41eel0ltDwTDho1cjL41pLKtGU/qllTNYw+Sna5F+zwwyC73zQSVIfuW80d1WttAp/7Brvkm3Nm1aW4GxE4+8GVC3OozHrmig+l9TmXxOqAU/Y+iX0H8jNql/Zk4ztO6cOlJXZqTn1cohC/46k8+mbIpYyYUrpoGCUZBYzZCgOjeK62hjWlFJ//n8uHyS7H9Wh9RoaLlFMHzZdS1pRICaYxuhsbJNBenmY1P30RW8YejRWZKVQ1gyZ+9C13Nhcel+YnTL3Hh/Mu4xkPU7uJ1rWDV69ly+hGIPrwX8Oriu4UfYO1f6Hauei7T647oMarBMwrDylvdwczHcut/vNWQYTjy9wxlvi1Spq2jxao5eM3xsuE4JGlXGoBx1ri7NYsbTVdXRzoUf7lPrp9G/B5P24eKFO6ZQiPPEwQGHEAVmKgVBlIEz5ygRhZCPdtSJ/k8SymNba/cZPQln3WYNjAQQxm54moLuuBrJi0ugDpeI+3MBAoIBAQDwslgCWSZ1h/nFVasyxHw6HdaXxv3sgR1Rv3iKwTGocilO0MgguLxYWt07sJV7J1c8716amuMbAHyUQI4vFIHX2ui70hy9wexNElNydbc+y862MdNYEj+omF6kLNvKSAHCQauyOJAqicirXa2D+uMkWTtrAeDqf5mfZwVHQcQ74IvFNC9cgJh+baXEXbkpSAuy7l4Xd1kU/kDcteMVHG+FXNQWq9TsfIELTQLLYC9jP2NsmQAh9qNaBMfTg8sJyKQu8EzA1Lxr6NMs4i/BS6o+w4n4zs2OWORoLkAwk4OEAcNUBUwwOrQUL2LCGAvFgmZBkd4MayIPTxcU8WJoZV8RAoIBAQDUBJQgkLSZS9Lxl+bNA0DTGo8rPeUmsmmnXyy+5+3bbSY3FxLPr4k7JXE71MIV/l6gLR/BviWXxNsAa/BT/80wKc1WwBoBGVag+8Ik1PN3Uwvgye63YDCtMWxt3e8OEp3t28gBsoh0PdUmwFFkcx7CwWs3cCa3Sk3FIvh/FazUE/zYn2uTuqyfKf/QiIZF510qY3pzjOuiFM4dG5ymrU5eCYTEKqgQn5G1GMljdMVuUeanBgSlhASqXejmpvIF5GA745UMEZiK41J3k1/ftjeyWqLfZb8VxSZK6v+qgLXtfHtLqZC/F8/H3LouZqKyqJu9M0bqRvLSpR6AfrYaKd/LAoIBAQCOoeCtY476zt0gJygQKYdEGTJ1NqJ6Z/ir3L7d2qZGn4ADCI3PdimGYjH/kSDDngiAP4jcLTSvZ5Zrc+XN2GUpOLv5sQT/Hc+dzGJOzZsNR1DzfoszAX1ftws+VqbYM9t29dt8/S/RRVz4z8ZnE2FOxOIFA1P5Iiwy3aWvGDXlFac98TKvqjSzlt5IgxGaeOsV2VNArKTJX/Gn2ND8TaF4cg3scV731L/TposlYZYzRfjw/amqdrFOQs73dH35CkDg391b17TgbVPWoSKTdPcpwk4cdtAwrlSLt/ypzIB+1VyeiZUgb2kPHRV01zMCk8dsy7e9vC6uy7tt2azrGEdBAoIBAFiR+ReJnAiWRm8q1/45J7GI3NphpwL9cWm8NsMhvJn4BQjqmK8uU5Pj1mP6Q4JQm1MqxqN3PdkjIaIRdXqB2EQQknl1Euf2n9vskHQ2iecFSGcFtLXidzNC/FsBZcZpPJSLRgh/fsWgtxTTLq1Z8jXF+3ZTYbkcT1TVgR4F/gcyumP8ZUJ3r3lmYDnLbtxxBNiaaiMvcZhqPJioWk6/A4gBTfdtInyfTI1s52Sm+XtWt5Z6yyIh/pj1VkBU6bJQb2tVbYR3LVSIFoFhjaG1S5s0henxZVxA2fheJ6Qg1N97Kz62fYN2/RQvP4/2zGz6RuVJMIzaSsJ4vD9Nr+FKMPUCggEAFTEHV4jf99mql6HnHDqfvaORiKKkbDh/429LG6RWnCVrOtgjBqWbInm/amQ1KqlhPZHUD3vGYzVLCC5+CS70bUiX8ipt7SppE6UoX7FjX/WYv5UzSNLOiQTNE263/UNvlX/5VeeMfNPOVDSqjrnbsZYAenvjcMqUcDCWZvDMERkdIUTavk7bg3+UJ1i4lendDeURRy6KZeiz1TeeRbBthTIZom1yHS+MzOGOpoqDaHdNUzppx2s086cfS7LefYo7wNE6/uiHcTV1D+9cnDQXUaL55HfBCCKgetNwz7P5Sy9Be+LsAnh1D1TSSfRYSV+IfdiMelaeWTK0R+iWlCBu/w==-----END PRIVATE KEY-----';

		$accountPrivateKey = '-----BEGIN PRIVATE KEY-----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-----END PRIVATE KEY-----';

		// create a SocialRecord
		try
		{
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// creating SocialRecord object to work with
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			$socialRecord = SocialRecordBuilder::buildFromJSON($sr);
			$accountKeyPair = new KeyPair($accountPrivateKey, $socialRecord->getAccountPubKey());
			$personalKeyPair = new KeyPair($personalPrivateKey, $socialRecord->getPersonalPubKey());
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// initializing Sonic SDK
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			$sonic = Sonic::init($socialRecord, $accountKeyPair, $personalKeyPair);
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// API tests
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			$targetGID = array(
				'social' => '2VX9QDX828U87SZ5879OH2NPOTTJH9ASQN93X1BUZI8M70DAX6', //3Z51OUSP4UPXTEOOKSDXVZQLMYGNJQVY0SKYUKKGVT8QWD78T5
			 	'sonic1' => '4PN00KC8DT8K2T2JKDAVY8CZI4G9JRVTXMU245UGNEWX6B8E7T',
			 	'sonic3' => '5NE2DNJF7BRML6DIO43IN9AL5CYNNE7E1R2LE8KFWVFLM13P2L'
			);
			$targetedGID = $targetGID['social'];
			//$targetedGID = $targetGID['sonic1'];
			//$targetedGID = $targetGID['sonic3'];
			
			// PERSON //////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			$person = (new PersonObjectBuilder())
				->objectID(UOID::createUOID())
				->globalID(Sonic::GID())
				->displayName('myDisplayName')
				->profilePicture("myProfilePicture")
				->profilePictureThumbnail("myProfilePictureThumbnail")
				->build();
			$person->validateJSON($person->getJSON());
			
			$this->assertEquals(PersonObjectBuilder::buildFromJSON($person->getJSON())->getJSONString(), $person->getJSONString());
			
			// PROFILE /////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			$profile = (new ProfileObjectBuilder())
				->objectID(UOID::createUOID())
				->globalID(Sonic::GID())
				->displayName('myDisplayName')
				->param("a", "firstParam")
				->paramArray(array("b" => "secondParam"))
				->build();
			$profile->validateJSON($profile->getJSON());
			
			$profile2 = ProfileObjectBuilder::buildFromJSON($profile->getJSON());
			
			$this->assertEquals(ProfileObjectBuilder::buildFromJSON($profile->getJSON())->getJSONString(), $profile->getJSONString());
			
			// LINK ////////////////////////////////////////////////////////////////////////////////////////////////////////////
			/*
			echo "\nrunning test for model LINK... ";
			$link1 = (new LinkObjectBuilder())
				->objectID(UOID::createUOID())
				->link(Sonic::GID())
				->owner($targetedGID)
				->datetime()
				->build();
			$link1->validateJSON($link1->getJSON());
			
			$link2 = LinkObjectBuilder::buildFromJSON($link1->getJSON());
			if($link1->getJSON() == $link2->getJSON()) echo "OK"; 
			else echo "FAILED!\n" . $link1->getJSON() . "\n" . $link2->getJSON();
			
			echo "\nrunning test for model LINK ROSTER... ";
			$link2 = (new LinkObjectBuilder())
				->objectID(UOID::createUOID())
				->link(Sonic::GID())
				->owner($targetedGID)
				->datetime()
				->build();
			
			$linkRoster = (new LinkRosterObjectBuilder())
				->objectID(UOID::createUOID())
				->owner(Sonic::GID())
				->roster(array($link1, $link2))
				->build();
			$linkRoster->validateJSON($linkRoster->getJSON());
			
			$linkRoster2 = LinkRosterObjectBuilder::buildFromJSON($linkRoster->getJSON());
			if($linkRoster->getJSON() == $linkRoster2->getJSON()) echo "OK"; 
			else echo "FAILED!\n" . $linkRoster->getJSON() . "\n" . $linkRoster2->getJSON();
			
			echo "\nrunning test for model LINK REQUEST... ";
			$linkRequest = (new LinkRequestObjectBuilder())
				->objectID(UOID::createUOID())
				->initiatingGID(Sonic::GID())
				->targetedGID($targetedGID)
				->datetime()
				->message('testMessage')
				->build();
			$linkRequest->validateJSON($linkRequest->getJSON());
				
			$linkRequest2 = LinkRequestObjectBuilder::buildFromJSON($linkRequest->getJSON());
			if($linkRequest->getJSON() == $linkRequest2->getJSON()) echo "OK"; 
			else echo "FAILED!\n" . $linkRequest->getJSON() . "\n" . $linkRequest2->getJSON();
			
			echo "\nrunning test for model LINK RESPONSE... ";
			$linkResponse = (new LinkResponseObjectBuilder())
				->objectID(UOID::createUOID())
				->targetID(UOID::createUOID())
				->accept(true)
				->message('testMessage')
				->link($link1)
				->build();
			$linkResponse->validateJSON($linkResponse->getJSON());
			
			$linkResponse2 = LinkResponseObjectBuilder::buildFromJSON($linkResponse->getJSON());
			if($linkResponse->getJSON() == $linkResponse2->getJSON()) echo "OK"; 
			else echo "FAILED!\n" . $linkResponse->getJSON() . "\n" . $linkResponse2->getJSON();
			
			// LIKE ////////////////////////////////////////////////////////////////////////////////////////////////////////////

			echo "\nrunning test for model LIKE... ";
			$like = (new LikeObjectBuilder())
				->objectID(UOID::createUOID())
				->targetID(UOID::createUOID())
				->author(Sonic::GID())
				->datePublished()
				->build();
			$like->validateJSON($like->getJSON());
				
			$like2 = LikeObjectBuilder::buildFromJSON($like->getJSON());
			if($like->getJSON() == $like2->getJSON()) echo "OK"; 
			else echo "FAILED!\n" . $like->getJSON() . "\n" . $like2->getJSON();
			
			// COMMENT /////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			echo "\nrunning test for model COMMENT... ";
			$comment = (new CommentObjectBuilder())
				->objectID(UOID::createUOID())
				->targetID(UOID::createUOID())
				->author(Sonic::GID())
				->comment('test comment')
				->datePublished()
				->build();
			$comment->validateJSON($comment->getJSON());
			
			$comment2 = CommentObjectBuilder::buildFromJSON($comment->getJSON());
			
			if($comment->getJSON() == $comment2->getJSON()) echo "OK"; 
			else echo "FAILED!\n" . $comment->getJSON() . "\n" . $comment2->getJSON();
			
			// TAG /////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			echo "\nrunning test for model TAG... ";
			$tag = (new TagObjectBuilder())
				->objectID(UOID::createUOID())
				->targetID(UOID::createUOID())
				->author(Sonic::GID())
				->tag($targetedGID)
				->datePublished()
				->build();
			$tag->validateJSON($tag->getJSON());
			
			$tag2 = TagObjectBuilder::buildFromJSON($tag->getJSON());
			
			if($tag->getJSON() == $tag2->getJSON()) echo "OK"; 
			else echo "FAILED!\n" . $tag->getJSON() . "\n" . $tag2->getJSON();
			
			// STREAM //////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			/*echo "\nrunning test for model STREAM ITEM... ";
			$streamItem = (new StreamItemObjectBuilder())
				->objectID(UOID::createUOID())
				->owner(Sonic::GID())
				->author(Sonic::GID())
				->datetime()
				->activity(json_decode('{}'))
				->build();
			$streamItem->validateJSON($streamItem->getJSON());
			
			$streamItem2 = StreamItemObjectBuilder::buildFromJSON($streamItem->getJSON());
			if($streamItem->getJSON() == $streamItem2->getJSON()) echo "OK";
			else echo "FAILED!\n" . $streamItem->getJSON() . "\n" . $streamItem2->getJSON();*/
			
			// CONVERSATION ////////////////////////////////////////////////////////////////////////////////////////////////////
			
			$conversationUOID = UOID::createUOID();
			$messageUOID = UOID::createUOID();
			
			$conversation = (new ConversationObjectBuilder())
				->objectID($conversationUOID)
				->owner(Sonic::GID())
				->members(array(Sonic::GID(), $targetedGID))
				->title('conversation title')
				->build();
			
			$this->assertTrue($conversation->validateJSON($conversation->getJSON()));
			$this->assertEquals(ConversationObjectBuilder::buildFromJSON($conversation->getJSON())->getJSONString(), $conversation->getJSONString());
			
			$conversation->setTitle('new conversation title');
			$conversation->addMember($targetGID['sonic3']);
			
			$this->assertTrue($conversation->validateJSON($conversation->getJSON()));
			$this->assertEquals(ConversationObjectBuilder::buildFromJSON($conversation->getJSON())->getJSONString(), $conversation->getJSONString());
			
			$conversationMessage = (new ConversationMessageObjectBuilder())
				->objectID($messageUOID)
				->targetID($conversationUOID)
				->title('message title')
				->author(Sonic::GID())
				->body('message text')
				->datetime()
				->build();
			$conversationMessage->setStatus(ConversationMessageStatusObject::STATUS_READ);
			
			$this->assertTrue($conversationMessage->validateJSON($conversationMessage->getJSON()));
			$this->assertEquals(ConversationMessageObjectBuilder::buildFromJSON($conversationMessage->getJSON())->getJSONString(), $conversationMessage->getJSONString());
			
			$conversationStatus = (new ConversationStatusObjectBuilder())
				->targetID($conversationUOID)
				->status(ConversationStatusObject::STATUS_INVITED)
				->author(Sonic::GID())
				->targetGID($targetedGID)
				->datetime()
				->build();
			
			$this->assertTrue($conversationStatus->validateJSON($conversationStatus->getJSON()));
			$this->assertEquals(ConversationStatusObjectBuilder::buildFromJSON($conversationStatus->getJSON())->getJSONString(), $conversationStatus->getJSONString());
			
			$conversationMessageStatus = (new ConversationMessageStatusObjectBuilder())
				->targetID($messageUOID)
				->conversationID($conversationUOID)
				->status(ConversationMessageStatusObject::STATUS_READ)
				->author(Sonic::GID())
				->build();
			
			$this->assertTrue($conversationMessageStatus->validateJSON($conversationMessageStatus->getJSON()));
			$this->assertEquals(ConversationMessageStatusObjectBuilder::buildFromJSON($conversationMessageStatus->getJSON())->getJSONString(), $conversationMessageStatus->getJSONString());
			
			// SONIC RESPONSE //////////////////////////////////////////////////////////////////////////////////////////////////
			/*
			echo "\nrunning test for model SONIC RESPONSE... ";
			$response = (new ResponseObjectBuilder())
				->responseCode(12345)
				->errorCode('12345')
				->message('my message')
				->body('{"message": "text"}')
				->build();
			
			$response2 = ResponseObjectBuilder::buildFromJSON($response->getJSON());
			if($response->getJSON() == $response2->getJSON()) echo "OK";
			else echo "FAILED!\n" . $response->getJSON() . "\n" . $response->getJSON();*/
		}
		catch (\Exception $e)
		{
			die("Exception: " . $e->getMessage() . "\n\n" . $e->getTraceAsString() . "\n\n");
		}
	}
}

?>