<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;

/**
 * Creates IMAGE requests
 * version 20170612
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ImageRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_IMAGE = 'IMAGE';
	
	public function createGETImage($imageUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_IMAGE . '/' . $imageUOID);
		$this->request->setRequestMethod('GET');
		$this->request->setRequestBody('');
		
		return $this;
	}
}

?>