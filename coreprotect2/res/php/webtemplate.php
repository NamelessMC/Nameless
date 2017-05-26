<?php
// CoLWI v0.9.0
// WebTemplate PHP class
// Copyright (c) 2015-2016 SimonOrJ

// __construct ( array &Configuration[, string Username[, string PageTitle]] )
//   returns nothing.
// head ( void )
//   returns header DOM as string.
// navbar ( void )
//   returns navbar DOM as string.

class WebTemplate {
    private $c, $t;
    
    public function __construct(&$config, $username = false, $title = "CorePortect Lookup Web Interface &bull; by SimonOrJ") {
        $this->c = &$config;
        $this->t = $title;
        $this->u = $username;
    }
    // Head
    public function head() {?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $this->t;?></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css" integrity="sha384-MIwDKRSSImVFAZCVLtU0LMDdON6KVCrZHyVQQj6e8wIEJkW4tvwqXrbMIya1vriY" crossorigin="anonymous">
  <!-- CUSTOM THEMES HERE!!! -->
	<!-- Yeti - DEFAULT -->
		<!-- For Development -->
		<link rel="stylesheet" href="res/themes/Yeti/css/bootstrap.min.css?version=2" title="Yeti">
		<link rel="stylesheet" href="res/themes/Yeti/css/custom.css?version=2" title="Yeti">
		<link rel="stylesheet" href="res/themes/Yeti/css/font-awesome.min.css?version=2" title="Yeti">
		<!-- Live CSS -->
		<link rel="stylesheet" href="/styles/themes/Yeti/css/bootstrap.min.css?version=2" title="Yeti">
		<link rel="stylesheet" href="/styles/themes/Yeti/css/custom.css?version=2" title="Yeti">
		<link rel="stylesheet" href="/styles/themes/Yeti/css/font-awesome.min.css?version=2" title="Yeti">
	<!-- Bootstrap -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Bootstrap/css/bootstrap.min.css?version=2" title="Bootstrap">
		<link rel="alternate stylesheet" href="res/themes/Bootstrap/css/custom.css?version=2" title="Bootstrap">
		<link rel="alternate stylesheet" href="res/themes/Bootstrap/css/font-awesome.min.css?version=2" title="Bootstrap">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Bootstrap/css/bootstrap.min.css?version=2" title="Bootstrap">
		<link rel="alternate stylesheet" href="/styles/themes/Bootstrap/css/custom.css?version=2" title="Bootstrap">
		<link rel="alternate stylesheet" href="/styles/themes/Bootstrap/css/font-awesome.min.css?version=2" title="Bootstrap">
	<!-- Cerulean -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Cerulean/css/bootstrap.min.css?version=2" title="Cerulean">
		<link rel="alternate stylesheet" href="res/themes/Cerulean/css/custom.css?version=2" title="Cerulean">
		<link rel="alternate stylesheet" href="res/themes/Cerulean/css/font-awesome.min.css?version=2" title="Cerulean">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Cerulean/css/bootstrap.min.css?version=2" title="Cerulean">
		<link rel="alternate stylesheet" href="/styles/themes/Cerulean/css/custom.css?version=2" title="Cerulean">
		<link rel="alternate stylesheet" href="/styles/themes/Cerulean/css/font-awesome.min.css?version=2" title="Cerulean">
	<!-- Cosmo -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Cosmo/css/bootstrap.min.css?version=2" title="Cosmo">
		<link rel="alternate stylesheet" href="res/themes/Cosmo/css/custom.css?version=2" title="Cosmo">
		<link rel="alternate stylesheet" href="res/themes/Cosmo/css/font-awesome.min.css?version=2" title="Cosmo">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Cosmo/css/bootstrap.min.css?version=2" title="Cosmo">
		<link rel="alternate stylesheet" href="/styles/themes/Cosmo/css/custom.css?version=2" title="Cosmo">
		<link rel="alternate stylesheet" href="/styles/themes/Cosmo/css/font-awesome.min.css?version=2" title="Cosmo">
	<!-- Cyborg -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Cyborg/css/bootstrap.min.css?version=2" title="Cyborg">
		<link rel="alternate stylesheet" href="res/themes/Cyborg/css/custom.css?version=2" title="Cyborg">
		<link rel="alternate stylesheet" href="res/themes/Cyborg/css/font-awesome.min.css?version=2" title="Cyborg">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Cyborg/css/bootstrap.min.css?version=2" title="Cyborg">
		<link rel="alternate stylesheet" href="/styles/themes/Cyborg/css/custom.css?version=2" title="Cyborg">
		<link rel="alternate stylesheet" href="/styles/themes/Cyborg/css/font-awesome.min.css?version=2" title="Cyborg">
	<!-- Darkly -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Darkly/css/bootstrap.min.css?version=2" title="Darkly">
		<link rel="alternate stylesheet" href="res/themes/Darkly/css/custom.css?version=2" title="Darkly">
		<link rel="alternate stylesheet" href="res/themes/Darkly/css/font-awesome.min.css?version=2" title="Darkly">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Darkly/css/bootstrap.min.css?version=2" title="Darkly">
		<link rel="alternate stylesheet" href="/styles/themes/Darkly/css/custom.css?version=2" title="Darkly">
		<link rel="alternate stylesheet" href="/styles/themes/Darkly/css/font-awesome.min.css?version=2" title="Darkly">
	<!-- Flatly -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Darkly/css/bootstrap.min.css?version=2" title="Darkly">
		<link rel="alternate stylesheet" href="res/themes/Darkly/css/custom.css?version=2" title="Darkly">
		<link rel="alternate stylesheet" href="res/themes/Darkly/css/font-awesome.min.css?version=2" title="Darkly">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Flatly/css/bootstrap.min.css?version=2" title="Flatly">
		<link rel="alternate stylesheet" href="/styles/themes/Flatly/css/custom.css?version=2" title="Flatly">
		<link rel="alternate stylesheet" href="/styles/themes/Flatly/css/font-awesome.min.css?version=2" title="Flatly">
	<!-- Journal -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Journal/css/bootstrap.min.css?version=2" title="Journal">
		<link rel="alternate stylesheet" href="res/themes/Journal/css/custom.css?version=2" title="Journal">
		<link rel="alternate stylesheet" href="res/themes/Journal/css/font-awesome.min.css?version=2" title="Journal">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Journal/css/bootstrap.min.css?version=2" title="Journal">
		<link rel="alternate stylesheet" href="/styles/themes/Journal/css/custom.css?version=2" title="Journal">
		<link rel="alternate stylesheet" href="/styles/themes/Journal/css/font-awesome.min.css?version=2" title="Journal">
	<!-- Lumen -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Lumen/css/bootstrap.min.css?version=2" title="Lumen">
		<link rel="alternate stylesheet" href="res/themes/Lumen/css/custom.css?version=2" title="Lumen">
		<link rel="alternate stylesheet" href="res/themes/Lumen/css/font-awesome.min.css?version=2" title="Lumen">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Lumen/css/bootstrap.min.css?version=2" title="Lumen">
		<link rel="alternate stylesheet" href="/styles/themes/Lumen/css/custom.css?version=2" title="Lumen">
		<link rel="alternate stylesheet" href="/styles/themes/Lumen/css/font-awesome.min.css?version=2" title="Lumen">
	<!-- Paper -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Paper/css/bootstrap.min.css?version=2" title="Paper">
		<link rel="alternate stylesheet" href="res/themes/Paper/css/custom.css?version=2" title="Paper">
		<link rel="alternate stylesheet" href="res/themes/Paper/css/font-awesome.min.css?version=2" title="Paper">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Paper/css/bootstrap.min.css?version=2" title="Paper">
		<link rel="alternate stylesheet" href="/styles/themes/Paper/css/custom.css?version=2" title="Paper">
		<link rel="alternate stylesheet" href="/styles/themes/Paper/css/font-awesome.min.css?version=2" title="Paper">
	<!-- Readable -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Readable/css/bootstrap.min.css?version=2" title="Readable">
		<link rel="alternate stylesheet" href="res/themes/Readable/css/custom.css?version=2" title="Readable">
		<link rel="alternate stylesheet" href="res/themes/Readable/css/font-awesome.min.css?version=2" title="Readable">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Readable/css/bootstrap.min.css?version=2" title="Readable">
		<link rel="alternate stylesheet" href="/styles/themes/Readable/css/custom.css?version=2" title="Readable">
		<link rel="alternate stylesheet" href="/styles/themes/Readable/css/font-awesome.min.css?version=2" title="Readable">
	<!-- Sandstone -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Sandstone/css/bootstrap.min.css?version=2" title="Sandstone">
		<link rel="alternate stylesheet" href="res/themes/Sandstone/css/custom.css?version=2" title="Sandstone">
		<link rel="alternate stylesheet" href="res/themes/Sandstone/css/font-awesome.min.css?version=2" title="Sandstone">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Sandstone/css/bootstrap.min.css?version=2" title="Sandstone">
		<link rel="alternate stylesheet" href="/styles/themes/Sandstone/css/custom.css?version=2" title="Sandstone">
		<link rel="alternate stylesheet" href="/styles/themes/Sandstone/css/font-awesome.min.css?version=2" title="Sandstone">
	<!-- Simplex -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Simplex/css/bootstrap.min.css?version=2" title="Simplex">
		<link rel="alternate stylesheet" href="res/themes/Simplex/css/custom.css?version=2" title="Simplex">
		<link rel="alternate stylesheet" href="res/themes/Simplex/css/font-awesome.min.css?version=2" title="Simplex">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Simplex/css/bootstrap.min.css?version=2" title="Simplex">
		<link rel="alternate stylesheet" href="/styles/themes/Simplex/css/custom.css?version=2" title="Simplex">
		<link rel="alternate stylesheet" href="/styles/themes/Simplex/css/font-awesome.min.css?version=2" title="Simplex">
	<!-- Slate -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Slate/css/bootstrap.min.css?version=2" title="Slate">
		<link rel="alternate stylesheet" href="res/themes/Slate/css/custom.css?version=2" title="Slate">
		<link rel="alternate stylesheet" href="res/themes/Slate/css/font-awesome.min.css?version=2" title="Slate">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Slate/css/bootstrap.min.css?version=2" title="Slate">
		<link rel="alternate stylesheet" href="/styles/themes/Slate/css/custom.css?version=2" title="Slate">
		<link rel="alternate stylesheet" href="/styles/themes/Slate/css/font-awesome.min.css?version=2" title="Slate">
	<!-- Spacelab -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Spacelab/css/bootstrap.min.css?version=2" title="Spacelab">
		<link rel="alternate stylesheet" href="res/themes/Spacelab/css/custom.css?version=2" title="Spacelab">
		<link rel="alternate stylesheet" href="res/themes/Spacelab/css/font-awesome.min.css?version=2" title="Spacelab">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Spacelab/css/bootstrap.min.css?version=2" title="Spacelab">
		<link rel="alternate stylesheet" href="/styles/themes/Spacelab/css/custom.css?version=2" title="Spacelab">
		<link rel="alternate stylesheet" href="/styles/themes/Spacelab/css/font-awesome.min.css?version=2" title="Spacelab">
	<!-- Superhero -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/Superhero/css/bootstrap.min.css?version=2" title="Superhero">
		<link rel="alternate stylesheet" href="res/themes/Superhero/css/custom.css?version=2" title="Superhero">
		<link rel="alternate stylesheet" href="res/themes/Superhero/css/font-awesome.min.css?version=2" title="Superhero">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Superhero/css/bootstrap.min.css?version=2" title="Superhero">
		<link rel="alternate stylesheet" href="/styles/themes/Superhero/css/custom.css?version=2" title="Superhero">
		<link rel="alternate stylesheet" href="/styles/themes/Superhero/css/font-awesome.min.css?version=2" title="Superhero">
	<!-- United -->
		<!-- For Development -->
		<link rel="alternate stylesheet" href="res/themes/United/css/bootstrap.min.css?version=2" title="United">
		<link rel="alternate stylesheet" href="res/themes/United/css/custom.css?version=2" title="United">
		<link rel="alternate stylesheet" href="res/themes/United/css/font-awesome.min.css?version=2" title="United">
		<!-- Live CSS -->
		<link rel="alternate stylesheet" href="/styles/themes/Journal/css/bootstrap.min.css?version=2" title="United">
		<link rel="alternate stylesheet" href="/styles/themes/Journal/css/custom.css?version=2" title="United">
		<link rel="alternate stylesheet" href="/styles/themes/Journal/css/font-awesome.min.css?version=2" title="United">
  <!-- END CUSTOM THEMES -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="res/css/custom.css">
  <link rel="stylesheet" href="res/css/jquery-autocomplete.css">
  <!-- BEGIN Theme Switcher -->
  <script src="res/js/styleswitch.js" type="text/javascript"></script>
  <!-- END Theme Switcher -->
</head>

<?php
    }
    // Navigation Bar
    public function navbar($shownavs = true) {?>
<!-- CUSTOM NAVBAR -->
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main_navbar_collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="./">CP2 Interface</a>
		</div>
		
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="main_navbar_collapse">
			<ul class="nav navbar-nav">
			<?php if ($shownavs) foreach($this->c['navbar'] as $ll => $hf) echo '<li><a href="'.$hf.'">'.$ll.'</a></li>';?>
			</ul>
			<!-- User control (right side of navbar) -->
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown alert-dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span style="margin: -10px 0px; font-size: 16px;"><i class="fa fa-envelope"></i> <div style="display: inline;" id="pms"></div></span></a>
				<ul class="dropdown-menu">
					<li><a href="#">Click View Messages</a></li>
					<li role="separator" class="divider"></li>
					<li><a href="../user/messaging">View Messages</a></li>
				</ul>
				</li>	
				
				<li class="dropdown alert-dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span style="margin: -10px 0px; font-size: 16px;"><i class="fa fa-flag"></i> <div style="display: inline;" id="alerts"></div></span></a>
				<ul class="dropdown-menu">
					<li><a href="#">Click View Alerts</a></li>
					<li role="separator" class="divider"></li>
					<li><a href="../user/alerts">View Alerts</a></li>
				</ul>
				</li>
				
				<li class="dropdown">
				<a href="#" data-toggle="dropdown" role="button" aria-expanded="false">&nbsp;&nbsp;MuhsinunCool <span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu">
					<li><a href="../profile/<?php echo $this->u;?>">Profile</a></li>
					<li class="divider"></li>
					<li><a href="../user">UserCP</a></li>
					<li><a href="../mod">ModCP</a></li>
					<li><a href="../admin">AdminCP</a></li>
					<li class="divider"></li>
					<li><a href="../infractions">Infractions</a></li>
					<li class="divider"></li>
					<li><a href="./login.php<?php if ($this->u) echo "?action=logout"?>"><?php echo $this->u ? "Sign Out" : "Sign In";?></a></li>
				</ul>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container -->
</nav>
<?php
    }
}
?>