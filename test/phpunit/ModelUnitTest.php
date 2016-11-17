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
use sgoendoer\Sonic\Identity\EntityAuthData;
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
use sgoendoer\Sonic\Model\SearchQueryObjectBuilder;
use sgoendoer\Sonic\Model\SearchResultObjectBuilder;
use sgoendoer\Sonic\Model\SearchResultCollectionObjectBuilder;
use sgoendoer\Sonic\Model\AccessControlRuleObjectBuilder;
use sgoendoer\Sonic\Model\AccessControlRuleObject;
use sgoendoer\Sonic\Model\AccessControlGroupObjectBuilder;

use sgoendoer\esquery\ESQueryBuilder;
use sgoendoer\json\JSONObject;

date_default_timezone_set('Europe/Berlin');

class ModelUnitTest extends PHPUnit_Framework_TestCase
{
	public $platformSRjson = '{"socialRecord":{"@context":"http://sonic-project.net/","@type":"socialrecord","type":"platform","globalID":"2UZCAI2GM45T160MDN44OIQ8GKN5GGCKO96LC9ZOQCAEVAURA8","platformGID":"2UZCAI2GM45T160MDN44OIQ8GKN5GGCKO96LC9ZOQCAEVAURA8","displayName":"social.snet.tu-berlin.de","profileLocation":"http://social.snet.tu-berlin.de/sonic-sdk/","personalPublicKey":"-----BEGIN PUBLIC KEY-----MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAtKXJWNeV1ld7oSXWelgeJlJSabwL2YN0f3tg6ZPEf3mlmt7e+PpBHp8ECsbZlkcKvRuoDxy+7YSFoPcy0nACrPVw4xX+9+fSvJQXlM/3yNi30U/VvdUBaWBzxuI7IAqoUYSV5dVA4FFgHlIOIDktQeQmz+Ob06cxRg2TgqOjaaERkC1tTc0TO4UsqQOfl03IPc0ZrVyuHTWZd/Cc/UE9LRhJvZ/eV4OgZL0f5rJXD3N9XyKsd6npRTJPEBqj1ixNKzBiP8tvkD23FUGdOFpiUq3eLmIoxljWwDW/6M+sX6i08UZh8RDIK8b/FnJxUKyfssZ1Nd8QtgGe6uwMoAnv124Nimd2IqSrvRw3SO52v1NzGGrvb3c1L6f6clPAVCA8Z6ujBCTv7tUXCN5YUIKjzIXUfkyUhjZFS3fQuPTiB1Pk/75aDcdRB0wcmN4hOKAA2B3jXV3fT/Jz3woY7HLIOG/qCEWjMMjW6oB74jnXe6rOWqspmFC6lzAjVlu7tL+UnqYvr6WdZJZmZdw4YkuWdi5kSRyZrNZ2SFswSus5/QL/hTA1bLmWUzCFIP+312SSo6xp7tnqVtWSJBkRy4T9CYux2/csaDQ4BiNyBdRorz28LUuLaVLT5A3s9VaU331AtWOb4xYMbKArqrpJns3/aYcuURhJKMG+Gguvzn1EJqMCAwEAAQ==-----END PUBLIC KEY-----","accountPublicKey":"-----BEGIN PUBLIC KEY-----MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAnO/pV1hRbAFZ65OhBr843xm5Iu200OpI39ADhppKcH87rV0SPvRFfNXKGMt0UPf6YeYAagWgvm+o+Ynu6jb0u3ev9bPblvM6r4Syj+k9SfCDS3QvVLiLUE/V5Ht8tW/fcyGpgg+6GYvBksD0Q+kesLWtbQdLx/m4txdiI7tVkma504P4XS+X0yvIqdC8jh5t+mSBkdw9mtNf/c10Vvs2ijBc39qPIBkA7rE724cg2uPor8TWgsm3rZ4ZCCY/lwbhDfDxPbQbEtWCq94ks9+moi1xJTP09688KmuFX5GjwtYl4zL96mze9B1LXrQ6kJBZEf8fpaWASH6IxNMNC+U3v7Y/E0CI2oyfNV5O6w6K4u3a/rGXZgIXuO9dHfB6rMSpox2OL7mbY+7H/f86AbXWZWGXmpm4CaImys9C9BaUF1f5Hvt3ZQHr1o+qn+SEmsjfANogsisCoy8r1cwBUY9rUOIFo8THEc6M8+E6Qxi3eK7YNrMJvasWh5WH5AG4dejylzoIKQcR65aBl5jUIzVVYLfPxP6D5qfATqnEIGxhb3NaSNrrPLDxEvlHVWeIPdBRSZRJwXORYuAz0lEbeYRMuSBsPJ12nXc2BB3N2wyp+cyZ2KDnDJQO246bPDQTIm+P1pzXCnWdo54wJmD10g/E10pfY1SYsXM+XEV/tobwJXECAwEAAQ==-----END PUBLIC KEY-----","salt":"82daf2b77b46f36d","datetime":"2016-01-13T10:58:52+01:00","active":1,"keyRevocationList":[]},"accountPrivateKey":"-----BEGIN PRIVATE KEY-----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-----END PRIVATE KEY-----","personalPrivateKey":"-----BEGIN PRIVATE KEY-----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-----END PRIVATE KEY-----"}';
	
