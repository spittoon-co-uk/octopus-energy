<?php

require_once 'functions.php';

// Get the Octopus Energy product we're querying from a POSTed value
$product_code = $_POST['product_code'];
// Get the Octopus Energy tariff we're querying from a POSTed value
$tariff_code = $_POST['tariff_code'];
// Build the Octopus Energy endpoint we're querying
$query_array = array(
	// Now, but one year ago
	'period_from'=>date('Y-m-d\TH:i\Z', strtotime('-1 year')),
	// Now
	'period_to'=>date('Y-m-d\TH:i\Z'),
	// Specify the maximum allowed number of records per page
	'page_size'=>1500
	);
// Assemble query into GET parameters for an HTTP request
$query_parameters = http_build_query($query_array);
// Add the GET parameters to our URL
$endpoint = 'https://api.octopus.energy/v1/products/'.$product_code.'/electricity-tariffs/'.$tariff_code.'/standard-unit-rates/?'.$query_parameters;
// Query the Octopus Energy endpoint and assign its response to a variable
$initial_query = queryEndpoint( $endpoint );
// Decode the JSON response from the OE endpoint to an associative array
$initial_query = json_decode( $initial_query, true );

// Store some of the returned values in variables for ease of use
$total_results = $initial_query['count'];
$next = $initial_query['next'];
$previous = $initial_query['previous'];
$results = $initial_query['results'];


// Declare an empty array to store values in
$average_array = array();

// While there are multiple pages of results, keep querying the endpoints until we've got all the required records in our array
while ( count($results) < $total_results ) {
	$next_page = queryEndpoint( $next );
	$page = json_decode( $next_page, true );
	foreach ( $page['results'] as $result ) {
		if ( count($results) < $total_results ) {
			$results[] = $result;
		}
	}
	$next = $page['next'];
}

// Pass the associative array of data into a function to assign only the 'value_exc_vat' to the average array, ready to add them all together
$average_array = buildValueArrayFromJsonData( $results, $average_array );

// Divide the sum of all unit rates by the total count of results to get a MEAN average
$average_unit_rate = number_format( array_sum( $average_array ) / count( $average_array ), 2, '.', ',');

// Echo the period queried
echo 'Period from ' . $query_array['period_from'] . ', to ' . $query_array['period_to'] . '.<br>';
// Echo the total number of records we have retrieved for the past year
echo 'Total number of records: ' . $total_results . '.<br>';
// Echo the average unit rate
echo 'Average "value_exc_vat" of ' . count( $average_array ) . ' records: ' . $average_unit_rate . '.<br>';