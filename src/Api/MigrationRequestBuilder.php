<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;

/**
 * Creates LINK requests
 * version 20160404
 *
 * author: Senan Sharhan, Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class MigrationRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_MIGRATION = 'MIGRATION';
	
	public function createGETMigration($authToken)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_MIGRATION);
		$this->request->setRequestMethod('GET');
		$this->request->setHeaderAuthToken($authToken);
		
		return $this;
	}
	
	public function createDELETEUser($authToken)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_MIGRATION);
		$this->request->setRequestMethod('DELETE');
		$this->request->setHeaderAuthToken($authToken);
		
		return $this;
	}
}

?>