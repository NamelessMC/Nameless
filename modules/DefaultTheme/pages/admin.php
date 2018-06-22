<?php
/*
 *	Made by Samerton
 *  https://github.com/samerton
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Default themes for NamelessMC
 */

// Can the user view the AdminCP?
if($user->isLoggedIn()){
	if(!$user->canViewACP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	} else {
		// Check the user has re-authenticated
		if(!$user->isAdmLoggedIn()){
			// They haven't, do so now
			Redirect::to(URL::build('/admin/auth'));
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}
 
 
$page = 'admin';
$admin_page = 'defaulttheme';

if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$cache->setCache('default_template');
		
		if(isset($_POST['theme'])){
			$cache->store('bootswatch', $_POST['theme']);
		}
		
		if(isset($_POST['navbarType']) && ($_POST['navbarType'] == 'dark' || $_POST['navbarType'] == 'light')){
			$cache->store('nav_style', $_POST['navbarType']);
		}

		if(isset($_POST['navbarColour'])){
			$cache->store('nav_bg', $_POST['navbarColour']);
		}

		Redirect::to(URL::build('/admin/defaulttheme'));
		die();
		
	} else 
		$error = $language->get('admin', 'invalid_token');
}

?>
<!DOCTYPE html>
<html lang="<?php echo(defined('HTML_LANG') ? HTML_LANG : 'en'); ?>" <?php if(defined('HTML_RTL') && HTML_RTL === true) echo ' dir="rtl"'; ?>>
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	
	<?php 
	$title = $language->get('admin', 'admin_cp');
	require(ROOT_PATH . '/core/templates/admin_header.php');
	?>
  
  </head>
  <body>
    <?php require(ROOT_PATH . '/modules/Core/pages/admin/navbar.php'); ?>
	<div class="container">
	  <div class="row">
	    <div class="col-md-3">
		  <?php require(ROOT_PATH . '/modules/Core/pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <div class="card">
		    <div class="card-block">
			<h3><?php echo $default_theme_language->get('language', 'default_theme_title'); ?></h3>
			<hr />
			<?php
			$cache->setCache('default_template');
			if($cache->isCached('bootswatch')){
				$selected_theme = $cache->retrieve('bootswatch');
			} else {
				$selected_theme = 'bootstrap';
				$cache->store('bootswatch', 'bootstrap');
			}

			if($cache->isCached('nav_style')){
				$nav_style = $cache->retrieve('nav_style');
			} else {
				$nav_style = 'light';
				$cache->store('nav_style', 'light');
			}

			if($cache->isCached('nav_bg')){
				$nav_bg = $cache->retrieve('nav_bg');
			} else {
				$nav_bg = 'light';
				$cache->store('nav_bg', 'light');
			}
			?>
			<form action="" method="post">
			  <div class="form-group">
			    <label for="inputTheme"><?php echo $default_theme_language->get('language', 'theme'); ?></label>
				<select name="theme" class="form-control" id="inputTheme">
				  <option value="bootstrap"<?php if($selected_theme == 'default') echo ' selected'; ?>>Default</option>
				  <option value="cerulean"<?php if($selected_theme == 'cerulean') echo ' selected'; ?>>Cerulean</option>
				  <option value="cosmo"<?php if($selected_theme == 'cosmo') echo ' selected'; ?>>Cosmo</option>
				  <option value="cyborg"<?php if($selected_theme == 'cyborg') echo ' selected'; ?>>Cyborg</option>
				  <option value="darkly"<?php if($selected_theme == 'darkly') echo ' selected'; ?>>Darkly</option>
				  <option value="flatly"<?php if($selected_theme == 'flatly') echo ' selected'; ?>>Flatly</option>
				  <option value="journal"<?php if($selected_theme == 'journal') echo ' selected'; ?>>Journal</option>
				  <option value="litera"<?php if($selected_theme == 'litera') echo ' selected'; ?>>Litera</option>
				  <option value="lumen"<?php if($selected_theme == 'lumen') echo ' selected'; ?>>Lumen</option>
				  <option value="lux"<?php if($selected_theme == 'lux') echo ' selected'; ?>>Lux</option>
				  <option value="materia"<?php if($selected_theme == 'materia') echo ' selected'; ?>>Materia</option>
				  <option value="minty"<?php if($selected_theme == 'minty') echo ' selected'; ?>>Minty</option>
				  <option value="pulse"<?php if($selected_theme == 'pulse') echo ' selected'; ?>>Pulse</option>
				  <option value="sandstone"<?php if($selected_theme == 'sandstone') echo ' selected'; ?>>Sandstone</option>
				  <option value="simplex"<?php if($selected_theme == 'simplex') echo ' selected'; ?>>Simplex</option>
				  <option value="sketchy"<?php if($selected_theme == 'sketchy') echo ' selected'; ?>>Sketchy</option>
				  <option value="slate"<?php if($selected_theme == 'slate') echo ' selected'; ?>>Slate</option>
				  <option value="solar"<?php if($selected_theme == 'slate') echo ' selected'; ?>>Solar</option>
				  <option value="spacelab"<?php if($selected_theme == 'spacelab') echo ' selected'; ?>>Spacelab</option>
				  <option value="superhero"<?php if($selected_theme == 'superhero') echo ' selected'; ?>>Superhero</option>
				  <option value="united"<?php if($selected_theme == 'united') echo ' selected'; ?>>United</option>
				  <option value="yeti"<?php if($selected_theme == 'yeti') echo ' selected'; ?>>Yeti</option>
				</select>
			  </div>
			  <div class="form-group">
			    <label for="inputNavbarType"><?php echo $default_theme_language->get('language', 'navbar_style'); ?></label>
				<select name="navbarType" class="form-control" id="inputNavbarType">
				  <option value="light"<?php if($nav_style == 'light') echo ' selected'; ?>>Light</option>
				  <option value="dark"<?php if($nav_style == 'dark') echo ' selected'; ?>>Dark</option>
				</select>
			  </div>
			  <div class="form-group">
			    <label for="inputNavbarColour"><?php echo $default_theme_language->get('language', 'navbar_colour'); ?></label>
				<select name="navbarColour" class="form-control" id="inputNavbarColour">
				  <option value="light"<?php if($nav_bg == 'light') echo ' selected'; ?>>Light</option>
				  <option value="primary"<?php if($nav_bg == 'primary') echo ' selected'; ?>>Primary</option>
				  <option value="secondary"<?php if($nav_bg == 'secondary') echo ' selected'; ?>>Secondary</option>
				  <option value="success"<?php if($nav_bg == 'success') echo ' selected'; ?>>Success</option>
				  <option value="danger"<?php if($nav_bg == 'danger') echo ' selected'; ?>>Danger</option>
				  <option value="warning"<?php if($nav_bg == 'warning') echo ' selected'; ?>>Warning</option>
				  <option value="info"<?php if($nav_bg == 'info') echo ' selected'; ?>>Info</option>
				  <option value="dark"<?php if($nav_bg == 'dark') echo ' selected'; ?>>Dark</option>
				</select>
			  </div>
			  <div class="form-group">
			    <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
				<input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
			  </div>
			</form>
		    </div>
		  </div>
		</div>
	  </div>
    </div>
	
	<?php require(ROOT_PATH . '/modules/Core/pages/admin/footer.php'); ?>

    <?php require(ROOT_PATH . '/modules/Core/pages/admin/scripts.php'); ?>
	
  </body>
</html>