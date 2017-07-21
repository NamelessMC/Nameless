<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

$API = $queries->getWhere('donation_settings', array("name", "=", "api_key"));
$API = $API[0]->value;
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_URL, 'http://api.craftingstore.net/v2/' . $API . '/packages');
$cs_packages = curl_exec($ch);

$cs_packages = json_decode(str_replace("&quot;", "\"", strip_tags($cs_packages)), true);
 
// Payments
curl_setopt($ch, CURLOPT_URL, 'http://api.craftingstore.net/v2/' . $API . '/payments');

$cs_donors = curl_exec($ch);

$cs_donors = json_decode(str_replace("&quot;", "\"", strip_tags($cs_donors)), true);

curl_close($ch);