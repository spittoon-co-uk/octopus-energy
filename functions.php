<?php

function queryEndpoint( $endpoint ) {

	// Initialise cURL
	$ch = curl_init();
 
	//Set the URL that you want to GET by using the CURLOPT_URL option.
	curl_setopt( $ch, CURLOPT_URL, $endpoint );
	 
	//Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	 
	//Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	 
	//Execute the request.
	$response = curl_exec( $ch );
	 
	//Close the cURL handle.
	curl_close( $ch );
	 
	//Return the response.
	return $response;

}

function buildValueArrayFromJsonData( $data, $average_array ) {

	foreach ( $data as $result ) {
		$value_exc_vat = $result['value_exc_vat'];
		$average_array[] = $value_exc_vat;
	}

	return $average_array;

}