	public $aliceSRjson = '{"socialRecord":{"@context":"http://sonic-project.net/","@type":"socialrecord","type":"user","globalID":"4802C8DE6UZZ5BICQI830A8P8BW3YB5EBPGXWNRH1EP7H838V7","platformGID":"2UZCAI2GM45T160MDN44OIQ8GKN5GGCKO96LC9ZOQCAEVAURA8","displayName":"Alice","profileLocation":"http://social.snet.tu-berlin.de/sonic-sdk/","personalPublicKey":"-----BEGIN PUBLIC KEY-----MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAzMp0mukaLQl2Ya0RmZKtioXx3gifTe6Bu2UUsrOgwd/SHB3g438pcJBqF8pvPKKhx0hgp8MX1W3IGyqsNsIbFF2b4r9VrtDqUUd0WBCKsvBNcqxfqWkez2kVB+Q3hQkOjyocuO8I6v1rvkFNsio0E9XLPcLOiYJL3qHrbQFI+qtshfgjeK9taZbrEX6uY4VQ602fb8dHK9ieCV/W46RCTQS4+ac1+y1CAyH7gQ5TPMZ2vraeLR4kA1r8l/u3ZhB8b8biMt81K/WVcEf+8K4LAi/Tub1uDowKU2HNveG5ov055hvbvYv/9z1kEFGpTEMOzl0hiK4DGkvpugVO9nUfyy7VA85ZgkBpY4WoHGoZQbubyBsMwqpmT1pkUwAQTKnv6ME1YLLY81YjeshQz+YezT/gqH0uC3a+ZcQotFanNyTvQrtjxQSqeOA87K1RwfJvn9QS4Lz3MMt8eSK1/H+aFavDBARgzAGPgDRBTtjKSvdImZ1g5zd9pItzGV9ZcasvTY3/m6U5L9ByiiFEHLQJr9eKBb0OAoQVG9G5vYQ1f1CF7OtYQA2L0ygc4TwwLCjILBJDoqiOuYgq/wVzE1200G1tQ504hLdaUJIETCLxvDhyMI3TbywxLSyihYjC3Tge68X+rKPgZoY4ahTok0CszOzYf4lDsYmKAPIVDH5C5AECAwEAAQ==-----END PUBLIC KEY-----","accountPublicKey":"-----BEGIN PUBLIC KEY-----MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAvgeptoYIVDvxPPIk4ZBpRy/SohpJPTHSguP3VjWG0xL8zllZSkdBLS1ijBGCxG/jx5KebBSLSkIAjEdby0/FKRjLTBqC7eak6s/1zUUzoABXwu/JckAToOJK5R/iSwAd5jOa94Bl6q7Pu6kfBsDSg43JOkIU0rvfMNcgdo/9GJwI2tg6/ZjM5YWNoRcHvl2XXM0lljJrxfVlXpWNhTUUoy/IrnyPhBlhHCXtbCVo/U5gQ5O6ymqwewRyWwhfvaWrWiwAW6KnvBz3ddCmjBArerOciVtcSXRoJ01jQP4HeDTzQDxvDb4ymAewfoRuzp0ctL4tMMS8P+XxpQmNrivZP+thwEM+jB8XLkbFB1Pj0aTdQzCkrJupiSz8mK5aBBptjPsek50egoOEyf5LY3y/daup5rbLFLE58pNO13GdtDiin0NDVwrC19uKrvy5vca/+O1lZjTaVNrP9FN9ug2wZ62N7Q6vyZT0H+7wkdbGWeKhxMa1j05dx0v61dp91k6N4wWXEeAknLw06FrCKicKjA6LQIVrT9KYjWDI9ewwaoKbRyrKJrHNoZg1+Mua+x3TWsXAEGE91+Mgdd0UZQ+XBihjyq76ccZULbzJ/flGeOyVUMwXg05QNT6zXRDyNUPUyb8HoI0kHlT8nRDF9kkKu2Yx40EhsrAQogoEqgxeYoUCAwEAAQ==-----END PUBLIC KEY-----","salt":"abb0afd289f102f3","datetime":"2016-01-13T10:58:54+01:00","active":1,"keyRevocationList":[]},"accountPrivateKey":"-----BEGIN PRIVATE KEY-----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-----END PRIVATE KEY-----","personalPrivateKey":"-----BEGIN PRIVATE KEY-----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-----END PRIVATE KEY-----"}';
		
