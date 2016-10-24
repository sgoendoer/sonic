<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Model\TagObject;

/**
 * Creates TAG requests
 * version 20160129
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class TagRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_TAG = 'TAG';
	
	public function createGETTag($tagUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_TAG . '/' . $tagUOID);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createPOSTTag(TagObject $tagObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_TAG);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($tagObject->getJSONString());
		
		return $this;
	}
	
	public function createDELETETag($tagUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_TAG . '/' . $tagUOID);
		$this->request->setRequestMethod('DELETE');
		
		return $this;
	}
}

?>