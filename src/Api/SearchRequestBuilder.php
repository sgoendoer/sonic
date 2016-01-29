<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Model\SearchQueryObject;
use sgoendoer\Sonic\Model\SearchResultObject;

/**
 * Creates search requests
 * version 20160129
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class SearchRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_SEARCH = 'SEARCH';
	const RESOURCE_NAME_SEARCH_RESULT = 'RESULT';
	
	public function createPOSTSearchQuery(SearchQueryObject $searchQueryObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_SEARCH);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($searchQueryObject->getJSONString());
		
		return $this;
	}
	
	public function createPOSTSearchResult(SearchResultObject $searchResultObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($toPlatformURL));
		$this->request->setPath($this->getPathFromProfileLocation($toPlatformURL) . '/' . self::RESOURCE_NAME_SEARCH . '/' . $searchResultObject->getTargetID() . '/' . self::RESOURCE_NAME_SEARCH_RESULT);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($searchResultObject->getJSONString());
		
		return $this;
	}
}

?>