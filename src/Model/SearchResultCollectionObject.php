<?php namespace sgoendoer\Sonic\Model;

use sgoendoer\Sonic\Model\SearchResultCollectionObjectBuilder;
use sgoendoer\Sonic\Model\ReferencingObject;
use sgoendoer\Sonic\Date\XSDDateTime;

/**
 * Represents a SEARCH RESULT COLLECTION object
 * version 20160127
 *
 * author: Sebastian Goendoer
 * copyright: Sebastian Goendoer <sebastian.goendoer@rwth-aachen.de>
 */
class SearchResultCollectionObject extends ReferencingObject
{
    const JSONLD_CONTEXT = 'http://sonic-project.net/';
    const JSONLD_TYPE = 'search-result-collection';

    protected $platformGID = NULL;
    protected $datetime = NULL;
    protected $results = NULL;

    public function __construct(SearchResultCollectionObjectBuilder $builder)
    {
        parent::__construct($builder->getObjectID(), $builder->getTargetID());

        $this->platformGID = $builder->getPlatformGID();
        $this->datetime = $builder->getDatetime();
        $this->results = $builder->getResults();
    }

    public function getPlatformGID()
    {
        return $this->platformGID;
    }

    public function setPlatformGID($platformGID)
    {
        $this->platformGID = $platformGID;
        return $this;
    }

    public function getDatetime()
    {
        return $this->datetime;
    }

    public function setDatetime($datetime)
    {
        if ($datetime == NULL)
            $this->datetime = XSDDateTime::getXSDDateTime();
        else
            $this->datetime = $datetime;
        return $this;
    }

    public function setResult(SearchResultObject $result)
    {
        $this->results[] = $result;
        // TODO manually implement array_unique
        return $this;
    }

    public function setResults($resultArray)
    {
        $this->results = $resultArray;
        return $this;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function getJSONString()
    {
        $json = '{'
            . '"@context": "' . SearchResultObject::JSONLD_CONTEXT . '",'
            . '"@type": "' . SearchResultObject::JSONLD_TYPE . '",'
            . '"objectID": "' . $this->objectID . '",'
            . '"targetID": "' . $this->targetID . '",'
            . '"resultOwnerGID": "' . $this->platformGID . '",'
            . '"datetime": "' . $this->datetime . '",'
            . '"results": [';

        foreach ($this->results as $result) {
            $json .= $result->getJSON();
            if ($result !== end($this->results)) $json .= ',';
        }

        $json .= ']}';

        return $json;
    }

    public static function validateJSON($json)
    {
        $result = \Jsv4::validate(json_decode($json), json_decode(SearchResultCollectionObject::SCHEMA));

        if ($result->valid == true)
            return true;
        else
            throw new \Exception('invalid JSON format for Tag: ' . $result->errors->message);
    }

    const SCHEMA = '{
		"$schema": "http://json-schema.org/draft-04/schema#",
		"id": "http://jsonschema.net/sonic/searchResultCollection,
		"type": "object",
		"properties":
		{
			"objectID":
			{
				"id": "http://jsonschema.net/sonic/searchResultCollection/objectID",
				"type": "string"
			},
			"targetID":
			{
				"id": "http://jsonschema.net/sonic/searchResultCollection/targetID",
				"type": "string"
			},
			"platformGID":
			{
				"id": "http://jsonschema.net/sonic/searchResultCollection/platformGID",
				"type": "string"
			},
			"datetime":
			{
				"id": "http://jsonschema.net/sonic/searchResultCollection/datetime,
				"type": "string"
			},
			"results":
			{
				"id": "http://jsonschema.net/sonic/searchResultCollection/results,
				"type": "array"
			}
		},
		"required": [
			"objectID",
			"targetID",
			"platformGID",
			"datetime",
			"results"
		]
	}';
}

?>