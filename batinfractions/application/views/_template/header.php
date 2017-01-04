<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo Message::network;?> Infractions</title>
	<!-- Bootstrap and jquery includes -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="//code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<!-- Custom fonts -->
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Bree%20Serif">
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Alegreya">
	<!-- Additional CSS and JS -->
	<link rel="stylesheet" href="public/styles/base-stylesheet.css">
	<script src="public/js/base-script.js"></script>
	<!-- CUSTOM THEMES HERE!!! -->
		<!-- For Development -->
		<link rel="stylesheet" href="res/themes/Yeti/css/bootstrap.min.css?version=2" title="Yeti">
		<link rel="stylesheet" href="res/themes/Yeti/css/custom.css?version=2" title="Yeti">
		<link rel="stylesheet" href="res/themes/Yeti/css/font-awesome.min.css?version=2" title="Yeti">
		<!-- Live CSS -->
		<link rel="stylesheet" href="/styles/themes/Yeti/css/bootstrap.min.css?version=2" title="Yeti">
		<link rel="stylesheet" href="/styles/themes/Yeti/css/custom.css?version=2" title="Yeti">
		<link rel="stylesheet" href="/styles/themes/Yeti/css/font-awesome.min.css?version=2" title="Yeti">
	<!-- END CUSTOM THEMES -->
	<link rel="stylesheet" href="public/styles/custom.css">
</head>
<body>
	<nav class="navbar" role="navigation">
		<div class="container-fluid">
			<!-- BEGIN Navbar -->
				<nav class="navbar navbar-default navbar-fixed-top">
					<div class="container" id="bs-example-navbar-collapse-1">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main_navbar_collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" href="./">BAT Interface</a>
						</div>
						
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse" id="main_navbar_collapse">
							<ul class="nav navbar-nav">
								<li class="<?php if(get_class($this) == "home"){echo "active";}?>"><a href="index.php">Home</a></li>
								<li class="<?php if(get_class($this) == "ban"){echo "active";}?>"><a href="index.php?p=ban">Bans</a></li>
								<li class="<?php if(get_class($this) == "mute"){echo "active";}?>"><a href="index.php?p=mute">Mutes</a></li>
								<li class="<?php if(get_class($this) == "kick"){echo "active";}?>"><a href="index.php?p=kick">Kicks</a></li>
								<li class="<?php if(get_class($this) == "comment"){echo "active";}?>"><a href="index.php?p=comment">Comments</a></li>
							</ul>
							<!-- User control (right side of navbar) -->
							<ul class="nav navbar-nav navbar-right">
								<?php if($this->isAdmin()) {include("admin/navbarAddon.php");} else { echo "<li><a href='?p=admin'>Login</a></li>";}?>
							</ul>
						</div><!-- /.navbar-collapse -->
					</div><!-- /.container -->
				</nav>
			<!-- END Navbar -->
		</div>
	</nav>
<?php include("application/views/_template/modal-info.php");?>
<div class="row">
<div class="col-md-1"></div>
<div class="col-md-10">