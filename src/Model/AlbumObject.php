<?php
/**
 * Created by PhpStorm.
 * User: Senan Sharhan
 * Date: 02.10.2016
 * Time: 21:03
 */

namespace sgoendoer\sonic\src\Model;


use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\RemoteObject;

class AlbumObject extends RemoteObject
{

    const JSONLD_CONTEXT = 'http://sonic-project.net/';
    const JSONLD_TYPE = 'Album';

    protected $owner = NULL;
    protected $title = NULL;
    protected $thumbnailUrl = NULL;
    protected $description = NULL;
    protected $mediaItemCount = NULL;
    protected $MediaItems = array();
    protected $datetime = NULL;

    public function __construct(AlbumObjectBuilder $builder)
    {
        parent::__construct($builder->getObjectID());

        $this->owner = $builder->getOwner();
        $this->title = $builder->getTitle();
        $this->thumbnailUrl = $builder->getThumbnailUrl();
        $this->description = $builder->getDescription();
        $this->mediaItemCount = $builder->getMediaItemCount();
        $this->addMediaItemArray($builder->getMediaItems());
        $this->datetime = $builder->getDatetime();
        $this->signature = $builder->getSignature();
    }


    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;
        $this->invalidate();
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl;
    }

    public function setThumbnailUrl($thumbnailUrl)
    {
        $this->thumbnailUrl = $thumbnailUrl;
        return $this;
    }


    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getMediaItemCount()
    {
        return $this->mediaItemCount;
    }

    public function setMediaItemCount($mediaItemCount)
    {
        $this->mediaItemCount = $mediaItemCount;
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

    public function addMediaItemArray($MediaItemArray)
    {
        $this->MediaItems = array_merge($this->MediaItems, $MediaItemArray);
    }

    public function addMediaItem(MediaItemObject $MediaItem)
    {
        $this->MediaItems[] = $MediaItem;
    }

    public function getMediaItems()
    {
        return $this->MediaItems;
    }

    protected function getStringForSignature()
    {
        // TODO: Implement getStringForSignature() method.
        return $this->objectID
        . $this->owner
        . $this->title
        . $this->thumbnailUrl
        . $this->description
        . $this->mediaItemCount
        . $this->datetime;
    }

    public function getJSONString()
    {
        // TODO: Implement getJSONString() method.

        $json = '{'
            . '"@context":"' . AlbumObject::JSONLD_CONTEXT . '",'
            . '"@type":"' . AlbumObject::JSONLD_TYPE . '",'
            . '"objectID":"' . $this->objectID . '",'
            . '"owner":"' . $this->owner . '",'
            . '"title":"' . $this->title . '",'
            . '"thumbnailUrl":"' . $this->thumbnailUrl . '",'
            . '"description":"' . $this->description . '",'
            . '"mediaItemCount":"' . $this->mediaItemCount . '",'
            . '"datetime":"' . $this->datetime . '",'
            . '"MediaItems":[';

        foreach ($this->MediaItems as $mediaItem) {
            $json .= $mediaItem->getJSONString();
            if ($mediaItem !== end($this->MediaItems)) $json .= ',';
        }
        $json .= '],';

        $json .= '"signature":' . $this->signature->getJSONString()
            . '}';

        return $json;
    }

}