<?php namespace sgoendoer\Sonic\examples;

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\API\LikeRequestBuilder;
use sgoendoer\Sonic\Model\LikeObject;
use sgoendoer\Sonic\Model\LikeObjectBuilder;

class LikeAPIExample
{
	public static function performGETLikeRequest($targetedGID, $likeID)
	{
		// create an instance of PersonRequestBuilder
		$likeRequest = new LikeRequestBuilder($targetedGID);
		
		// perform the request
		$response = $likeRequest->createGETLike($likeObjectID)->dispatch();
		
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
			return LikeObjectBuilder::buildFromJSON($response->getPayload());
		}
	}
	
	public static function performPOSTLikeRequest($targetedGID, $likedContentID)
	{
		// create an instance of PersonRequestBuilder
		$likeRequest = new LikeRequestBuilder($targetedGID);
		
		// create the LIKE object for the content to be liked
		$likeObject = (new LikeObjectBuilder())
						->objectID(UOID::createUOID())
						->targetID($likedContentID)
						->author(Sonic::getUserGlobalID())
						->build();
		
		// perform the request
		$response = $likeRequest->createGETLike($likeObject)->dispatch();
		
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
			// request was performed successfully
			return true;
		}
	}
	
	public static function performDELETELikeRequest($targetedGID, $likeObjectID)
	{
		// create an instance of PersonRequestBuilder
		$likeRequest = new LikeRequestBuilder($targetedGID);
		
		// perform the request
		$response = $likeRequest->createDELETELike($targetedGID, $likeObjectID)->dispatch();
		
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
			// request was performed successfully
			return true;
		}
	}
}

?>