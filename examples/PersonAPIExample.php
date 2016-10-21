<?php namespace sgoendoer\Sonic\examples;

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
		
		// return the Person object from the responses payload
		return PersonObjectBuilder::buildFromJSON($response->getPayload());
	}
}

?>