	public $bobSRjson = '{"socialRecord":{"@context":"http://sonic-project.net/","@type":"socialrecord","type":"user","globalID":"28B6TE8T9NUO202C5NZIUTNQSP88E70B8JAWH4FQ58OJOB8LIF","platformGID":"2UZCAI2GM45T160MDN44OIQ8GKN5GGCKO96LC9ZOQCAEVAURA8","displayName":"Bob","profileLocation":"http://social.snet.tu-berlin.de/sonic-sdk/","personalPublicKey":"-----BEGIN PUBLIC KEY-----MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEArOq6bwrO2OsD/UFzS/pnTk5ZKmdhobGeWyYX6nuuVahunr+NXb3CDZXBdvNRmfAvJCVVtwDC5MoMHterskJx/wJGSp9fzijE6P/+z2pSfYA8pIczZnmVzax1DH1F0ESNrpiOV+qFjICV6JqkC0H4/SHjlOahBHVxyvXkTNEMQe8jYUyWOop1PMCSMniRRvoYJPEhDG3qSCNtvyVXMtmkfv7tLtfUFHvbbZhXRZuaLUBGI3XYAHcv2+Ms625I/zr1BXIeAvbHuCeDkPx0QzHNEC3H85fSW4ldBmL5tBLJb0sB3z7vcufvvswusiyw/IsmbqgOUoYHIrlTBBJW+bJHkITVMzh8/VSkF/qyTifZHUn4GxeAZLamDK3+kto+NGaieM/cD7i4XeSQq0ApbJz8SBFDFW0WPuiSWDKvfES6qRPRjmEZ+L/zL+WHAu8PwtWvYsdQEr2OBEE7wJSqXgK088kjrGr/rWxcQHpyiAchyrcQ74zxYKv/bY7/m3uNr+aHsLtcOjpuH2rGpUYnEStlkdtfrrw0EgFj629aJdv7lyy6KtqJier17vgG2t1TrnOEG0U9WvnjZXD6uIOLUNld67TaDcnorxcX9JavJkJ8YSABPe24/RhOjGm8ZhIkRcf0Gx11bH1AiAVGYLnsBprYbdaHK3azsUZ+0Mq/Y+b/98MCAwEAAQ==-----END PUBLIC KEY-----","accountPublicKey":"-----BEGIN PUBLIC KEY-----MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAwpU9YKsNMb7VTfVudN5Tbv4aYkb4YFtw8b3efj0QYcFrql8bD8kiadqDRGcJgt2h2YCEA3t4Vx3m32LbhA0KLSZ04//uVgc180XFm57YGDd3AErLYYcBY9A08Xb78kbPCLabU8z4E6BpoLaqzjLMljVQ0SVvXF3DvMpgDC7VE3iZ4tvDJs1T3WN+bmtJPMcI5oUAQ3IUnbIn/IUtSybkKyt6VUXBdWF+TRKUaoNW/pnWOOdf8/WvGKjHxgoowGJaohIEbOl78yK3t99gXERbVT6j1VuEn91byufnczl7s0ZF9w6ugT+qXoOXIj7SAKUZDHmJAtc3KtJIiZtswoSfem1MGTsmfkpgVt8vjW6WdkZnQEcSGr7sk2T7BUpl8Y/OucBmnW5S+tYXhmFFOy0KbhNMQBusai5cvl6EwPY0Ab/w60P+OFlRtOCZpErXx+yVPifwZiapBS5sv3qO8PvqVE704v9K8BZ66dF8snMxMmpBeDM//a4Z20M53EZc23zxvP+kvCpTLcNu5h+R7Ni8iC2/8pVsWV3JW4UxIDgFINbMKnBzYt6NFZVa4lW+eYKXEsO2/bRVDt1xy6X2fAE/mNfJLCG/VX/W0fd2EPMeWbeC/rk33pgcFPS2VXk6ZmDiS+Brz1NPTDZaXbAhs5WCvhJAp31+/oQEFlOXcWzkP7UCAwEAAQ==-----END PUBLIC KEY-----","salt":"be7265f39b6c9b68","datetime":"2016-01-13T10:58:55+01:00","active":1,"keyRevocationList":[]},"accountPrivateKey":"-----BEGIN PRIVATE KEY-----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-----END PRIVATE KEY-----","personalPrivateKey":"-----BEGIN PRIVATE KEY-----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-----END PRIVATE KEY-----"}';
	
