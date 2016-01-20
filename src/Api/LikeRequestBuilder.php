<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Model\LikeObject;

/**
 * Creates LIKE requests
 * version 20150905
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class LikeRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_LIKE = 'LIKE';
	
	public function createGETLike($toGID, $likeUOID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_LIKE . '/' . $likeUOID);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createPOSTLike($toGID, LikeObject $likeObject)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_LIKE);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($likeObject->getJSONString());
		
		return $this;
	}
	
	public function createDELETELike($toGID, $likeUOID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_LIKE . '/' . $likeUOID);
		$this->request->setRequestMethod('DELETE');
		
		return $this;
	}
}

?>