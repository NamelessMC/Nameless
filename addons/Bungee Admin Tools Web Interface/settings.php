<?php 
/*
 *	Made by Partydragen
 *  http://partydragen.com/
 *
 *  and Samerton
 *  http://worldscapemc.co.uk
 *
 *  and MuhsinunC
 *  http://muhsinunc.ml/
 *
 *  License: MIT
 */

// Settings for the BAT Web Interface addon

// Ensure user is logged in, and is admin
if($user->isLoggedIn()){
	if($user->canViewACP($user->data()->id)){
		if($user->isAdmLoggedIn()){
			// Can view
		} else {
			Redirect::to('/admin');
			die();
		}
	} else {
		Redirect::to('/');
		die();
	}
} else {
	Redirect::to('/');
	die();
}

// Display information first
?>
<h3>Addon: Bungee Admin Tools Web Interface</h3>
Authors: <a href="http://muhsinunc.ml/">MuhsinunC</a>, <a href="https://github.com/alphartdev/BAT-WebInterface">AlphartDev</a>, <a href="http://partydragen.com/">Partydragen</a>, and <a href="http://worldscapemc.co.uk">Samerton</a><br />
Version: 1.0.0<br />
Description: Adds an online browser to explore Bungee Admin Tools Infractions<br />

<br>

	<div class="panel panel-default margin-fix">
		<div class="panel-heading">
			<h3 class="panel-title">Installation</h3>
		</div>
		<div class="panel-body">
			<h4>Setup</h4>
			<ul>
				<li>Visit <code>yourwebsite.com/batinfractions/__install</code></li>
				<li>Follow the instructions given</li>
				<li>Enjoy! <3 :O :D</li>
			</ul>
		</div>
	</div>

	<div class="panel panel-default margin-fix">
		<div class="panel-heading">
			<h3 class="panel-title">To Bungee Admin Tools Web Interface Theme</h3>
		</div>
		<div class="panel-body">
			<p>It's actually quite simple to change the theme (by default it's set to Yeti)! It was very hard for me to implement the themes into the web interface, but I made it EXTREMELY easy for u! :D <3</p>
			<p>To change the theme, all you have to do is:</p>
			<ul>
				<li>Navigate to <code>"/batinfractions/application/views/_template/"</code> and open <code>"header.php"</code></li>
				<li>Scroll down to the area commented as <code>"CUSTOM THEME HERE!!!"</code></li>
				<li>Change all three lines where it says <code>"Yeti,"</code> since that's the theme I'm currently using, to <strong>ANY</strong> theme that's already installed to your NamelessMC setup! :O :D</li>
				<li>If you're unsure of which themes are installed, navigate to your AdminCP, then on the left you'll see a tab called <code>"Style"</code>. Click it and it'll list all of your currently installed themes! :O :D Enjoy! :D</li>
			</ul>
		</div>
	</div>

	<div class="panel panel-default margin-fix">
		<div class="panel-heading">
			<h3 class="panel-title">To Change Link Location</h3>
		</div>
		<div class="panel-body">
			<p>Unfortunately, I couldn't get the location selector module to work. I tried, believe me. If u know how to get it to work, PLEASE show me how! D:</p>
			<p>Open <code>"/addons/Bungee Admin Tools Web Interface/initialisation.php"</code> and paste in the code snippets below or put none to hide the link (The link is hidden by default & the footer code is commented out as an example for you).</p>
			<ul>
				<li>To place it in the header use this code: <div class="well">$navbar_array[] = array('batinfractions' => $bat_language['bat_icon'] . $bat_language['bat']);</div></li>
				<li>To place it in the header's "more" dropdown menu, use this code: <div class="well">$nav_more_dropdown[$bat_language['bat_icon'] . $bat_language['bat']] = '/batinfractions';</div></li>
				<li>To place it in the footer use this code: <div class="well">$footer_nav_array['batinfractions'] = $bat_language['bat_icon'] . $bat_language['bat'];</div></li>
				<li>To have it not show up at all, just don't put anything. :P ;)</li>
			</ul>
		</div>
	</div>
	
	<div class="panel panel-default margin-fix">
		<div class="panel-heading">
			<h3 class="panel-title">Other Info</h3>
		</div>
		<div class="panel-body">
			<h4>To Do:</h4>
				<ul>
					<li>Add Link Location Selector</li>
					<li>Add MySQL Auto Theme Switcher (Links up with currently selected theme on NamelessMC website)</li>
				</ul>
			<li><a href="https://github.com/alphartdev/BAT-WebInterface">Original GitHub Repository</a></li>
		</div>
	</div>