<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Identity\SocialRecordManager;
use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;

/**
 * Creates PROFILE requests
 * version 20150802
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class ProfileRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_PROFILE = 'PROFILE';
	
	public function createGETProfile($toGID)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_PROFILE);
		$this->request->setRequestMethod('GET');
		//die($body);
		//echo $this->request->toString();die();
		return $this;
	}
}

?>