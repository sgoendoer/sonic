<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;
use sgoendoer\Sonic\Model\ActivityObject;
use sgoendoer\Sonic\Api\CommentRequestBuilder;
use sgoendoer\Sonic\Api\LikeRequestBuilder;
use sgoendoer\Sonic\Api\TagRequestBuilder;

/**
 * Creates Activity requests
 * version 20180110
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ActivityRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_STREAM = 'ACTIVITY';
	
	public function createGETActivity($activityUOID = NULL)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		if($streamItemUOID != NULL)
			$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_ACTIVITY . '/' . $activityUOID);
		else
			$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_ACTIVITY);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createGETActivityComment($activityUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_ACTIVITY . '/' . $activityUOID . '/' . CommentRequestBuilder::RESOURCE_NAME_COMMENT);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createGETActivityLike($activityUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_ACTIVITY . '/' . $activityUOID . '/' . LikeRequestBuilder::RESOURCE_NAME_LIKE);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createGETActivityTag($activityUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_ACTIVITY . '/' . $activityUOID . '/' . TagRequestBuilder::RESOURCE_NAME_TAG);
		$this->request->setRequestMethod('GET');
		
		return $this;
	}
	
	public function createPOSTActivity(ActivityObject $activityObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_ACTIVITY);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($activityObject->getJSONString());
		
		return $this;
	}
	
	public function createPUTActivity(ActivityObject $activityObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_ACTIVITY . '/' . $activityObject->getObjectID());
		$this->request->setRequestMethod('PUT');
		$this->request->setRequestBody($activityObject->getJSONString());
		
		return $this;
	}
	
	public function createDELETEActivity($activityUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_ACTIVITY . '/' . $activityUOID);
		$this->request->setRequestMethod('DELETE');
		
		return $this;
	}
}

?>