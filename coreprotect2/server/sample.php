<?php return array (
// If you want to configure server databases on your own, you can duplicate
//   this file and write some configuration.

// If you set things up on the web UI and want to input passwords manually,
//   you will have to visit this directory and configure the password.

  'db' =>
  array (
    'type' => 'sqlite',         // Type of database: mysql or sqlite
    'host' => '127.0.0.1',      // mysql; server IP/hostname
    'user' => 'username',       // mysql; username
    'pass' => 'password',       // mysql; password
    'data' => 'coreprotect',    // mysql; Database
    'path' => '../database.db', // sqlite; Relative to webroot
  ),
  'co'     => 'co_',            // Prefix.
  'legacy' => false,            // Did this start logging below CP v2.11?
  // For dynmap configuration, visit the .json file by the same name.
  //  dynmap:
  //    URL:   Link to the Dynmap page
  //    zoom:   Deafult Zoom level
  //    map:    Deafult map type. Common: flat, surface, or cave
)?>