	public $sonic = NULL;
	
	public $platformSR = NULL;
	public $platformSocialRecord = NULL;
	public $platformAccountKeyPair = NULL;
	public $platformPersonalKeyPair = NULL;
	
	public $aliceSR = NULL;
	public $aliceSocialRecord = NULL;
	public $aliceAccountKeyPair = NULL;
	public $alicePersonalKeyPair = NULL;
	
	public $bobSR = NULL;
	public $bobSocialRecord = NULL;
	public $bobAccountKeyPair = NULL;
	public $bobPersonalKeyPair = NULL;
	
	public function __construct()
	{
		$this->platformSR = SocialRecordManager::importSocialRecord($this->platformSRjson);
		$this->platformSocialRecord = $this->platformSR['socialRecord'];
		$this->platformAccountKeyPair = $this->platformSR['accountKeyPair'];
		$this->platformPersonalKeyPair = $this->platformSR['personalKeyPair'];
		
		$this->aliceSR = SocialRecordManager::importSocialRecord($this->aliceSRjson);
		$this->aliceSocialRecord = $this->aliceSR['socialRecord'];
		$this->aliceAccountKeyPair = $this->aliceSR['accountKeyPair'];
		$this->alicePersonalKeyPair = $this->aliceSR['personalKeyPair'];
		
		$this->bobSR = SocialRecordManager::importSocialRecord($this->bobSRjson);
		$this->bobSocialRecord = $this->bobSR['socialRecord'];
		$this->bobAccountKeyPair = $this->bobSR['accountKeyPair'];
		$this->bobPersonalKeyPair = $this->bobSR['personalKeyPair'];
		
		$this->sonic = Sonic::initInstance(new EntityAuthData(
										$this->platformSocialRecord,
										$this->platformAccountKeyPair,
										$this->platformPersonalKeyPair));
		
		Sonic::setUserAuthData(new EntityAuthData($this->aliceSocialRecord, $this->aliceAccountKeyPair));
		Sonic::setContext(Sonic::CONTEXT_USER);
	}
	
	// ACCESSCONTROLRULE ///////////////////////////////////////////////////////////////////////////////////////////////
	
	public function testAccessControlRule()
	{
		$rule = (new AccessControlRuleObjectBuilder())
					->objectID(UOID::createUOID())
					->owner(Sonic::getUserGlobalID())
					->index(0)
					->directive(AccessControlRuleObject::DIRECTIVE_ALLOW)
					->entityType(AccessControlRuleObject::ENTITY_TYPE_ALL)
					->entityID(AccessControlRuleObject::WILDCARD)
					->targetType(AccessControlRuleObject::TARGET_TYPE_INTERFACE)
					->target('person')
					->accessType(AccessControlRuleObject::ACCESS_TYPE_WRITE)
					->build();
					
		$this->assertTrue($rule->validate());
		$this->assertEquals($rule, AccessControlRuleObjectBuilder::buildFromJSON($rule->getJSONString()));
	}
	
	// ACCESSCONTROLGROUP //////////////////////////////////////////////////////////////////////////////////////////////
	
	public function testAccessControlGroup()
	{
		$group = (new AccessControlGroupObjectBuilder())
					->objectID(UOID::createUOID())
					->owner(Sonic::getUserGlobalID())
					->displayName('testgroupname')
					->members(array('28B6TE8T9NUO202C5NZIUTNQSP88E70B8JAWH4FQ58OJOB8LIF'))
					->build();
					
		$this->assertTrue($group->validate());
		$this->assertEquals($group, AccessControlGroupObjectBuilder::buildFromJSON($group->getJSONString()));
	}
	
