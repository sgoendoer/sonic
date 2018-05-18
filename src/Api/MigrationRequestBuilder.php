<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;

/**
 * Creates MIGRATION requests
 * version 20180110
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class MigrationRequestBuilder extends AbstractRequestBuilder
{
	const RESOURCE_NAME_MIGRATION = 'MIGRATION';
	
	public function createPOSTMigration($migrationObject)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_MIGRATION);
		$this->request->setRequestMethod('POST');
		$this->request->setRequestBody($migrationObject->getJSONString());
				
		return $this;
	}
	
	public function createDELETEMigration($migrationUOID)
	{
		$this->request = new OutgoingRequest();
		
		$this->request->setServer($this->getDomainFromProfileLocation($this->targetSocialRecord->getProfileLocation()));
		$this->request->setPath($this->getPathFromProfileLocation($this->targetSocialRecord->getProfileLocation()) . $this->targetSocialRecord->getGlobalID() . '/' . self::RESOURCE_NAME_MIGRATION . '/' . $migrationUOID);
		$this->request->setRequestMethod('DELETE');
		
		return $this;
	}
}

?>