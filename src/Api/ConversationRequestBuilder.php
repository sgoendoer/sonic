<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Model\ConversationObject;
use sgoendoer\Sonic\Model\ConversationStatusObject;
use sgoendoer\Sonic\Model\ConversationMessageObject;
use sgoendoer\Sonic\Model\ConversationMessageStatusObject;

/**
 * Creates CONVERSATION requests
 * version 20160129
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ConversationRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_STATUS = 'STATUS';
	const RESOURCE_NAME_CONVERSATION = 'CONVERSATION';
	const RESOURCE_NAME_MESSAGE = 'MESSAGE';
	
	public function createPOSTConversation(ConversationObject $conversationObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_CONVERSATION);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($conversationObject->getJSONString());
		
		return $this;
	}
	
	public function createPUTConversation(ConversationObject $conversationObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_CONVERSATION . '/' . $conversationObject->getObjectID());
		$this->request->setRequestMethod('PUT');
		$this->request->setRequestBody($conversationObject->getJSONString());
		
		return $this;
	}
	
	public function createPOSTConversationStatus(ConversationStatusObject $statusObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_CONVERSATION . '/' . $statusObject->getTargetID() . '/' . self::RESOURCE_NAME_STATUS);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($statusObject->getJSONString());
		
		return $this;
	}
	
	public function createPOSTConversationMessage(ConversationMessageObject $messageObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_CONVERSATION . '/' . $messageObject->getTargetID() . '/' . self::RESOURCE_NAME_MESSAGE);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($messageObject->getJSONString());
		
		return $this;
	}
	
	public function createPUTConversationMessage(ConversationMessageObject $messageObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_CONVERSATION . '/' . $messageObject->getTargetID() . '/' . self::RESOURCE_NAME_MESSAGE . '/' . $messageObject->getObjectID());
		$this->request->setRequestMethod('PUT');
		$this->request->setRequestBody($messageObject->getJSONString());
		
		return $this;
	}
	
	public function createPOSTConversationMessageStatus(ConversationMessageStatusObject $messageStatusObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_CONVERSATION . '/' . $messageStatusObject->getConversationID() . '/' . self::RESOURCE_NAME_MESSAGE . '/' . $messageStatusObject->getTargetID() . '/' . self::RESOURCE_NAME_STATUS);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($messageStatusObject->getJSONString());
		
		return $this;
	}
}

?>