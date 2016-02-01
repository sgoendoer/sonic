<?php
/**
 * Created by PhpStorm.
 * User: senan
 * Date: 18.01.2016
 * Time: 16:44
 */

namespace sgoendoer\Sonic\Model;


use sgoendoer\json\JSONObject;

class QueryObject
{
    protected $query = NULL;
    protected $type = NULL;
    protected $index = NULL;

    public function __construct(QueryObjectBuilder $builder)
    {

        $this->index = $builder->getIndex();
        $this->type = $builder->getType();
        $this->query = $builder->getQuery();

    }

    public function setIndex($index)
    {
        $this->index = $index;
        return $this;
    }

    public function getIndex()
    {
        return $this->index;
    }


    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery()
    {
        $query = new JSONObject($this->query);
        return $query;
    }

    public function addMatch($column, $value)
    {
        $this->query = '{' . '"match": ' . '{' . '"' . $column . '":"' . $value . '"' . ' }' . '}';
        return $this;
    }


    public function getJSONString()
    {
        $json = '{'
            . '"index": "' . $this->index . '",'
            . '"type": "' . $this->type . '",'
            . '"query": ' . $this->query . ''
            . '}';

        return $json;
    }

}