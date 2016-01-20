<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Model\CommentObject;
use sgoendoer\Sonic\Api\LikeRequestBuilder;
use sgoendoer\Sonic\Api\TagRequestBuilder;

/**
 * Creates COMMENT requests
 * version 20150929
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class CommentRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_COMMENT = 'COMMENT';
	
	public function createGETComment($toGID, $commentUOID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_COMMENT . '/' . $commentUOID);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createGETCommentLike($toGID, $commentUOID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_COMMENT . '/' . $commentUOID . '/' . LikeRequestBuilder::RESOURCE_NAME_LIKE);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createGETCommentTag($toGID, $commentUOID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_COMMENT . '/' . $commentUOID . '/' . TagRequestBuilder::RESOURCE_NAME_TAG);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createPOSTComment($toGID, CommentObject $commentObject)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_COMMENT);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($commentObject->getJSONString());
		
		return $this;
	}
	
	public function createPUTComment($toGID, CommentObject $commentObject)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_COMMENT . '/' . $commentObject->getObjectID());
		$this->request->setRequestMethod('PUT');
		$this->request->setRequestBody($commentObject->getJSONString());
		
		return $this;
	}
	
	public function createDELETEComment($toGID, $commentUOID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_COMMENT . '/' . $commentUOID);
		$this->request->setRequestMethod('DELETE');
		
		return $this;
	}
}

?>