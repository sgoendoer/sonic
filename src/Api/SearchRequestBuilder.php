<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Model\SearchQueryObject;
use sgoendoer\Sonic\Model\SearchResultObject;
use sgoendoer\Sonic\Identity\SocialRecordManager;

/**
 * Creates search requests
 * version 20151211
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class SearchRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_SEARCH = 'SEARCH';
	const RESOURCE_NAME_SEARCH_RESULT = 'RESULT';
	
	public function createPOSTSearchQuery($toGID, SearchQueryObject $searchQueryObject)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($socialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($socialRecord->getProfileLocation()) . $socialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_SEARCH);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($searchQueryObject->getJSONString());
		
		return $this;
	}
	
	public function createPOSTSearchResult($toGID, SearchResultObject $searchResultObject)
	{
		$socialRecord = SocialRecordManager::retrieveSocialRecord($toGID);
		
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($toPlatformURL));
		$this->request->setPath($this->getPathFromProfileLocation($toPlatformURL) . '/' . self::RESOURCE_NAME_SEARCH . '/' . $searchResultObject->getTargetID() . '/' . self::RESOURCE_NAME_SEARCH_RESULT);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($searchResultObject->getJSONString());
		
		return $this;
	}
}

?>