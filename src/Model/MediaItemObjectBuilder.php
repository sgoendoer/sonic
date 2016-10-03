<?php
/**
 * Created by PhpStorm.
 * User: Senan Sharhan
 * Date: 02.10.2016
 * Time: 22:44
 */

namespace sgoendoer\sonic\src\Model;

use Illuminate\Support\Facades\Log;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Model\IllegalModelStateException;
use sgoendoer\Sonic\Model\ReferencingRemoteObjectBuilder;
use sgoendoer\Sonic\Model\SignatureObject;
use sgoendoer\Sonic\Model\MediaItemObject;

class MediaItemObjectBuilder extends ReferencingRemoteObjectBuilder
{

    protected $mimetype = NULL;
    protected $type = NULL;
    protected $url = NULL;
    protected $datetime = NULL;

    public function __construct()
    {
    }

    public static function buildFromJSON($json)
    {
        // TODO parse and verify json
        $jsonObject = json_decode($json);

        $signature = SignatureObject::createFromJSON(json_encode($jsonObject->signature));

        return (new MediaItemObjectBuilder())
            ->objectID($jsonObject->objectID)
            ->targetID($jsonObject->targetID)
            ->mimetype($jsonObject->mimetype)
            ->type($jsonObject->type)
            ->url($jsonObject->url)
            ->datetime($jsonObject->datetime)
            ->signature($signature)
            ->build();
    }


    public function getMimetype()
    {
        return $this->mimetype;
    }

    public function mimetype($mimetype)
    {
        $this->mimetype = $mimetype;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function type($type)
    {
        $this->type = $type;
        return $this;
    }


    public function getUrl()
    {
        return $this->url;
    }

    public function url($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getDatetime()
    {
        return $this->datetime;
    }

    public function datetime($datetime = NULL)
    {
        if ($datetime == NULL)
            $this->datetime = XSDDateTime::getXSDDateTime();
        else
            $this->datetime = $datetime;
        return $this;
    }

    public function build()
    {
        // TODO: Implement build() method.

        if ($this->objectID == NULL)
            $this->objectID = UOID::createUOID();
        if ($this->datetime == NULL)
            $this->datetime = XSDDateTime::getXSDDateTime();

        if (!UOID::isValid($this->objectID))
            throw new IllegalModelStateException('Invalid objectID');
        if (!UOID::isValid($this->targetID))
            throw new IllegalModelStateException('Invalid targetID');
        if (!XSDDateTime::validateXSDDateTime($this->datetime))
            throw new IllegalModelStateException('Invalid datePublished');

        $MediaItemObject = new MediaItemObject($this);


        if ($MediaItemObject->getSignature() == NULL)
            $MediaItemObject->signObject();

        if (!$MediaItemObject->verifyObjectSignature())
            throw new IllegalModelStateException('Invalid signature');

        return $MediaItemObject;
    }
}