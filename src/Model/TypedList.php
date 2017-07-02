<?php namespace sgoendoer\Sonic\Model;

/**
 * Typed list
 * version 20170702
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian [dot] goendoer [at] gmail [dot] com>
 */
class TypedList
{
    public $key = NULL;
    public $value = NULL;
    public $description = NULL;

    public function __construct($key, $value, $description = NULL)
    {
        if($key == '' || $key == NULL)
            throw new IllegalModelStateException('parameter $key cannot be empty');
        if($value == '' || $value == NULL)
            throw new IllegalModelStateException('parameter $value cannot be empty');

        $this->key = $key;
        $this->value = $value;

        if($description != NULL)
            $this->description = $description;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        if($key == '' || $key == NULL)
            throw new IllegalModelStateException('parameter $key cannot be empty');

        $this->key = $key;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        if($value == '' || $value == NULL)
            throw new IllegalModelStateException('parameter $value cannot be empty');

        $this->value = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
}

?>
