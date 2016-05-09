<?php


namespace sgoendoer\Sonic\Model;
use sgoendoer\Sonic\Identity\UOID;
use sgoendoer\Sonic\Model\IllegalModelStateException;
use sgoendoer\Sonic\Model\FeatureObject;


class FeatureObjectBuilder extends ObjectBuilder
{

    protected $featureNamespace			        = NULL;
    protected $name     			            = NULL;
    protected $version  			            = NULL;
    protected $compatibility_version			= NULL;
    protected $api_path             			= NULL;


    public function __construct()
    {
    }
    
    public function build()
    {
        if($this->objectID == NULL)
            $this->objectID = UOID::createUOID();


        if(!UOID::isValid($this->objectID))
            throw new IllegalModelStateException('Invalid objectID');
        

        $featureObject = new FeatureObject($this);
        

        return $featureObject;
    }



    public static function buildFromJSON($json)
    {
       
        $json = json_decode($json);
        

        $builder = new FeatureObjectBuilder();
        $builder->objectID($json->objectID)
            ->featureNamespace($json->namespace)
            ->name($json->name)
            ->version($json->version)
            ->compatibilityVersion($json->compatibility_version)
            ->apiPath($json->api_path);
        return $builder->build();
    }


    public function getFeatureNamespace()
    {
        return $this->featureNamespace;
    }

    public function featureNamespace($namespace)
    {
        $this->featureNamespace = $namespace;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function version($version)
    {
        $this->version = $version;
        return $this;
    }

    public function getCompatibilityVersion()
    {
        return $this->compatibility_version;
    }

    public function compatibilityVersion($compatibility_version)
    {
        $this->compatibility_version = $compatibility_version;
        return $this;
    }

    public function getApiPath()
    {
        return $this->api_path;
    }
    
    public function apiPath($api_path)
    {
        $this->api_path = $api_path;
        return $this;
    }


   

}