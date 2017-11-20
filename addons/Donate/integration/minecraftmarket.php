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

// Categories
curl_setopt($ch, CURLOPT_URL, 'https://www.minecraftmarket.com/api/v1/plugin/' . $API . '/categories/?format=json');
$mm_gui = curl_exec($ch);
$mm_gui = json_decode(str_replace("&quot;", "\"", strip_tags($mm_gui)), true);

// Packages
curl_setopt($ch, CURLOPT_URL, 'https://www.minecraftmarket.com/api/v1/plugin/' . $API . '/items/?format=json');
$mm_packages = curl_exec($ch);
$mm_packages = json_decode(str_replace("&quot;", "\"", strip_tags($mm_packages)), true);
 
// Latest purchases
curl_setopt($ch, CURLOPT_URL, 'https://www.minecraftmarket.com/api/v1/plugin/' . $API . '/purchases/?format=json');
$mm_donors = curl_exec($ch);
$mm_donors = json_decode(str_replace("&quot;", "\"", strip_tags($mm_donors)), true);

curl_close($ch);