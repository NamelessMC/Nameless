<?php
// CoLWI v0.9.3
// Index page
// Copyright (c) 2015-2016 SimonOrJ

// Testing script
//error_reporting(-1);ini_set('display_errors', 'On');

// Get the configuration variable.
$c = require "config.php";

// Check login status.
require "res/php/login.php";
$login = new Login($c);
if (!$login->permission(Login::PERM_LOOKUP)) {
    header("Location: login.php?landing=.%2F");
    exit();
}

/* Psuedocode:
if not logged in
    redirect to 
    exit
*/

// Get the template file and initiate its class.
require "res/php/webtemplate.php";
$template = new WebTemplate($c, $login->getUsername());
?>
<!DOCTYPE html>
<html>
  <?php 
  // Get the head from template.

  $template->head();
  ?>
  <body data-spy="scroll" data-target="#row-pages">

    <?php
    $template->navbar();
    ?>

	<!-- BEGIN Editable Content -->
		
		<style>
		.margin-fix {
		margin-left: 12rem;
		margin-right: 12rem;
		}
		</style>
		
		<div class="panel panel-default margin-fix">
			<div class="panel-heading">
				<h3 class="panel-title">Info</h3>
			</div>
			<div class="panel-body">
			<p>
				To switch the theme, press any button below! :O :D
			</p>
			</div>
		</div>
		
		<div class="panel panel-default margin-fix">
			<div class="panel-heading">
				<h3 class="panel-title">Themes</h3>
			</div>
			<div class="panel-body">
				
				<?php
					$cookie_name = "mysheet";
				?>
				
				<div class="well">
					<div class="row">
						<div>
							<div class="col-md-6">
								<strong>Yeti</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Yeti', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Yeti'){echo "success";} else {echo "primary";} ?>" data-toggle="tooltip" data-placement="top" data-original-title="This one is the default one and is setup to work PERFECTLY :O :D" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Yeti'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Yeti'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Bootstrap</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Bootstrap', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Bootstrap'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Bootstrap'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Bootstrap'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Cerulean</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Cerulean', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Cerulean'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Cerulean'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Cerulean'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Cosmo</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Cosmo', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Cosmo'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Cosmo'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Cosmo'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Cyborg</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Cyborg', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Cyborg'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Cyborg'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Cyborg'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Darkly</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Darkly', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Darkly'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Darkly'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Darkly'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Flatly</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Flatly', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Flatly'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Flatly'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Flatly'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Journal</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Journal', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Journal'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Journal'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Journal'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Lumen</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Lumen', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Lumen'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Lumen'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Lumen'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Paper</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Paper', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Paper'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Paper'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Paper'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Readable</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Readable', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Readable'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Readable'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Readable'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Sandstone</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Sandstone', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Sandstone'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Sandstone'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Sandstone'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Simplex</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Simplex', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Simplex'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Simplex'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Simplex'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Slate</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Slate', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Slate'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Slate'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Slate'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Spacelab</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Spacelab', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Spacelab'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Spacelab'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Spacelab'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>Superhero</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('Superhero', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Superhero'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Superhero'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'Superhero'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
						<div>
							<div class="col-md-6">
								<strong>United</strong>
							</div>
							<div class="col-md-6">
								<span class="pull-right">
									<a href="javascript:chooseStyle('United', 60)" style="width: 90px;" class="btn btn-<?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'United'){echo "success";} else {echo "primary";} ?>" <?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'United'){echo "disabled";} ?> onclick="location.reload();"><?php if(isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'United'){echo "Active";} else {echo "Activate";} ?></a>
								</span>
							</div>
							<hr>
							<hr>
							<hr>
						</div>
					</div>
				</div>
			</div>
		</div>
	
	<!-- END Editable Content -->
	
    <!-- All the scripting needs -->
    <?php
    // Unset sensetitive information before sending it to the JS.
    unset($c['login']);
    unset($c['user']);
    ?>
    <script>
    // Quick Styling for JS-enabled browser
    
    // Corner/Radius Reset
    document.getElementById("lCorner1").innerHTML = "Center";
    document.getElementById("lCorner2").innerHTML = "Radius";
    document.getElementById("lC2").className = "";
    a = document.getElementsByClassName("lRadiusHide");
    for (var i = 0; i < a.length; i++) a[i].style.display = "none";
    document.getElementById("lCX2").setAttribute("placeholder","Radius");
    
    // Add data-toggle attribute to checkboxes (and radio buttons) with dtButtons class
    a = document.getElementsByClassName("dtButtons");
    for (var i = 0; i < a.length; i++) a[i].setAttribute("data-toggle","buttons");
    document.getElementById("lT").setAttribute("placeholder","")
    document.getElementById("lT").setAttribute("type","text");
    document.getElementById("lT").removeAttribute("name");
    
    document.getElementById("lSubmit").disabled = true;
    document.getElementById("mSubmit").disabled = true;
    </script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js">// JQuery</script>
    <script src="res/js/buttons.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js">// Dropdown</script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/tether/1.1.1/js/tether.min.js">// Bootstrap dependency</script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js" integrity="sha384-ux8v3A6CPtOTqOzMKiuo3d/DomGaaClxFYdCu2HPMBEkf6x2xiDyJ7gkXU0MWwaD" crossorigin="anonymous">// Bootstrap (Alpha!)</script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment-with-locales.min.js">// datetime-picker dependency</script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js">// Datetime Picker</script>
    <script src="res/js/lookup.js"></script>
  </body>
</html>
