<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\BasicObject;
use sgoendoer\json\JSONObject;

/**
 * Represents a query object
 * version 20160404
 *
 * author: Senan Sharhan, Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class QueryObject extends BasicObject
{
	protected $query			= NULL;
	protected $type				= NULL;
	protected $index			= NULL;
	
	public function __construct(QueryObjectBuilder $builder)
	{
		parent::__construct();
		
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
		$this->query = '{"match":{"' . $column . '":"' . $value . '"}}';
		return $this;
	}
	
	public function getJSONString()
	{
		$json = '{'
			. '"index":"' . $this->index . '",'
			. '"type":"' . $this->type . '",'
			. '"query":' . $this->query . ''
			. '}';
		
		return $json;
	}
}

?>