<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

$API = $queries->getWhere('donation_settings', array("name", "=", "api_key"));
$API = $API[0]->value;
 
$bc_payments = file_get_contents('https://api.buycraft.net/v4?action=payments&secret=' . $API);
$bc_payments = json_decode($bc_payments, true);

$bc_packages = file_get_contents('https://api.buycraft.net/v4?action=packages&secret=' . $API);
$bc_packages = json_decode($bc_packages, true);

$bc_categories = file_get_contents('https://api.buycraft.net/v4?action=categories&secret=' . $API);
$bc_categories = json_decode($bc_categories, true);

?>