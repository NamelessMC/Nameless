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

// Settings for the CP2 Web Interface addon

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
<h3>Addon: Core Protect 2 Web Interface</h3>
Authors: <a href="http://muhsinunc.ml/">MuhsinunC</a>, <a href="https://simonorj.com/">SimonOrJ</a>, <a href="http://partydragen.com/">Partydragen</a>, and <a href="http://worldscapemc.co.uk">Samerton</a><br />
Version: 1.2.2<br />
Description: Adds an online browser to explore Core Protect 2 logs<br />

<br>

	<div class="panel panel-default margin-fix">
		<div class="panel-heading">
			<h3 class="panel-title">Installation</h3>
		</div>
		<div class="panel-body">
			<h4>Prerequisites</h4>
			<ul>
			<li>Webserver running PHP 5.3.0 or above (This webserver is running PHP <?php echo phpversion();?>.)</li>
			<li>
				A MySQL or SQLite database used by a Minecraft server running CoreProtect 2.11 or above.
					<ul>
						<li>If using SQLite in real-time, you're advised to have the webserver run on the same machine as the Minecraft server.</li>
					</ul>
			</li>
			</ul>
			<h4>Setup</h4>
			<ul>
				<li>Extract the folder titled <code>"coreprotect2"</code> into your NamelessMC website root.</li>
			</ul>
			<h4>Write Permissions</h4>
			<ul>
				<li>The webserver should have write permission to the <code>cache/</code> folder in order for this web application to work efficiently. Do this by running: <code>chmod 777 cache</code> or by editing permissions through an FTP program such as WinSCP.</li>
				<li>If you want to be able to make configuration changes from web UI (via <code>web/setup.php</code>): <code>chmod 777 config.php config.json server</code> or by editing permissions through an FTP program such as WinSCP.</li>
				<li>(If you're an advanced user, you can just find a way for the webserver to have write access to the files, or make configuration directly from those files.)</li>
			</ul>
			<h4>Configuration</h4>
			<ul>
				<li>You <strong>must</strong> edit <code>config.php</code> and make account changes before you can do anything else. Follow the instructions in the file. If you decided to make all configuration manually (by editing the configuration files), then configure the rest of the file and <code>config.json</code>.</li>
				<li>If you are an advanced user and want to set up server information manually, you should do so now using the <code>server/sample.php</code> and <code>server/sample.json</code> files. The two files may be copied or renamed to better suit your needs.</li>
			</ul>
		</div>
	</div>

	<div class="panel panel-default margin-fix">
		<div class="panel-heading">
			<h3 class="panel-title">To Change Core Protect 2 Web Interface Theme</h3>
		</div>
		<div class="panel-body">
			<p>It's actually quite simple to change the theme (by default it's set to Yeti)! It was very hard for me to implement the themes into the web interface, but I made it EXTREMELY easy for u! :D <3</p>
			<p>To change the theme, all you have to do is:</p>
			<ul>
				<li>Go to the web interface and click the button in the navigation bar labeled as <code>"Themes"</code></li>
				<li>Select any theme you wish! <3 :O :D (Scroll down and read the To-Do list! :O :D)</li>
			</ul>
		</div>
	</div>

	<div class="panel panel-default margin-fix">
		<div class="panel-heading">
			<h3 class="panel-title">To Change Link Location</h3>
		</div>
		<div class="panel-body">
			<p>Unfortunately, I couldn't get the location selector module to work. I tried, believe me. If u know how to get it to work, PLEASE show me how! D:</p>
			<p>Open <code>"/addons/CoreProtect2 Web Interface/initialisation.php"</code> and paste in the code snippets below or put none to hide the link (The link is hidden by default & the footer code is commented out as an example for you).</p>
			<ul>
				<li>To place it in the header use this code: <div class="well">$navbar_array[] = array('coreprotect2' => $cp2_language['cp2_icon'] . $cp2_language['cp2']);</div></li>
				<li>To place it in the header's "more" dropdown menu, use this code: <div class="well">$nav_more_dropdown[$cp2_language['cp2_icon'] . $cp2_language['cp2']] = '/coreprotect2';</div></li>
				<li>To place it in the footer use this code: <div class="well">$footer_nav_array['coreprotect2'] = $cp2_language['cp2_icon'] . $cp2_language['cp2'];</div></li>
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
					<li>Add Theme Switcher <span class="label label-success">DONE</span></li>
					<li>Add Link Location Selector</li>
					<li>Add MySQL Auto Theme Switcher (Links up with currently selected theme on NamelessMC website)</li>
				</ul>
			<li><a href="https://github.com/SimonOrJ/CoreProtect-Lookup-Web-Interface">Original GitHub Repository</a></li>
		</div>
	</div>