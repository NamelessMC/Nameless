<?php
// CoLWI v0.9.3
// Purge JSON application
// Copyright (c) 2015-2016 SimonOrJ

// Request parameters:
// server = server name to purge
// all    = to purge all

// Set header
header('Content-type:application/json;charset=utf-8');

// Load config
$c = require "config.php";

// Check login
require "res/php/login.php";
$login = new Login($c);
if (!$login->permission(Login::PERM_PURGE)) {
    // Not enough permission
    $out = array(false, "You have insufficient permission to purge.");
    echo json_encode($out);
    exit();
}

$out = array(true);

if (!empty($_REQUEST['all'])) {
    // purge all
    $sr = new FilesystemIterator("cache/");
    foreach ($sr as $fi) {
        // Check directory
        if ($fi->isDir()) {
            $sr2 = new FilesystemIterator($fi->getPathname());
            
            foreach ($sr2 as $fi2) {
                // Delete files inside directory
                if (!unlink($fi2->getPathname()))
                    $out = array(false, "All of the files didn't want to go away.");
            }
            // Delete directory
            if (!rmdir($fi->getPathname()) && $out[0] !== false)
                $out = array(false, "Directory couldn't be deleted.  Please check permissions.");
        }
    }
} elseif (!empty($_REQUEST['server'])) {
    // purge server
    if (is_dir($path = "cache/".$_REQUEST['server'])) {
        $sr = new FilesystemIterator($path);
        foreach ($sr as $fi) {
            // Delete files inside directory
            if (!unlink($fi->getPathname()))
                $out = array(false, "All of the files didn't want to go away.");
        }
        if (!rmdir($path) && $out[0] !== false)
            $out = array(false, "Directory couldn't be deleted.  Please check permissions.");
    } else {
        // Server directory does not exist.
        $out= array(false, "Server cache does not exist.");
    }
    
} else {
    // Nothing happened...
    $out = array(false, "Nothing happened.");
}

echo json_encode($out);
?>