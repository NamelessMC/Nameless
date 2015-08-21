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

curl_setopt($ch, CURLOPT_URL, 'http://www.minecraftmarket.com/api/1.5/' . $API . '/gui');
$mm_gui = curl_exec($ch);
$mm_gui = json_decode(str_replace("&quot;", "\"", strip_tags($mm_gui)), true);
 
// Packages and categories

curl_setopt($ch, CURLOPT_URL, 'http://www.minecraftmarket.com/api/1.5/' . $API . '/recentdonor');

$mm_donors = curl_exec($ch);
$mm_donors = json_decode(str_replace("&quot;", "\"", strip_tags($mm_donors)), true);

curl_close($ch);

?>