<?php
/**
 * Created by PhpStorm.
 * User: Senan Sharhan
 * Date: 02.10.2016
 * Time: 21:08
 */

namespace sgoendoer\sonic\src\Model;

use Illuminate\Support\Facades\Log;
use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Model\IllegalModelStateException;
use sgoendoer\Sonic\Model\RemoteObjectBuilder;
use sgoendoer\Sonic\Model\SignatureObject;
use sgoendoer\Sonic\Model\AlbumObject;

class AlbumObjectBuilder extends RemoteObjectBuilder
{

    protected $owner = NULL;
    protected $title = NULL;
    protected $thumbnailUrl = NULL;
    protected $description = NULL;
    protected $mediaItemCount = NULL;
    protected $MediaItems = array();
    protected $datetime = NULL;


    public function __construct()
    {

    }

    public static function buildFromJSON($json)
    {
        // TODO parse and verify json
        $jsonObject = json_decode($json);

        $signature = SignatureObject::createFromJSON(json_encode($jsonObject->signature));

        $builder =(new AlbumObjectBuilder())
            ->objectID($jsonObject->objectID)
            ->owner($jsonObject->owner)
            ->title($jsonObject->title)
            ->thumbnailUrl($jsonObject->thumbnailUrl)
            ->description($jsonObject->description)
            ->dateTime($jsonObject->datetime);

        foreach($jsonObject->MediaItems as $MediaItem)
            {
                $builder->addMediaItem(MediaItemObjectBuilder::buildFromJSON(json_encode($MediaItem)));
            }

        $builder->signature($signature);

        return     $builder->build();
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function owner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl;
    }

    public function thumbnailUrl($thumbnailUrl)
    {
        $this->thumbnailUrl = $thumbnailUrl;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function description($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getMediaItemCount()
    {
        return $this->mediaItemCount;
    }

    public function mediaItemCount($mediaItemCount)
    {
        $this->mediaItemCount = $mediaItemCount;
        return $this;
    }

    public function getDatetime()
    {
        return $this->datetime;
    }

    public function datetime($datetime = NULL)
    {
        if($datetime == NULL)
            $this->datetime = XSDDateTime::getXSDDateTime();
        else
            $this->datetime = $datetime;
        return $this;
    }


    public function addMediaItem($MediaItem)
    {
        $this->MediaItems[] = $MediaItem;
        return $this;
    }

    public function MediaItems($MediaItemArray)
    {
        $this->MediaItems = $MediaItemArray;
        return $this;
    }

    public function getMediaItems()
    {
        return $this->MediaItems;
    }

    public function build()
    {
        // TODO: Implement build() method.

        if($this->objectID == NULL)
            $this->objectID = UOID::createUOID();
        if($this->datetime == NULL)
            $this->datetime = XSDDateTime::getXSDDateTime();

        if(!UOID::isValid($this->objectID))
            throw new IllegalModelStateException('Invalid objectID');
        if(!GID::isValid($this->owner))
            throw new IllegalModelStateException('Invalid owner');
        if(!XSDDateTime::validateXSDDateTime($this->datetime))
            throw new IllegalModelStateException('Invalid datetime');
        if(!is_array($this->MediaItems))
            throw new IllegalModelStateException('Invalid MediaItems value');

        $AlbumObject =  new AlbumObject($this);

        if($AlbumObject->getSignature() == NULL)
            $AlbumObject->signObject();

        if(!$AlbumObject->verifyObjectSignature())
            throw new IllegalModelStateException('Invalid signature');

        return $AlbumObject;

    }
}