	// PROFILE /////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function testProfile()
	{
		$profile = (new ProfileObjectBuilder())
					->globalID(Sonic::getContextGlobalID())
					->displayName($this->aliceSocialRecord->getDisplayName())
					->param('x', 'y')
					->build();
		
		$this->assertTrue($profile->validate());
		$this->assertEquals($profile, ProfileObjectBuilder::buildFromJSON($profile->getJSONString()));
	}
	
	// PERSON //////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function testPerson()
	{
		$person = (new PersonObjectBuilder())
			->objectID(UOID::createUOID())
			->globalID(Sonic::getContextGlobalID())
			->displayName($this->aliceSocialRecord->getDisplayName())
			->build();
		
		$this->assertTrue($person->validate());
		$this->assertEquals($person, PersonObjectBuilder::buildFromJSON($person->getJSONString()));
	}
	
	// LINK ////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function testLink()
	{
		$link = (new LinkObjectBuilder())
			->objectID(UOID::createUOID())
			->link(Sonic::getContextGlobalID())
			->owner($this->bobSocialRecord->getGlobalID())
			->build();
		
		$this->assertTrue($link->validate());
		$this->assertEquals($link, LinkObjectBuilder::buildFromJSON($link->getJSONString()));
		
		$linkRoster = (new LinkRosterObjectBuilder())
			->objectID(UOID::createUOID())
			->owner(Sonic::getContextGlobalID())
			->roster(array($link))
			->build();
		
		$this->assertTrue($linkRoster->validate());
		$this->assertEquals($linkRoster, LinkRosterObjectBuilder::buildFromJSON($linkRoster->getJSONString()));
		
		$linkRequest = (new LinkRequestObjectBuilder())
			->objectID(UOID::createUOID())
			->initiatingGID(Sonic::getContextGlobalID())
			->targetedGID($this->bobSocialRecord->getGlobalID())
			->message('testMessage')
			->build();
			
		$this->assertTrue($linkRequest->validate());
		$this->assertEquals($linkRequest, LinkRequestObjectBuilder::buildFromJSON($linkRequest->getJSONString()));
		
		$linkResponse = (new LinkResponseObjectBuilder())
			->objectID(UOID::createUOID())
			->targetID(UOID::createUOID())
			->accept(true)
			->message('testMessage')
			->link($link)
			->build();
		
		$this->assertTrue($linkResponse->validate());
		$this->assertEquals($linkResponse, LinkResponseObjectBuilder::buildFromJSON($linkResponse->getJSONString()));
	}
	
	// LIKE ////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function testLike()
	{
		$like = (new LikeObjectBuilder())
			->objectID(UOID::createUOID())
			->targetID(UOID::createUOID())
			->author(Sonic::getContextGlobalID())
			->build();
		
		$this->assertTrue($like->validate());
		$this->assertEquals($like, LikeObjectBuilder::buildFromJSON($like->getJSONString()));
	}
	
	// COMMENT /////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function testComment()
	{
		$comment = (new CommentObjectBuilder())
			->objectID(UOID::createUOID())
			->targetID(UOID::createUOID())
			->author(Sonic::getContextGlobalID())
			->comment('test comment')
			->build();
	
		$this->assertTrue($comment->validate());
		$this->assertEquals($comment, CommentObjectBuilder::buildFromJSON($comment->getJSONString()));
	}
	
	// TAG /////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function testTag()
	{
		$tag = (new TagObjectBuilder())
			->objectID(UOID::createUOID())
			->targetID(UOID::createUOID())
			->author(Sonic::getContextGlobalID())
			->datePublished()
			->tag($this->bobSocialRecord->getGlobalID())
			->build();
		
		$this->assertTrue($tag->validate());
		$this->assertEquals($tag, TagObjectBuilder::buildFromJSON($tag->getJSONString()));
	}
	
