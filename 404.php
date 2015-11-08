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
    <link rel="icon" href="assets/favicon.ico">

    <title>404-Page Not Found</title>
	
	<link href="/assets/css/1.css" rel="stylesheet">
	<?php require('inc/templates/header.php'); ?>
	<?php require('inc/templates/navbar.php'); ?>

  </head>
  <body>
    <br /><br /><br />
    <div class="container">
      <div class="jumbotron">
	<center><img src="http://kootenaycaninelovers.weebly.com/uploads/3/4/3/1/3431427/8458915.jpg?433"/>
	    <h1>Sorry dude,</h1>
		<h4>The page that you're looking doesn't exist. Seeya around though my brutha!</h4>
		<div class="btn-group" role="group" aria-label="...">
		  <a href="#" class="btn btn-primary btn-lg" onclick="window.history.back()">Go back</a>&nbsp
		  <a href="/" class="btn btn-success btn-lg">Homepage</a></center>
	    </div>
	  </div>
	</div>
  </body>
</html>
