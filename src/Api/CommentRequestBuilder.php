<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Model\CommentObject;
use sgoendoer\Sonic\Api\LikeRequestBuilder;
use sgoendoer\Sonic\Api\TagRequestBuilder;

/**
 * Creates COMMENT requests
 * version 20150929
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class CommentRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_COMMENT = 'COMMENT';
	
	public function createGETComment($commentUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_COMMENT . '/' . $commentUOID);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createGETCommentLike($commentUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_COMMENT . '/' . $commentUOID . '/' . LikeRequestBuilder::RESOURCE_NAME_LIKE);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createGETCommentTag($commentUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_COMMENT . '/' . $commentUOID . '/' . TagRequestBuilder::RESOURCE_NAME_TAG);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createPOSTComment(CommentObject $commentObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_COMMENT);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($commentObject->getJSONString());
		
		return $this;
	}
	
	public function createPUTComment(CommentObject $commentObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_COMMENT . '/' . $commentObject->getObjectID());
		$this->request->setRequestMethod('PUT');
		$this->request->setRequestBody($commentObject->getJSONString());
		
		return $this;
	}
	
	public function createDELETEComment($commentUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_COMMENT . '/' . $commentUOID);
		$this->request->setRequestMethod('DELETE');
		
		return $this;
	}
}

?>