<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Rewrite test
 */
?>
<html lang="en">

<head>
    <title>Rewrite Test &bull; NamelessMC</title>

    <!-- Global CSS -->
    <link rel="stylesheet" href="core/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="core/assets/css/custom.css">
    <link rel="stylesheet" href="core/assets/css/font-awesome.min.css">

    <style>
    html {
        overflow-y: scroll;
    }
    body {
        background-color: #eceeef;
    }
    </style>
</head>

<body>
    <div style="text-align: center">
        <br /><br /><br />

        <h1>NamelessMC v2 <sup><span style="font-size: small;">pre-release</span></sup></h1>

        <hr />

        <div class="row">
            <div class="col-md-6 offset-md-3">
            <?php
            if (isset($_GET['route']) && $_GET['route'] == '/rewrite_test') {
                echo '<div class="alert alert-success">Rewrite enabled!</div>';

            } else {
                echo '<div class="alert alert-danger">Rewrite disabled!</div>';
            }
            ?>
            </div>
        </div>
    </div>
</body>
</html>
