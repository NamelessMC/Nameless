<?php
/**
 * BungeeAdminTools - WebInterface
 * Author : Alphart
 * Version 1.5
 * License : GPL V3 -> https://github.com/alphartdev/BungeeAdminTools/blob/master/LICENSE
 */
if(is_dir("__install")){
	echo "Once you have installed the WebInterface, you must delete the install folder in to use the interface.";
	exit;
}

// Load Composer autoload
require_once("vendor/autoload.php");

// Loading config.inc.php with composer doesn't seem to keep variable in the right scope
require("application/config/config.inc.php");
if($debugMode){
	echo "<h3><center><span class ='glyphicon glyphicon-warning-sign'></span>
			The debug mode is enabled ! Disable it from the configuration file.
		<span class ='glyphicon glyphicon-warning-sign'></span></center></h3>";
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
}
if($increaseMemoryLimit){
	ini_set('memory_limit', '16M');
}

// Use the given timezone if there is one
$isTimezoneSet = true;
if($timezone != "default"){
	if(!date_default_timezone_set($timezone)){
		$isTimezoneSet = false;
	}
}
// If the timezone isn't set by the php.ini, we set it to UTC
try{
    date_default_timezone_get();
}
catch(Exception $e){
	$isTimezoneSet = false;
    date_default_timezone_set('UTC');
}
if(!$isTimezoneSet){
	echo "<h3><center><span class ='glyphicon glyphicon-warning-sign'></span>
			The specified timezone is not valid. Please change it in the configuration (timezone settings).
		<span class ='glyphicon glyphicon-warning-sign'></span></center></h3>";
}

session_start();

$router = new Router($_GET);
$controller = $router->getController();

$controller->executeAction();

if($debugMode){
	echo "<h3><center>Peak memory usage:" . floor((memory_get_peak_usage() / 1024)) . "Kb</center></h3>";
}
