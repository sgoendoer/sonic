<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\ImageObjectBuilder;

use sgoendoer\Sonic\Model\ILikeableObject;
use sgoendoer\Sonic\Model\ITagableObject;
use sgoendoer\Sonic\Model\ICommentableObject;

/**
 * Represents a IMAGE object
 * version 20170612
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ImageObject extends Object implements ILikeableObject, ICommentableObject, ITagableObject
{
	const JSONLD_CONTEXT = 'http://sonic-project.net/';
	const JSONLD_TYPE = 'image';
	
	protected $owner					= NULL;
	protected $targetID					= NULL;
	protected $datetime					= NULL;
	protected $title					= NULL;
	protected $description				= NULL;
	protected $imageThumbnailData		= NULL;
	protected $imageData				= NULL;
	protected $imageWidth				= NULL;
	protected $imageHeight				= NULL;
	protected $imageCodec				= NULL; // jpg jpeg, png, gif
	
	public function __construct(ImageObjectBuilder $builder)
	{
		parent::__construct($builder->getObjectID());
		
		$this->owner = $builder->getOwner();
		$this->targetID = $builder->getTargetID();
		$this->datetime = $builder->getDatetime();
		$this->title = $builder->getTitle();
		$this->description = $builder->getDescription();
		$this->imageThumbnailData = $builder->getImageThumbnailData();
		$this->imageData = $builder->getImageData();
		$this->imageWidth = $builder->getImageWidth();
		$this->imageHeight = $builder->getImageHeight();
		$this->imageCodec = $builder->getImageCodec();
	}
	
	public function setOwner($owner)
	{
		$this->owner = $owner;
		return $this;
	}
	
	public function setDatetime($datetime)
	{
		$this->datetime = $datetime;
		return $this;
	}
	
	public function setTitle($title)
	{
		$this->title = title;
		return $this;
	}
	
	public function setDescription($description)
	{
		$this->Description = $description;
		return $this;
	}
	
	public function setImageThumbnailData($imageThumbnailData)
	{
		$this->imageThumbnailData = $imageThumbnailData;
		return $this;
	}
	
	public function setImageData($imageData)
	{
		$this->imageData = $imageData;
		return $this;
	}
	
	public function setImageWidth($imageWidth)
	{
		$this->imageWidth = $imageWidth;
		return $this;
	}
	
	public function setImageHeight($imageHeight)
	{
		$this->imageHeight = $imageHeight;
		return $this;
	}
	
	public function setImageCodec($imageCodec)
	{
		$this->imageCodec = $imageCodec;
		return $this;
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	public function getDatetime()
	{
		return $this->datetime;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getDescription()
	{
		return $this->Description;
	}
	
	public function getImageThumbnailData()
	{
		return $this->imageThumbnailData;
	}
	
	public function getImageData()
	{
		return $this->imageData;
	}
	
	public function getImageWidth()
	{
		return $this->imageWidth;
	}
	
	public function getImageHeight()
	{
		return $this->imageHeight;
	}
	
	public function getImageCodec()
	{
		return $this->imageCodec;
	}
	
	public function getJSONString()
	{
		$json = '{'
			. '"@context":"' . ImageObject::JSONLD_CONTEXT . '",'
			. '"@type":"' . ImageObject::JSONLD_TYPE . '",'
			. '"objectID":"' . $this->objectID . '",' 
			. '"owner":"' . $this->owner . '",' 
			. '"datetime":"' . $this->datetime . '",'
			. '"title":"' . $this->title . '",' 
			. '"description":"' . $this->description . '",';
		
		if($this->imageThumbnailData != NULL) 
		{
			$json .= '"imageThumbnailData":"' . $this->imageThumbnailData . '",'; 
		}
		
		$json .= '"imageData":"' . $this->imageData . '",' 
			. '"imageWidth":"' . $this->imageWidth . '",' 
			. '"imageHeight":"' . $this->imageHeight . '",' 
			. '"imageCodec":"' . $this->imageCodec . '"';
		
		$json .= '}';
		
		return $json;
	}
	
	const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/person",
		"type": "object",
		"properties":
		{
			"objectID": { "type": "string" },
			"owner": { "type": "string" },
			"title": { "type": "string" },
			"description": { "type": "string" },
			"imageThumbnailData": { "type": "string" },
			"imageData": { "type": "string" },
			"imageWidth": { "type": "integer" },
			"imageHeight": { "type": "integer" },
			"imageCodec": { "type": "string" }
		},
		"required": [
			"objectID",
			"owner",
			"imageData",
			"imageDataWidth",
			"imageDataHeight",
			"imageCodec"
		]
	}';
}

?>