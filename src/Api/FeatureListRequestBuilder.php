<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Model\FeatureListObject;

/**
 * Creates FEATURE requests
 * version 20160915
 *
 * author: Markus Beckmann, Senan Sharhan, Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class FeatureListRequestBuilder extends  AbstractRequestBuilder
{
	const RESOURCE_NAME_FEATURE = 'FEATURE';

	public function createGETFeatureList()
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_FEATURE);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createPOSTFeatureList(FeatureListObject $featureListObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_FEATURE);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($featureListObject->getJSONString());
		
		return $this;
	}
}

?>