<?php
$GLOBALS['config'] = array(
    "mysql" => array(
        "host" => "sql7.freemysqlhosting.net", // Web server database IP (Likely to be 127.0.0.1)
        "username" => "sql7149948", // Web server database username
        "password" => '5Kp9mJiXeK', // Web server database password
        "db" => "sql7149948", // Web server database name
        "port" => "3306", // Web server database port
        "prefix" => "nl2_" // Web server table prefix
    ),
    "remember" => array(
        "cookie_name" => "nlmc", // Name for website cookies
        "cookie_expiry" => 604800
    ),
    "session" => array(
        "session_name" => "user",
        "admin_name" => "admin",
        "token_name" => "token"
    ),
    "core" => array(
        "path" => "",
        "friendly" => true
    )
);