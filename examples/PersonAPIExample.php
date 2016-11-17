<?php namespace sgoendoer\Sonic\examples;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\API\PersonRequestBuilder;
use sgoendoer\Sonic\Model\PersonObject;
use sgoendoer\Sonic\Model\PersonObjectBuilder;

class PersonAPIExample
{
	public static function performGETPersonRequest($targetedGID)
	{
		// create an instance of PersonRequestBuilder
		$personRequest = new PersonRequestBuilder($targetedGID);
		
		// perform the request
		$response = $personRequest->createGETPerson()->dispatch();
		
		// to access contents of the response, use
		// $response->getPayload(); <-- the actual object data
		// $response->getResponseBody(); <-- the complete response body
		
		if($response->getResponseStatusCode() != 200)
		{
			// in case the request returned something else thatn a 200
			throw new \Exception('Request failed with status code ' . $response->getResponseStatusCode());
		}
		else
		{
			// return the Person object from the responses payload
			return PersonObjectBuilder::buildFromJSON($response->getPayload());
		}
	}
}

?>