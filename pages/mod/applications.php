<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Mod check
if($user->isLoggedIn()){
	if(!$user->canViewMCP($user->data()->id) || !$user->canViewApps($user->data()->id)){
		Redirect::to('/');
		die();
	}
} else {
	Redirect::to('/');
	die();
}

// page for ModCP sidebar
$mod_page = 'applications';

if(isset($_GET['app'])){
	// Does the application exist?
	$application = $queries->getWhere('staff_apps_replies', array('id', '=', htmlspecialchars($_GET['app'])));
	if(empty($application)){
		// Doesn't exist
		echo '<script>window.location.replace(\'/mod/applications\');</script>';
		die();
	} else {
		$application = $application[0];
		
		if(!isset($_GET['action'])){
			// Handle comment input
			if(Input::exists()){
				if(Token::check(Input::get('token'))){
					// Valid token
					$validate = new Validate();
					$validation = $validate->check($_POST, array(
						'comment' => array(
							'required' => true,
							'min' => 2,
							'max' => 2048
						)
					));
					if($validation->passed()){
						try {
							$queries->create("staff_apps_comments", array(
								'aid' => $application->id,
								'uid' => $user->data()->id,
								'time' => date('U'),
								'content' => htmlspecialchars(Input::get('comment'))
							));
							Session::flash('mod_staff_app', '<div class="alert alert-info alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $mod_language['comment_added'] . '</div>');
						} catch(Exception $e){
							die($e->getMessage());
						}
					} else {
						Session::flash('mod_staff_app', '<div class="alert alert-danger">' . $mod_language['comment_error'] . '</div>');
					}
				} else {
					// Invalid token
					Session::flash('mod_staff_app', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
				}
			}
			
			// Decode the questions/answers
			$answers = json_decode($application->content, true);
			// Get questions
			$questions = $queries->getWhere('staff_apps_questions', array('id', '<>', 0));
		} else {
			// Can the user actually accept an application?
			if($user->canAcceptApps($user->data()->id)){
				// Who posted the app?
				$user_posted = $application->uid;
				
				if($_GET['action'] == 'accept'){
					$queries->update('staff_apps_replies', $application->id, array(
						'status' => 1
					));
					// Add alert to tell user that it's been accepted
					$queries->create('alerts', array(
						'user_id' => $user_posted,
						'type' => $user_language['staff_application'],
						'url' => '#',
						'content' => str_replace('{x}', htmlspecialchars($user->data()->username), $user_language['application_accepted']),
						'created' => date('U')
					));
					
					
				} else if($_GET['action'] == 'reject'){
					$queries->update('staff_apps_replies', $application->id, array(
						'status' => 2
					));
					// Add alert to tell user that it's been rejected
					$queries->create('alerts', array(
						'user_id' => $user_posted,
						'type' => $user_language['staff_application'],
						'url' => '#',
						'content' => str_replace('{x}', htmlspecialchars($user->data()->username), $user_language['application_rejected']),
						'created' => date('U')
					));
					
				}
			}
			Redirect::to('/mod/applications/?app=' . $application->id);
			die();
		}
	}
}

$token = Token::generate();

// HTMLPurifier
require('core/includes/htmlpurifier/HTMLPurifier.standalone.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Moderator panel">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $mod_language['mod_cp'] . ' - ' . $navbar_language['staff_apps'];
	
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
		  <?php require('pages/mod/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
			<h2><?php echo $navbar_language['staff_apps']; ?></h2>
			<?php 
			if(!isset($_GET['app'])){
			?>
			<div class="well well-sm">
				<?php
					if(!isset($_GET['view'])){ 
						// Get open applications
						$applications = $queries->getWhere('staff_apps_replies', array('status', '=', 0));
						echo $mod_language['viewing_open_applications'] . '<br /><br />';
					} else if(isset($_GET['view']) && $_GET['view'] == 'accepted'){ 
						// Get accepted applications
						$applications = $queries->getWhere('staff_apps_replies', array('status', '=', 1));
						echo $mod_language['viewing_accepted_applications'] . '<br /><br />';
					} else if(isset($_GET['view']) && $_GET['view'] == 'declined'){ 
						// Get declined applications
						$applications = $queries->getWhere('staff_apps_replies', array('status', '=', 2));
						echo $mod_language['viewing_declined_applications'] . '<br /><br />';
					} 
					if(count($applications)){
				?>
				<table class="table table-striped">
				  <thead>
				    <tr>
				      <th></th>
					  <th><?php echo $user_language['minecraft_username']; ?></th>
					  <th><?php echo $mod_language['time_applied']; ?></th>
				    </tr>
				  </thead>
				  <?php 
					  foreach($applications as $application){ 
						// Get username
						$username = $user->IdToMCName($application->uid);
				  ?>
				  <tbody>
				    <tr>
				      <td><a href="/mod/applications/?app=<?php echo $application->id; ?>" class="btn btn-info btn-xs"><?php echo $general_language['view']; ?></a></td>
					  <td><a href="/profile/<?php echo htmlspecialchars($username); ?>"><?php echo htmlspecialchars($username); ?></a></td>
					  <td><?php echo date('d M Y, G:i', $application->time); ?></td>
				    </tr>
				  </tbody>
				  <?php } ?>
				</table>
				<?php
					} else {
						echo $mod_language['no_applications'];
					}
				?>
			</div>
			<?php
			} else {
				$username = htmlspecialchars($user->idToMCName($application->uid));

				if(Session::exists('mod_staff_app')){
				  echo Session::flash('mod_staff_app');
				}
				
                echo str_replace('{x}', '<a href="/profile/' . $username . '">' . $username . '</a>', $mod_language['viewing_app_from']);

				if($application->status == 0){ 
					?>
					<span class="label label-info"><?php echo $mod_language['open']; ?></span>
					<?php 
				} else if($application->status == 1){ 
					?>
					<span class="label label-success"><?php echo $mod_language['accepted']; ?></span>
					<?php 
				} else if($application->status == 2){ 
					?>
					<span class="label label-danger"><?php echo $mod_language['declined']; ?></span>
					<?php 
				} 
				?>
			<div class="well well-sm">
			<span class="pull-right">
			  <?php 
			  // Can the user accept applications?
			  if($application->status == 0 && $user->canAcceptApps($user->data()->id)){
			  ?>
			  <div class="btn-group">
			    <a href="/mod/applications/?app=<?php echo $application->id; ?>&action=accept" class="btn btn-success"><?php echo $mod_language['accept']; ?></a><a href="/mod/applications/?app=<?php echo $application->id; ?>&action=reject" class="btn btn-danger"><?php echo $mod_language['decline']; ?></a>
			  </div>
			  <?php
			  }
			  ?>
			</span><br /><br />
			<hr>
			<?php 
			foreach($answers as $answer){
				// Get the question itself from the ID
				foreach($questions as $key => $item){
					if($item->id == $answer[0]){
					  echo '<strong>' . htmlspecialchars($item->question) . '</strong>'; 
					}
				}
				echo '<p>' . htmlspecialchars($answer[1]) . '</p>';
			}
			?>
			<hr>
			<h4><?php echo $mod_language['comments']; ?></h4>
			<?php
			// Get comments
			$comments = $queries->getWhere('staff_apps_comments', array('aid', '=', $application->id));
			if(count($comments)){
				foreach($comments as $comment){
					$username = htmlspecialchars($user->idToName($comment->uid));
					$mcusername = htmlspecialchars($user->idToMCName($comment->uid));
			?>
			<div class="panel panel-primary">
			  <div class="panel-heading">
				<a class="white-text" href="/profile/<?php echo $mcusername; ?>"><?php echo $username; ?></a>
				<span class="pull-right">
				  <?php echo date('jS M Y , g:ia', $comment->time); ?>
				</span>
			  </div>
			  <div class="panel-body">
				<?php
				// Purify comment
				$config = HTMLPurifier_Config::createDefault();
				$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
				$config->set('URI.DisableExternalResources', false);
				$config->set('URI.DisableResources', false);
				$config->set('HTML.Allowed', 'u,p,a,b,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
				$config->set('CSS.AllowedProperties', array('float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
				$config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
				$config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
				$purifier = new HTMLPurifier($config);
				echo $purifier->purify(htmlspecialchars_decode($comment->content));
				?>
			  </div>
			</div>
			<?php 
				} 
			}
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo $mod_language['new_comment']; ?>
				</div>
				<div class="panel-body">
					<form action="" method="post">
						<textarea name="comment" class="form-control" rows="3"></textarea>
						<br />
						<?php echo '<input type="hidden" name="token" value="' . $token . '">'; ?>
						<button type="submit" class="btn btn-danger">
						  <?php echo $general_language['submit']; ?>
						</button>
					</form>
				</div>
			</div>
			</div>
			<?php
			}
			?>
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
  </body>
</html>
