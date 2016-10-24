<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Model\LinkRequestObject;
use sgoendoer\Sonic\Model\LinkResponseObject;

/**
 * Creates LINK requests
 * version 20160129
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class LinkRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_LINK = 'LINK';
	
	public function createGETLink()
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_LINK);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createPOSTLink(LinkRequestObject $linkRequest)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_LINK);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($linkRequest->getJSONString());
		
		return $this;
	}
	
	public function createPUTLink(LinkResponseObject $linkResponse)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_LINK . '/' . $linkResponse->getTargetID());
		$this->request->setRequestMethod('PUT');
		$this->request->setRequestBody($linkResponse->getJSONString());
		
		return $this;
	}
	
	public function createDELETELink($linkUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_LINK . '/' . $linkUOID);
		$this->request->setRequestMethod('DELETE');
		
		return $this;
	}
}

?>