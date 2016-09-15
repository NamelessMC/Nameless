<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

$API = $queries->getWhere('donation_settings', array("name", "=", "api_key"));
$API = $API[0]->value;
 
$mcs_payments = file_get_contents('https://mcstock.net/api/v1/?key_type=server&key=' . $API . '&action=get_orders');
$mcs_payments = json_decode($mcs_payments, true);

if(isset($mcs_payments['result']) && $mcs_payments['result'] == 'success'){
	// Successful query
} else {
	die('Error querying the API');
}

//echo '<pre>', print_r($mcs_payments), '</pre>';

$mcs_categories = file_get_contents('https://mcstock.net/api/v1/?key_type=server&key=' . $API . '&action=get_categories');
$mcs_categories = json_decode($mcs_categories, true);

if(isset($mcs_categories['result']) && $mcs_categories['result'] == 'success'){
	// Successful query
	$mcs_packages = array();
	foreach($mcs_categories['data'] as $id => $category){
		// Get packages
		$mcs_packages_query = file_get_contents('https://mcstock.net/api/v1/?key_type=server&key=' . $API . '&action=get_items&category_id=' . $id);
		$mcs_packages_query = json_decode($mcs_packages_query, true);
		$mcs_packages_query['cid'] = $id;
		
		if(isset($mcs_packages_query['result']) && $mcs_packages_query['result'] == 'success'){
			// Successful query
			$mcs_packages[] = $mcs_packages_query;
		} else {
			die('Error querying the API');
		}
	}
} else {
	die('Error querying the API');
}

//echo '<pre>', print_r($mcs_packages), '</pre>';
//echo '<pre>', print_r($mcs_categories), '</pre>';
