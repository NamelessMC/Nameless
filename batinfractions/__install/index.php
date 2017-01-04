<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>BungeeAdminTools WebInterface Installation</title>
<!-- Bootstrap includes -->
<link rel="stylesheet"
	href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
</head>
<body style="background: #A7DBD8;">
	<nav class="navbar" role="navigation" style="background: #FA6900; color: #E0E4CC;">
		<div class="container-fluid">
			<div class="collapse navbar-collapse"
				id="bs-example-navbar-collapse-1">
				<center><h1>
					BungeeAdminTools WebInterface - Installation
				</h1></center>
			</div>
		</div>
	</nav>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<center>
			<div class="jumbotron">
			<p>Hi, welcome to the setup of the BAT WebInterface!</p>
			<br>
			<?php 
			require("../application/config/config.inc.php");
			if(empty($host) || empty($database) || empty($user)){
				echo "<p class='text-default'>To start, please fill out the configuration file (located in application/config/config.inc.php)</p>";
				return;
			}
			try{
				$database = new PDO('mysql:host='.$host.';dbname='.$database, $user, $password);
				$database->query("CREATE TABLE IF NOT EXISTS `BAT_web` (
									  `id` int(11) NOT NULL AUTO_INCREMENT,
									  `user` varchar(32) NOT NULL,
									  `password` char(128) NOT NULL,
									  `salt` char(16) NOT NULL,
									  `superuser` tinyint(1) NOT NULL DEFAULT '0',
									  PRIMARY KEY (`id`),
									  UNIQUE KEY `user` (`user`)
									) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");
			?>
				<p class='text-success'>The connection to your MySQL server is working !</p>
			<?php 
				$query = $database->query("SELECT * FROM BAT_web;");
				if($query->rowCount() > 0){?>
					<p class='text-success'>The installation is almost over! You must now delete the '_install' folder 
						and then go to the directory of the installation (<a href="../index.php">index.php</a>).</a></p>
				<?php }else{?>
				<p class='text-default'>You must now create your admin account <a href="createaccount.php">there</a></p>
			<?php
			}}catch(Exception $e)
			{
				echo "<p class='text-danger'>An error occured while connecting to the database to the database.</p> 
					Please check your database login information : ".$e->getMessage();
			}
			?>
			</div>
			</center>
		</div>
		<div class="col-md-2"></div>
	</div>
</body>
</html>
