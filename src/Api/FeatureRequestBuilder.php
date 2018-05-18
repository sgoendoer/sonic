<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Model\FeatureObject;

/**
 * Creates FEATURE requests
 * version 20160915
 *
 * author: Markus Beckmann, Senan Sharhan, Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class FeatureRequestBuilder extends  AbstractRequestBuilder
{
	const RESOURCE_NAME_FEATURE = 'FEATURE';
	
	public function createGETFeature($featureUOID = NULL)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		if($featureUOID != NULL)
			$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_FEATURE . '/' . $featureUOID);
		else
			$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_FEATURE);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
}

?>