	// STREAM //////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function testStream()
	{
		$streamItem = (new StreamItemObjectBuilder())
			->objectID(UOID::createUOID())
			->owner(Sonic::getContextGlobalID())
			->author(Sonic::getContextGlobalID())
			->datetime()
			->activity(new JSONObject('{"@context": "http://www.w3.org/ns/activitystreams",
				"type": "Activity",
				"actor":
				{
					"type": "Person",
					"displayName": "Alice"
				},
				"object":
				{
					"type": "Note",
					"displayName": "A Note"
				}
			}'))
			->build();
		
		$this->assertTrue($streamItem->validate());
		$this->assertEquals($streamItem, StreamItemObjectBuilder::buildFromJSON($streamItem->getJSONString()));
	}
	
	// CONVERSATION ////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function testConversation()
	{
		$conversationUOID = UOID::createUOID();
		$messageUOID = UOID::createUOID();
		
		$conversation = (new ConversationObjectBuilder())
			->objectID($conversationUOID)
			->owner(Sonic::getContextGlobalID())
			->members(array(Sonic::getContextGlobalID(), $this->bobSocialRecord->getGlobalID()))
			->title('conversation title')
			->build();
		
		$this->assertTrue($conversation->validate());
		$this->assertEquals($conversation, ConversationObjectBuilder::buildFromJSON($conversation->getJSONString()), "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
		
		$conversationMessage = (new ConversationMessageObjectBuilder())
			->objectID($messageUOID)
			->targetID($conversationUOID)
			->title('message title')
			->author(Sonic::getContextGlobalID())
			->body('message text')
			->datetime()
			->build();
		$conversationMessage->setStatus(ConversationMessageStatusObject::STATUS_READ);
		
		$this->assertTrue($conversationMessage->validate());
		$this->assertEquals($conversationMessage, ConversationMessageObjectBuilder::buildFromJSON($conversationMessage->getJSONString()));
		
		$conversationStatus = (new ConversationStatusObjectBuilder())
			->targetID($conversationUOID)
			->status(ConversationStatusObject::STATUS_INVITED)
			->author(Sonic::getContextGlobalID())
			->targetGID($this->bobSocialRecord->getGlobalID())
			->datetime()
			->build();
		
		$this->assertTrue($conversationStatus->validate());
		$this->assertEquals($conversationStatus, ConversationStatusObjectBuilder::buildFromJSON($conversationStatus->getJSONString()));
		
		$conversationMessageStatus = (new ConversationMessageStatusObjectBuilder())
			->targetID($messageUOID)
			->conversationID($conversationUOID)
			->status(ConversationMessageStatusObject::STATUS_READ)
			->author(Sonic::getContextGlobalID())
			->build();
		
		$this->assertTrue($conversationMessageStatus->validate());
		$this->assertEquals($conversationMessageStatus, ConversationMessageStatusObjectBuilder::buildFromJSON($conversationMessageStatus->getJSONString()));
	}
	
	// SEARCH //////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function testSearch()
	{
		$searchQuery = (new SearchQueryObjectBuilder())
			->initiatingGID(Sonic::getContextGlobalID())
			->query((new ESQueryBuilder())->type('profile')->match('displayName', 'alice')->build())
			->datetime()
			->build();
		
		$this->assertTrue($searchQuery->validate());
		$this->assertEquals($searchQuery, SearchQueryObjectBuilder::buildFromJSON($searchQuery->getJSONString()));
		
		$profile = (new ProfileObjectBuilder())
			->globalID(Sonic::getContextGlobalID())
			->displayName($this->aliceSocialRecord->getDisplayName())
			->param('x', 'y')
			->build();
			
		$searchResult = (new SearchResultObjectBuilder())
			->targetID($searchQuery->getObjectID())
			->resultOwnerGID($profile->getGlobalID())
			->resultObjectID($profile->getObjectID())
			->resultIndex($searchQuery->getQuery()->getIndex())
			->resultType($searchQuery->getQuery()->getType())
			->displayName($profile->getDisplayName())
			->datetime()
			->build();
		
		$this->assertTrue($searchResult->validate());
		$this->assertEquals($searchResult, SearchResultObjectBuilder::buildFromJSON($searchResult->getJSONString()));
		
		$searchResultCollection = (new SearchResultCollectionObjectBuilder())
			->objectID(UOID::createUOID($this->platformSocialRecord->getGlobalID()))
			->targetID($searchQuery->getObjectID())
			->platformGID($this->platformSocialRecord->getGlobalID())
			->datetime()
			->result($searchResult)
			->build();
		
		$this->assertTrue($searchResultCollection->validate());
		$this->assertEquals($searchResultCollection, SearchResultCollectionObjectBuilder::buildFromJSON($searchResultCollection->getJSONString()));
	}
	
	// SONIC RESPONSE //////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function testResponse()
	{
		$response = (new ResponseObjectBuilder())
			->responseCode(12345)
			->errorCode('12345')
			->message('my message')
			->body('{"message":"text"}')
			->build();
		
		$this->assertEquals($response, ResponseObjectBuilder::buildFromJSON($response->getJSONString()));
	}
}

?>