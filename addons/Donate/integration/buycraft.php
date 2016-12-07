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
curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Buycraft-Secret: ' . $API));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');

// Payments
curl_setopt($ch, CURLOPT_URL, 'https://plugin.buycraft.net/payments');
$bc_payments = curl_exec($ch);
$bc_payments = json_decode($bc_payments, true);
 
// Packages and categories
curl_setopt($ch, CURLOPT_URL, 'https://plugin.buycraft.net/listing');
$bc_categories = curl_exec($ch);
$bc_categories = json_decode($bc_categories, true);

curl_close($ch);