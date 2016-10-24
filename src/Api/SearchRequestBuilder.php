<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Model\SearchQueryObject;
use sgoendoer\Sonic\Model\SearchResultObject;
use sgoendoer\Sonic\Model\SearchResultCollectionObject;
use sgoendoer\Sonic\Identity\SocialRecordManager;

/**
 * Creates search requests
 * version 20151211
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
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
	
	public function createPOSTSearchResult(SearchResultCollectionObject $searchResultCollectionObject)
	{
		$this->request = new OutgoingRequest();
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_SEARCH . '/' . $searchResultCollectionObject->getTargetID() . '/' . self::RESOURCE_NAME_SEARCH_RESULT);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($searchResultCollectionObject->getJSONString());
		
		return $this;
	}
}

?>