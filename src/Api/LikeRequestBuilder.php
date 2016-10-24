<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Model\LikeObject;

/**
 * Creates LIKE requests
 * version 20160129
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class LikeRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_LIKE = 'LIKE';
	
	public function createGETLike($likeUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_LIKE . '/' . $likeUOID);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createPOSTLike(LikeObject $likeObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_LIKE);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($likeObject->getJSONString());
		
		return $this;
	}
	
	public function createDELETELike($likeUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_LIKE . '/' . $likeUOID);
		$this->request->setRequestMethod('DELETE');
		
		return $this;
	}
}

?>