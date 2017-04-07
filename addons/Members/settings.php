<?php 
/*
 *	Made by Partydragen
 *  http://partydragen.com/
 *
 *  and Samerton
 *  https://worldscapemc.com
 *
 *  License: MIT
 */

// Settings for the Members addon

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

if(Input::exists()){
	if(Token::check(Input::get('token'))){
		// Link location
		$c->setCache('membersaddon');
		$c->store('linklocation', htmlspecialchars(Input::get('linkposition')));
	} else {
		// Invalid token
		$error = $admin_language['invalid_token'];
	}
}

// Display information first
?>
<h3>Addon: Members List</h3>
Authors: Partydragen and Samerton<br />
Version: 1.2.2<br />
Description: Adds a page where users can check all registered members along with staff groups<br />

<hr />

<form action="" method="post">
  <?php if(isset($error)){ ?>
  <div class="alert alert-danger">
    <?php echo $error; ?>
  </div>
  <?php } ?>
  <div class="form-group">
	<label for="InputLinkPosition"><?php echo $admin_language['page_link_location']; ?></label>
	<?php
	// Get position of link
	$c->setCache('membersaddon');
	if($c->isCached('linklocation')){
		$link_location = $c->retrieve('linklocation');
	} else {
		$c->store('linklocation', 'navbar');
		$link_location = 'navbar';
	}
	?>
	<select name="linkposition" id="InputLinkPosition" class="form-control">
	  <option value="navbar" <?php if($link_location == 'navbar'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_navbar']; ?></option>
	  <option value="more" <?php if($link_location == 'more'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_more']; ?></option>
	  <option value="footer" <?php if($link_location == 'footer'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_footer']; ?></option>
	  <option value="none" <?php if($link_location == 'none'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_none']; ?></option>
	</select>
  </div>
  
  <div class="form-group">
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	<input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
  </div>
</form>