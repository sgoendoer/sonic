<?php namespace sgoendoer\Sonic\Api;

use sgoendoer\Sonic\Request\OutgoingRequest;
use sgoendoer\Sonic\Api\AbstractRequestBuilder;

/**
 * Created by PhpStorm.
 * Date: 15.02.2016
 * Time: 15:43
 * author: Senan Sharhan
 * copyright: Senan Sharhan  <senan.sharhan@hotmail.com>
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