<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 18.01.2016
 * Time: 17:18
 */

namespace sgoendoer\Sonic\Model;


use sgoendoer\Sonic\Identity\UOID;

class QueryObjectBuilder
{
    protected $query = NULL;
    protected $type = NULL;
    protected $index = NULL;

    public function __construct()
    {
    }

    public static function buildFromJSON($json)
    {

        $jsonObject = json_decode($json);

        $builder = (new QueryObjectBuilder())
            ->index($jsonObject->index)
            ->type($jsonObject->type)
            ->query($jsonObject->query);

        return $builder->build();
    }

    public function index($index)
    {
        $this->index = $index;
        return $this;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function query($query)
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function build()
    {

        if ($this->index == NULL)
            $this->index = 'default';
        if ($this->type == NULL)
            throw new IllegalModelStateException(' Query type must not be null');
        if ($this->query == NULL)
            $this->query = '{ "match_all" : { }}';

        $Query = new QueryObject($this);

        return $Query;
    }
}