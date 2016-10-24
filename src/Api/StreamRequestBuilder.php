<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Model\StreamItemObject;
use sgoendoer\Sonic\Api\CommentRequestBuilder;
use sgoendoer\Sonic\Api\LikeRequestBuilder;
use sgoendoer\Sonic\Api\TagRequestBuilder;

/**
 * Creates STREAM requests
 * version 20160129
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class StreamRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_STREAM = 'STREAM';
	
	public function createGETStream($streamUOID = NULL)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		if($streamUOID != NULL)
			$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM . '/' . $streamUOID);
		else
			$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createGETStreamComment($commentUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM . '/' . $commentUOID . '/' . CommentRequestBuilder::RESOURCE_NAME_COMMENT);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createGETStreamLike($commentUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM . '/' . $commentUOID . '/' . LikeRequestBuilder::RESOURCE_NAME_LIKE);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createGETStreamTag($commentUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM . '/' . $commentUOID . '/' . TagRequestBuilder::RESOURCE_NAME_TAG);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createPOSTStream(StreamItemObject $streamItem)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($streamItem->getJSONString());
		
		return $this;
	}
	
	public function createPUTStream(StreamItemObject $streamItem)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM . '/' . $streamItem->getObjectID());
		$this->request->setRequestMethod('PUT');
		$this->request->setRequestBody($streamItem->getJSONString());
		
		return $this;
	}
	
	public function createDELETEStream($streamUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM . '/' . $streamUOID);
		$this->request->setRequestMethod('DELETE');
		
		return $this;
	}
}

?>