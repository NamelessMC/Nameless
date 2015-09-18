<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

if(!$user->isLoggedIn()){
	Redirect::to('/');
	die();
}

// page for UserCP sidebar
$user_page = 'settings';

require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTMLPurifier

// Are custom usernames enabled?
$custom_usernames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
$custom_usernames = $custom_usernames[0]->value;

if(Input::exists()){
	if(Token::check(Input::get('token'))) {
		$validate = new Validate();
		
		$validate_array = array(
			'signature' => array(
				'max' => 256
			)
		);
		
		if($custom_usernames == 'true'){
			$validate_array['screenname'] = array(
				'required' => true,
				'min' => 2,
				'max' => 20
			);
		}
		
		$validation = $validate->check($_POST, $validate_array);
		
		if($validation->passed()){
			if($custom_usernames == 'true'){
				$username = Input::get('screenname');
			} else {
				$username = $user->data()->mcname;
			}
			
			// update database value
			try {
				$queries->update('users', $user->data()->id, array(
					'username' => htmlspecialchars($username),
					'signature' => htmlspecialchars(Input::get('signature'))
				));
				Redirect::to('/user/settings');
				die();
			} catch(Exception $e) {
				die($e->getMessage());
			}
			
		} else {
		
			$error_string = "";
			foreach($validation->errors() as $error){
				$error_string .= ucfirst($error) . '<br />';
			}
		
			Session::flash('usercp_settings', '<div class="alert alert-danger">' . $error_string . '</div>');
		}
	}
}


$token = Token::generate();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="User panel">
    <meta name="author" content="Samerton">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

    <title><?php echo $sitename; ?> &bull; <?php echo $user_language['user_cp']; ?></title>
	
	<?php
	// Generate header and navbar content
	require('core/includes/template/generate.php');
	?>
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	</style>

  </head>

  <body>
	<?php
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	?>
	<br />
    <div class="container">	
	  <div class="row">
		<div class="col-md-3">
		  <?php require('pages/user/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <div class="well">
			<h2><?php echo $user_language['profile_settings']; ?></h2>
			<?php 
			if(Session::exists('settings_avatar_error')){
				echo Session::flash('settings_avatar_error');
			}
			
			if(Session::exists('usercp_settings')){
				echo Session::flash('usercp_settings');
			}
			
			// HTML Purifier
			$config = HTMLPurifier_Config::createDefault();
			$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
			$config->set('URI.DisableExternalResources', false);
			$config->set('URI.DisableResources', false);
			$config->set('HTML.Allowed', 'u,p,b,a,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
			$config->set('CSS.AllowedProperties', array('float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
			$config->set('HTML.AllowedAttributes', 'src, height, href, width, alt, class, *.style');
			$purifier = new HTMLPurifier($config);
			
			$signature = $purifier->purify(htmlspecialchars_decode($user->data()->signature));
			?>
			<form action="" method="post">
			  <?php
			  if($custom_usernames == 'true'){
			  ?>
			  <div class="form-group">
				<label for="InputScreenName"><?php echo $user_language['display_name']; ?></label>
				<input type="text" name="screenname" class="form-control" id="InputScreenName" value="<?php echo htmlspecialchars($user->data()->username); ?>">
			  </div>
			  <?php
			  }
			  ?>
			  <div class="form-group">
				<label for="signature"><?php echo $user_language['signature']; ?></label>
				<textarea rows="10" name="signature" id="signature">
					<?php echo $signature; ?>
				</textarea>
			  </div>
			  <input type="hidden" name="token" value="<?php echo $token; ?>" />
			  <input class="btn btn-primary" type="submit" name="submit" value="<?php echo $general_language['submit']; ?>" />
			</form>
			<br />
			<?php
			// Is avatar uploading enabled?
			$avatar_enabled = $queries->getWhere('settings', array('name', '=', 'user_avatars'));
			$avatar_enabled = $avatar_enabled[0]->value;

			if($avatar_enabled === '1'){
			?>
			<form action="/user/avatar_upload/" method="post" enctype="multipart/form-data">
			  <strong><?php echo $user_language['upload_an_avatar']; ?></strong>
			  <input type="file" name="uploaded_avatar" />
			  <input type="hidden" name="token" value="<?php echo $token; ?>" /><br />
			  <input class="btn btn-primary" type="submit" name="submit" value="<?php echo $general_language['submit']; ?>" />
			</form>
			<?php
			}
			?>
		  </div>
		</div>
      </div>
    </div>
	<?php
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts 
	require('core/includes/template/scripts.php');
	?>
	<script src="/core/assets/js/ckeditor.js"></script>
	<script type="text/javascript">
		CKEDITOR.replace( 'signature', {
			// Define the toolbar groups as it is a more accessible solution.
			toolbarGroups: [
				{"name":"basicstyles","groups":["basicstyles"]},
				{"name":"links","groups":["links"]},
				{"name":"paragraph","groups":["list","align"]},
				{"name":"insert","groups":["insert"]},
				{"name":"styles","groups":["styles"]},
				{"name":"about","groups":["about"]}
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: 'Anchor,Styles,Specialchar,Font,About,Flash,Iframe'
		} );
	</script>
  </body>
</html>