<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Identity\GID;
use sgoendoer\Sonic\Model\ImageObject;
use sgoendoer\Sonic\Model\ObjectBuilder;
use sgoendoer\Sonic\Model\IllegalModelStateException;

/**
 * Builder class for a IMAGE object
 * version 20170613
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class ImageObjectBuilder extends ObjectBuilder
{
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
	
	public function __construct()
	{}
	
	public static function buildFromJSON($json)
	{
		// TODO parse and verify json
		$json = json_decode($json);
		
		$builder = new ImageObjectBuilder();
		$builder->objectID($json->objectID)
				->owner($json->owner)
				->targetID($json->targetID)
				->datetime($json->datetime)
				->title($json->title)
				->description($json->description)
				->imageData($json->imageData)
				->imageWidth($json->imageWidth)
				->imageHeight($json->imageHeight)
				->imageCodec($json->imageCodec);
				
		if(property_exists($json, 'imageThumbnailData'))
			$builder->imageThumbnailData($json->imageThumbnailData);
		
		return $builder->build();
	}
	
	public function owner($owner)
	{
		$this->owner = $owner;
		return $this;
	}
	
	public function datetime($datetime)
	{
		$this->datetime = $datetime;
		return $this;
	}
	
	public function title($title)
	{
		$this->title = title;
		return $this;
	}
	
	public function description($description)
	{
		$this->Description = $description;
		return $this;
	}
	
	public function imageThumbnailData($imageThumbnailData)
	{
		$this->imageThumbnailData = $imageThumbnailData;
		return $this;
	}
	
	public function imageData($imageData)
	{
		$this->imageData = $imageData;
		return $this;
	}
	
	public function imageWidth($imageWidth)
	{
		$this->imageWidth = $imageWidth;
		return $this;
	}
	
	public function imageHeight($imageHeight)
	{
		$this->imageHeight = $imageHeight;
		return $this;
	}
	
	public function imageCodec($imageCodec)
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
	
	public function getImageThumbnailWidth()
	{
		return $this->imageThumbnailWidth;
	}
	
	public function getImageWidth()
	{
		return $this->imageWidth;
	}
	
	public function getImageThumbnailHeight()
	{
		return $this->imageThumbnailHeight;
	}
	
	public function getImageData()
	{
		return $this->imageData;
	}
	
	public function getImageHeight()
	{
		return $this->imageHeight;
	}
	
	public function getImageCodec()
	{
		return $this->imageCodec;
	}
	
	public function build()
	{
		if($this->objectID == NULL)
			$this->objectID = UOID::createUOID();
			
		if(!UOID::isValid($this->objectID))
			throw new IllegalModelStateException('Invalid objectID');
		if(!GID::isValid($this->owner))
			throw new IllegalModelStateException('Invalid owner');
		
		return new ImageObject($this);
	}
}

?>