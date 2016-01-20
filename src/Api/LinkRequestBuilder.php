<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Model\LinkRequestObject;
use sgoendoer\Sonic\Model\LinkResponseObject;

/**
 * Creates LINK requests
 * version 20150901
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class LinkRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_LINK = 'LINK';
	
	public function createGETLink($toGID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_LINK);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createPOSTLink($toGID, LinkRequestObject $linkRequest)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_LINK);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($linkRequest->getJSONString());
		//die($body);
		//echo $this->request->toString();die();
		return $this;
	}
	
	public function createPUTLink($toGID, LinkResponseObject $linkResponse)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_LINK . '/' . $linkResponse->getTargetID());
		$this->request->setRequestMethod('PUT');
		$this->request->setRequestBody($linkResponse->getJSONString());
		//die($body);
		return $this;
	}
	
	public function createDELETELink($toGID, $linkUOID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_LINK . '/' . $linkUOID);
		$this->request->setRequestMethod('DELETE');
		//die($body);
		return $this;
	}
}

?>