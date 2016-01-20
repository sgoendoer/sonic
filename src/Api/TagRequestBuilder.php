<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Model\TagObject;

/**
 * Creates TAG requests
 * version 20150929
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class TagRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_TAG = 'TAG';
	
	public function createGETTag($toGID, $tagUOID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_TAG . '/' . $tagUOID);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createPOSTTag($toGID, TagObject $tagObject)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_TAG);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($tagObject->getJSONString());
		
		return $this;
	}
	
	public function createDELETETag($toGID, $tagUOID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_TAG . '/' . $tagUOID);
		$this->request->setRequestMethod('DELETE');
		
		return $this;
	}
}

?>