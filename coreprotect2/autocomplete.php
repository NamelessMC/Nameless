<?php
// CoLWI v0.9.3
// AutoComplete JSON application
// Copyright (c) 2015-2016 SimonOrJ

// Request parameters:
// s = server
// a = source
// b = part of the name to look up
// e = exclude
// l = length

// Testing script
//error_reporting(-1);ini_set('display_errors', 'On');

// Set header
header('Content-type:application/json;charset=utf-8');

// Get config for login check
$c = require "config.php";

// Login check
require "res/php/login.php";
$login = new Login($c);
if (!$login->permission(Login::PERM_LOOKUP)) {
    echo '["Login is required."]';
    exit();
}

// Check for required variables
if ($se = empty($_REQUEST['s']) || empty($_REQUEST['a']) || empty($_REQUEST['l'])) {
    echo '["'
        .($se ? "Server" : "Source or length")
        .' is not defined"]';
    exit();
}

// Load server
$s = include "server/".$_REQUEST['s'].".php";

// Check if file/cache exists or request parameter 'b' is set.
if (!file_exists($file = "cache"."/".$_REQUEST['s']."/".$_REQUEST['a'].".php") || empty($_REQUEST['b'])) {
    echo "[]";
    exit();
}

// Get data source
$data = include $file;

// Material name validation
if ($_REQUEST["a"] === "material") {
    // Remove non-colon words
    if ($s['legacy']) {
        $data = array_filter($data, function($v) {
            return (strpos($v,":") !== false);
        });
    }
    
    // Conversion
    if ($c['flag']['bukkitToMc']) {
        require "bukkittominecraft.php";
        $Cc = new BukkitToMinecraft();
        foreach($data as $k => $v) $data[$k] = $Cc->getMc($v);
    }
}

// Keyword Filter
$data = array_filter($data, function($v) {
    return(stripos($v,$_REQUEST["b"]) !== false);
});

// Remove already listed words
if (!empty($_REQUEST['e']))
    foreach($_REQUEST['e'] as $value)
        if(($key = array_search($value,$data)) !== false)
            unset($data[$key]);

// Sort
usort($data, function ($a,$b) {
    return levenshtein($a,$_REQUEST["b"]) > levenshtein($b,$_REQUEST["b"]) ? 1 : -1;
});

echo json_encode(array_slice($data,0,$_REQUEST['l']));
?>