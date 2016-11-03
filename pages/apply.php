<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// HTMLPurifier
require('core/includes/htmlpurifier/HTMLPurifier.standalone.php');

/*
 *  User must be logged in
 */
if(!$user->isLoggedIn()){
	Session::flash('home', '<div class="alert alert-warning">' . $user_language['not_logged_in'] . '</div>');
	Redirect::to('/');
	die();
}


/*
 *  Check if page is enabled
 */ 
$staff_applications = $queries->getWhere('core_modules', array('name', '=', 'Staff_Applications'));
if($staff_applications[0]->enabled == 0){
	Redirect::to('/');
	die();
}

/* 
 *  Handle input
 */
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		// Get all answers into one string
		unset($_POST['token']);
		
		$content = array();
		foreach($_POST as $key => $item){
			$content[] = array($key, htmlspecialchars($item));
		}
		
		$content = json_encode($content);
		
		$queries->create('staff_apps_replies', array(
			'uid' => $user->data()->id,
			'time' => date('U'),
			'content' => $content
		));
		
		$app_id = $queries->getLastId();
		
		// Moderator alerts
		$mod_groups = $queries->getWhere('groups', array('staff_apps', '=', 1));
		foreach($mod_groups as $mod_group){
			$mod_users = $queries->getWhere('users', array('group_id', '=', $mod_group->id));
			foreach($mod_users as $individual){
				$queries->create('alerts', array(
					'user_id' => $individual->id,
					'type' => $user_language['staff_application'],
					'url' => '/mod/applications/?app=' . $app_id,
					'content' => str_replace('{x}', htmlspecialchars($user->data()->username), $mod_language['new_app_submitted_alert']),
					'created' => date('U')
				));
			}
		}
		
		Session::flash('app_success', '<div class="alert alert-success">' . $user_language['application_submitted'] . '</div>');
		$completed = 1;
		
	} else {
		// Invalid token
		Session::flash('app_succes', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
	}
}

if(!isset($completed)){
	// Has the user already submitted an application?
	$already_submitted = $queries->getWhere('staff_apps_replies', array('uid', '=', $user->data()->id));
	foreach($already_submitted as $item){
		if($item->status == 0){
			$completed = 2;
			break;
		}
	}
	$already_submitted = null;
}

$page = $navbar_language['staff_apps'];

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Staff application page for the <?php echo $sitename; ?> community">
    <meta name="author" content="<?php echo $sitename; ?>">
    <meta name="theme-color" content="#454545" />
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $navbar_language['staff_apps'];
	
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
	
	<div class="container">
	<?php 
	    if(Session::exists('staff_app')){
		  echo Session::flash('staff_app');
	    }
	?>
	  <div class="well">
		<h2><?php echo $user_language['staff_application']; ?></h2>
		<?php
		if(!isset($completed)){
		?>
		<div class="row">
		  <div class="col-md-5">
			<form action="" method="post">
			<?php 
			// Get all questions
			$questions = $queries->getWhere('staff_apps_questions', array('id', '<>', 0)); 
			
			foreach($questions as $question){
				if($question->type == 3){
					// text area
			?>
			  <label for="<?php echo htmlspecialchars($question->name); ?>"><?php echo htmlspecialchars($question->question); ?></label>
			  <textarea class="form-control" id="<?php echo htmlspecialchars($question->name); ?>" name="<?php echo $question->id; ?>"></textarea><br />
			<?php
				} else if($question->type == 1){
					// dropdown
			?>
			  <label for="<?php echo htmlspecialchars($question->name); ?>"><?php echo htmlspecialchars($question->question); ?></label>
			  <select name="<?php echo $question->id; ?>" id="<?php echo htmlspecialchars($question->name); ?>" class="form-control">
			    <?php
				$options = explode(',', $question->options);
				foreach($options as $option){
				?>
				  <option value="<?php echo htmlspecialchars($option); ?>"><?php echo htmlspecialchars($option); ?></option>
				<?php
				}
				?>
			  </select><br />
			<?php
				} else {
					// normal input tag
			?>
			  <label for="<?php echo htmlspecialchars($question->name); ?>"><?php echo htmlspecialchars($question->question); ?></label>
			  <input type="text" class="form-control" id="<?php echo htmlspecialchars($question->name); ?>" name="<?php echo $question->id; ?>"><br />
			<?php
				}
			}
			
			?>
			  <br />
			  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
			</form>
		  </div>
		</div>
		<?php
		} else {
			if(Session::exists('app_success')){
				echo Session::flash('app_success');
			}
			if($completed === 2){
		?>
		<div class="alert alert-info"><?php echo $user_language['application_already_submitted']; ?></div>
		<?php
			}
		}
		?>
	  </div>
    </div>
    <?php
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts 
	require('core/includes/template/scripts.php');
	?>
  </body>
</html>
