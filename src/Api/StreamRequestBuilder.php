<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Model\StreamItemObject;
use sgoendoer\Sonic\Api\CommentRequestBuilder;
use sgoendoer\Sonic\Api\LikeRequestBuilder;
use sgoendoer\Sonic\Api\TagRequestBuilder;

/**
 * Creates STREAM requests
 * version 20150818
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class StreamRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_STREAM = 'STREAM';
	
	public function createGETStream($toGID, $streamUOID = NULL)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		if($streamUOID != NULL)
			$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM . '/' . $streamUOID);
		else
			$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createGETStreamComment($toGID, $commentUOID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM . '/' . $commentUOID . '/' . CommentRequestBuilder::RESOURCE_NAME_COMMENT);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createGETStreamLike($toGID, $commentUOID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM . '/' . $commentUOID . '/' . LikeRequestBuilder::RESOURCE_NAME_LIKE);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createGETStreamTag($toGID, $commentUOID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM . '/' . $commentUOID . '/' . TagRequestBuilder::RESOURCE_NAME_TAG);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createPOSTStream($toGID, StreamItemObject $streamItem)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($streamItem->getJSONString());
		
		return $this;
	}
	
	public function createPUTStream($toGID, StreamItemObject $streamItem)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM . '/' . $streamItem->getObjectID());
		$this->request->setRequestMethod('PUT');
		$this->request->setRequestBody($streamItem->getJSONString());
		
		return $this;
	}
	
	public function createDELETEStream($toGID, $streamUOID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_STREAM . '/' . $streamUOID);
		$this->request->setRequestMethod('DELETE');
		
		return $this;
	}
}

?>