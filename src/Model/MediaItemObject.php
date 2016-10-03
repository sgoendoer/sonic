<?php
/**
 * Created by PhpStorm.
 * User: Senan Sharhan
 * Date: 02.10.2016
 * Time: 22:44
 */

namespace sgoendoer\sonic\src\Model;


use sgoendoer\Sonic\Date\XSDDateTime;

use sgoendoer\Sonic\Model\ReferencingRemoteObject;


class MediaItemObject extends ReferencingRemoteObject
{

    const JSONLD_CONTEXT = 'http://sonic-project.net/';
    const JSONLD_TYPE = 'MediaItem';

    protected $mimetype = NULL;
    protected $type = NULL;
    protected $url = NULL;
    protected $datetime = NULL;


    public function __construct(MediaItemObjectBuilder $builder)
    {
        parent::__construct($builder->getObjectID(), $builder->getTargetID());

        $this->mimetype = $builder->getMimetype();
        $this->type = $builder->getType();
        $this->url = $builder->getUrl();
        $this->datetime = $builder->getDatetime();
        $this->signature = $builder->getSignature();
    }


    public function getMimetype()
    {
        return $this->mimetype;
    }

    public function setMimetype($mimetype)
    {
        $this->mimetype = $mimetype;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getDatetime()
    {
        return $this->datetime;
    }

    public function setDatetime($datetime = NULL)
    {
        if ($datetime == NULL)
            $this->datetime = XSDDateTime::getXSDDateTime();
        else
            $this->datetime = $datetime;
        $this->invalidate();
        return $this;
    }

    protected function getStringForSignature()
    {
        // TODO: Implement getJSONString() method.
        return $this->objectID
        . $this->targetID
        . $this->mimetype
        . $this->type
        . $this->url
        . $this->datetime;
    }

    public function getJSONString()
    {
        // TODO: Implement getStringForSignature() method.
        $json = '{'
            . '"@context":"' . MediaItemObject::JSONLD_CONTEXT . '",'
            . '"@type":"' . MediaItemObject::JSONLD_TYPE . '",'
            . '"objectID":"' . $this->objectID . '",'
            . '"targetID":"' . $this->targetID . '",'
            . '"mimetype":"' . $this->mimetype . '",'
            . '"type":"' . $this->type . '",'
            . '"url":"' . $this->url . '",'
            . '"datetime":"' . $this->datetime . '",'
            . '"signature":' . $this->signature->getJSONString() . ''
            . '}';

        return $json;
    }
}