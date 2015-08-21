<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/core/assets/favicon.ico">

    <title>404</title>
	
	<link href="/core/assets/css/bootstrap.min.css" rel="stylesheet">

  </head>
  <body>
    <br /><br /><br />
    <div class="container">
      <div class="jumbotron">
	    <h1>404</h1>
		<h4>Sorry, we couldn't find that page.</h4>
		<div class="btn-group" role="group" aria-label="...">
		  <a href="#" class="btn btn-primary btn-lg" onclick="window.history.back()">Go back</a>
		  <a href="/" class="btn btn-success btn-lg">Homepage</a>
	    </div>
	  </div>
	</div>
  </body>
</html>