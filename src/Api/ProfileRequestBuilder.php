<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;

/**
 * Creates PROFILE requests
 * version 20150802
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ProfileRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_PROFILE = 'PROFILE';
	
	public function createGETProfile()
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_PROFILE);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
}

?>