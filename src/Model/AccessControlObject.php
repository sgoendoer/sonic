<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 03.10.2016
 * Time: 04:28
 */

namespace sgoendoer\sonic\src\Model;


use sgoendoer\Sonic\Date\XSDDateTime;
use sgoendoer\Sonic\Model\ReferencingRemoteObject;

class AccessControlObject extends ReferencingRemoteObject
{

    const JSONLD_CONTEXT = 'http://sonic-project.net/';
    const JSONLD_TYPE = 'Access_Control';

    protected $owner = NULL;
    protected $type = NULL;
    protected $allow = array();
    protected $deny = array();
    protected $datetime = NULL;

    public function __construct(AccessControlObjectBuilder $builder)
    {
        parent::__construct($builder->getObjectID(), $builder->getTargetID());

        $this->owner = $builder->getOwner();
        $this->type = $builder->getType();
        $this->allow = $builder->getAllow();
        asort($this->allow);
        $this->deny = $builder->getDeny();
        asort($this->deny);
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

    public function getType()
    {
        return $this->type;
    }

    public function setTitle($type)
    {
        $this->type = $type;
        return $this;
    }
    public function getDatetime()
    {
        return $this->datetime;
    }

    public function setDatetime($datetime)
    {
        if($datetime == NULL)
            $this->datetime = XSDDateTime::getXSDDateTime();
        else
            $this->datetime = $datetime;
        return $this;
    }

    public function addAllow($globalID)
    {
        $this->allow[] = $globalID;
        asort($this->allow);
        $this->invalidate();
        return $this;
    }

    public function setAllow($allowArray)
    {
        $this->allow = $allowArray;
        asort($this->allow);
        $this->invalidate();
        return $this;
    }

    public function getAllow()
    {
        return $this->allow;
    }

    public function addDeny($globalID)
    {
        $this->deny[] = $globalID;
        asort($this->deny);
        $this->invalidate();
        return $this;
    }

    public function setDeny($denyArray)
    {
        $this->deny = $denyArray;
        asort($this->deny);
        $this->invalidate();
        return $this;
    }

    public function getDeny()
    {
        return $this->deny;
    }

    public function getJSONString()
    {
        // TODO: Implement getJSONString() method.

        $json =  '{'
            . '"@context":"' . AccessControlObject::JSONLD_CONTEXT . '",'
            . '"@type":"' . AccessControlObject::JSONLD_TYPE . '",'
            . '"objectID":"' . $this->objectID . '",'
            . '"targetID":"' . $this->targetID . '",'
            . '"owner":"' . $this->owner . '",'
            . '"type":"' . $this->type . '",'
            . '"datetime":"' . $this->datetime . '",'
            . '"allow":[';

        asort($this->allow);

        foreach($this->allow as $member)
        {
            $json .= '"' . $member . '"';
            if($member !== end($this->allow)) $json .= ',';
        }

        $json .= '],';

        $json .= '"deny":[';
        asort($this->deny);

        foreach($this->deny as $member)
        {
            $json .= '"' . $member . '"';
            if($member !== end($this->deny)) $json .= ',';
        }

        $json .= '],';

        $json .= '"signature":' . $this->signature->getJSONString()
            . '}';

        return $json;
    }

    protected function getStringForSignature()
    {
        $string = $this->objectID
            . $this->targetID
            . $this->owner
            . $this->type
            . $this->datetime;

        asort($this->allow);
        foreach($this->allow as $member)
            $string .= $member;

        asort($this->deny);
        foreach($this->deny as $member)
            $string .= $member;

        return $string;

    }
}