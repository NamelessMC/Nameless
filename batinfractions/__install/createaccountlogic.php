<?php
// Initialize Composer autoload
require(__DIR__ . "/../vendor/autoload.php");
chdir("..");

// Create user
$admin_model = new admin_model();
echo $admin_model->createAccount($_POST['user'], $_POST['password']);
$admin_model->toogleSU($_POST['user']);
?>