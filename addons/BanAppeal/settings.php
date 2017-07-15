<?php 
/*
 *  Made by Partydragen And Samerton
 *  http://partydragen.com/
 *
 */
// Settings for the BanAppeal addon
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
// Check language has been loaded, if not load now
if(!isset($banappeal_language)) require('addons/BanAppeal/language.php');
$banappeal_questions = $queries->tableExists('banappeal_questions');
if(empty($banappeal_questions)){
		// Create/update tables
		$data = $queries->alterTable("groups", "banappeal", "tinyint(1) NOT NULL DEFAULT '0'");
 		$data = $queries->alterTable("groups", "accept_banappeal", "tinyint(1) NOT NULL DEFAULT '0'");
 		$data = $queries->createTable("banappeal_comments", " `id` int(11) NOT NULL AUTO_INCREMENT, `aid` int(11) NOT NULL, `uid` int(11) NOT NULL, `time` int(11) NOT NULL, `content` mediumtext NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
 		$data = $queries->createTable("banappeal_questions", " `id` int(11) NOT NULL AUTO_INCREMENT, `type` int(11) NOT NULL, `name` varchar(16) NOT NULL, `question` varchar(256) NOT NULL, `options` text NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
 		$data = $queries->createTable("banappeal_replies", " `id` int(11) NOT NULL AUTO_INCREMENT, `uid` int(11) NOT NULL, `time` int(11) NOT NULL, `content` mediumtext NOT NULL, `status` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
 	}
?>
<h3>Addon: BanAppeal</h3>
<b>Authors:</b> Partydragen And Samerton<br />
<b>Version:</b> 1.0.3<br />
<b>Description:</b> Allow your players to post a Ban Appeal<br />

<h3><?php echo $banappeal_language['ban_appeal']; ?></h3>
<?php 
	if(!isset($_GET['module_action']) && !isset($_GET['question'])){
		if(Session::exists('apps_post_success')){
			echo Session::flash('apps_post_success');
		}
		if(Input::exists()){
			if(Token::check(Input::get('token'))){
				// Group permissions
				// Get all groups
				$groups = $queries->getWhere('groups', array('id', '<>', '0'));
				foreach($groups as $group){ 
					if(Input::get('view-' . $group->id) == 'on'){
						$view = 1;
					} else {
						$view = 0;
					}
					if(Input::get('accept-' . $group->id)){
						$accept = 1;
					} else {
						$accept = 0;
					}
					
					try {
						$queries->update('groups', $group->id, array(
							'banappeal' => $view,
							'accept_banappeal' => $accept
						));
						
					} catch(Exception $e) {
						die($e->getMessage());
					}
				}
				
				// Link location
				$c->setCache('banappeal');
				$c->store('linklocation', htmlspecialchars(Input::get('linkposition')));
				
			} else {
				Session::flash('apps_post_success', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
				echo '<script data-cfasync="false">window.location.replace("/admin/addons/?action=edit&addon=BanAppeal")</script>';
				die();
			}
		}
		
		// Query groups again to get updated values
		$groups = $queries->getWhere('groups', array('id', '<>', '0'));
?>
<form role="form" action="" method="post">
  <div class="form-group">
	<strong><?php echo $banappeal_language['permissions']; ?></strong><br /><br />
	<div class="row">
	  <div class="col-md-8">
		<div class="col-md-6">
		  <?php echo $banappeal_language['groups']; ?>
		</div>
		<div class="col-md-3">
		  <?php echo $banappeal_language['view_ban_appeal']; ?>
		</div>
		<div class="col-md-3">
		  <?php echo $banappeal_language['accept_reject_ban_appeal']; ?>
		</div>
	  </div>
	</div>

	<?php
	foreach($groups as $group){
	?>
	<div class="row">
	  <div class="col-md-8">
		<div class="col-md-6">
		  <?php echo htmlspecialchars($group->name); ?><br /><br />
		</div>
		<div class="col-md-3">
		  <div class="form-group">
			<input id="view-<?php echo $group->id; ?>" name="view-<?php echo $group->id; ?>" type="checkbox" class="js-switch" <?php if($group->banappeal == 1){ ?>checked <?php } ?>/>
		  </div>
		</div>
		<div class="col-md-3">
		  <div class="form-group">
			<input id="accept-<?php echo $group->id; ?>" name="accept-<?php echo $group->id; ?>" type="checkbox" class="js-switch" <?php if($group->accept_banappeal == 1){ ?>checked <?php } ?>/>
		  </div>
		</div>
	  </div>
	</div>
	<?php
	}
	?>
  </div>
  <div class="form-group">
	<label for="InputLinkPosition"><?php echo $admin_language['page_link_location']; ?></label>
	<?php
	// Get position of link
	$c->setCache('banappeal');
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
	<input type="submit" class="btn btn-default" value="<?php echo $banappeal_language['submit']; ?>">
  </div>
</form>

<br /><br />
<strong><?php echo $admin_language['questions']; ?></strong> <span class="pull-right"><a href="/admin/addons/?action=edit&amp;addon=BanAppeal&amp;module_action=new" class="btn btn-primary"><?php echo $banappeal_language['new_question']; ?></a></span><br />
<?php 
// Get a list of questions
$questions = $queries->getWhere('banappeal_questions', array('id', '<>', 0));
if(count($questions)){
?>
<table class="table table-striped">
  <tr>
	<th><?php echo $banappeal_language['name']; ?></th>
	<th><?php echo $banappeal_language['question']; ?></th>
	<th><?php echo $banappeal_language['type']; ?></th>
	<th><?php echo $banappeal_language['options']; ?></th>
  </tr>
<?php
	foreach($questions as $question){
?>
  <tr>
	<td><a href="/admin/addons/?action=edit&amp;addon=BanAppeal&amp;question=<?php echo $question->id; ?>"><?php echo ucfirst(htmlspecialchars($question->name)); ?></a></td>
	<td><?php echo htmlspecialchars($question->question); ?></td>
	<td><?php echo $queries->convertQuestionType($question->type); ?></td>
	<td><?php 
	$options = explode(',', $question->options);
	foreach($options as $option){
		echo htmlspecialchars($option) . '<br />';
	}
	?></td>
  </tr>
<?php
		echo '<a href="/admin/addons/?action=edit&addon=BanAppeal&question=' . $question->id . '"></a><br />';
	}
} else {
	echo $banappeal_language['no_questions'];
}
?>
</table>
<?php 
	} else if(isset($_GET['question']) && !isset($_GET['module_action'])) { 
		// Get the question
		if(!is_numeric($_GET['question'])){
			echo '<script data-cfasync="false">window.location.replace(\'/admin/addons/?action=edit&addon=BanAppeal\');</script>';
			die();
		}
		$question_id = $_GET['question'];
		$question = $queries->getWhere('banappeal_questions', array('id', '=', $question_id));
		
		// Does the question exist?
		if(!count($question)){
			echo '<script data-cfasync="false">window.location.replace(\'/admin/addons/?action=edit&addon=BanAppeal\');</script>';
			die();
		}
		// Deal with the input
		if(Input::exists()){
			if(Token::check(Input::get('token'))){
				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'name' => array(
						'required' => true,
						'min' => 2,
						'max' => 16
					),
					'question' => array(
						'required' => true,
						'min' => 2,
						'max' => 255
					)
				));
				
				if($validation->passed()){
					// Get options into a string
					$options = str_replace("\n", ',', Input::get('options'));
					
					$queries->update('banappeal_questions', $question_id, array(
						'type' => Input::get('type'),
						'name' => htmlspecialchars(Input::get('name')),
						'question' => htmlspecialchars(Input::get('question')),
						'options' => htmlspecialchars($options)
					));
					Session::flash('apps_post_success', '<div class="alert alert-info">' . $admin_language['successfully_updated'] . '</div>');
					echo '<script data-cfasync="false">window.location.replace(\'/admin/addons/?action=edit&addon=BanAppeal\');</script>';
					die();
				} else {
					// errors
					$error = array();
					foreach($validation->errors() as $item){
						if(strpos($item, 'is required') !== false){
							switch($item){
								case (strpos($item, 'name') !== false):
									$error[] = $banappeal_language['name_required'];
								break;
								case (strpos($item, 'question') !== false):
									$error[] = $banappeal_language['question_required'];
								break;
							}
						} else if(strpos($item, 'minimum') !== false){
							switch($item){
								case (strpos($item, 'name') !== false):
									$error[] = $banappeal_language['name_minimum'];
								break;
								case (strpos($item, 'question') !== false):
									$error[] = $banappeal_language['question_minimum'];
								break;
							}
						} else if(strpos($item, 'maximum') !== false){
							switch($item){
								case (strpos($item, 'name') !== false):
									$error[] = $banappeal_language['name_maximum'];
								break;
								case (strpos($item, 'question') !== false):
									$error[] = $banappeal_language['question_maximum'];
								break;
							}
						}
					}
				}
		
			} else {
				// Invalid token
				$error[] = $admin_language['invalid_token'];
			}
		}
		$question = $question[0];
?>
<!-- Errors? Display here -->
<?php
if(isset($error)){
?>
<div class="alert alert-danger">
<?php
	foreach($error as $item){
		echo $item . '<br />';
	}
?>
</div>
<?php
}
?>
<strong><?php echo $banappeal_language['editing_question']; ?></strong>
<span class="pull-right"><a href="/admin/addons/?action=edit&amp;addon=BanAppeal&amp;question=<?php echo $question->id; ?>&amp;module_action=delete" onclick="return confirm('<?php echo $banappeal_language['confirm_cancellation']; ?>');" class="btn btn-danger"><?php echo $banappeal_language['delete_question']; ?></a></span>
<br /><br />

<form method="post" action="">
  <label for="name"><?php echo $banappeal_language['name']; ?></label>
  <input class="form-control" type="text" name="name" id="name" placeholder="<?php echo $banappeal_language['name']; ?>" value="<?php echo htmlspecialchars($question->name); ?>">
  <br />
  <label for="question"><?php echo $banappeal_language['question']; ?></label>
  <input class="form-control" type="text" name="question" id="question" placeholder="<?php echo $banappeal_language['question']; ?>" value="<?php echo htmlspecialchars($question->question); ?>">
  <br />
  <label for="type"><?php echo $admin_language['type']; ?></label>
  <select name="type" id="type" class="form-control">
	<option value="1"<?php if($question->type == 1){ ?> selected<?php } ?>><?php echo $banappeal_language['dropdown']; ?></option>
	<option value="2"<?php if($question->type == 2){ ?> selected<?php } ?>><?php echo $banappeal_language['text']; ?></option>
	<option value="3"<?php if($question->type == 3){ ?> selected<?php } ?>><?php echo $banappeal_language['textarea']; ?></option>
  </select>
  <br />
  <label for="options"><?php echo $banappeal_language['options']; ?> - <em><?php echo $banappeal_language['options_help']; ?></em></label>
  <?php
  // Get already inputted options
  if($question->options == null){
	  $options = '';
  } else {
	  $options = str_replace(',', "\n", htmlspecialchars($question->options));
  }
  ?>
  <textarea rows="5" class="form-control" name="options" id="options" placeholder="<?php echo $banappeal_language['options']; ?>"><?php echo $options; ?></textarea>
  <br />
  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
  <input type="submit" class="btn btn-primary" value="<?php echo $banappeal_language['submit']; ?>">
</form>


<?php 
	} else if(isset($_GET['module_action']) && $_GET['module_action'] == 'new') { 
		// Deal with the input
		if(Input::exists()){
			if(Token::check(Input::get('token'))){
				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'name' => array(
						'required' => true,
						'min' => 2,
						'max' => 16
					),
					'question' => array(
						'required' => true,
						'min' => 2,
						'max' => 255
					)
				));
				
				if($validation->passed()){
					// Get options into a string
					$options = str_replace("\n", ',', Input::get('options'));
					
					$queries->create('banappeal_questions', array(
						'type' => Input::get('type'),
						'name' => htmlspecialchars(Input::get('name')),
						'question' => htmlspecialchars(Input::get('question')),
						'options' => htmlspecialchars($options)
					));
					Session::flash('apps_post_success', '<div class="alert alert-info">' . $admin_language['successfully_updated'] . '</div>');
					echo '<script data-cfasync="false">window.location.replace(\'/admin/addons/?action=edit&addon=BanAppeal\');</script>';
					die();
				} else {
					// errors
					$error = array();
					foreach($validation->errors() as $item){
						if(strpos($item, 'is required') !== false){
							switch($item){
								case (strpos($item, 'name') !== false):
									$error[] = $banappeal_language['name_required'];
								break;
								case (strpos($item, 'question') !== false):
									$error[] = $banappeal_language['question_required'];
								break;
							}
						} else if(strpos($item, 'minimum') !== false){
							switch($item){
								case (strpos($item, 'name') !== false):
									$error[] = $banappeal_language['name_minimum'];
								break;
								case (strpos($item, 'question') !== false):
									$error[] = $banappeal_language['question_minimum'];
								break;
							}
						} else if(strpos($item, 'maximum') !== false){
							switch($item){
								case (strpos($item, 'name') !== false):
									$error[] = $banappeal_language['name_maximum'];
								break;
								case (strpos($item, 'question') !== false):
									$error[] = $banappeal_language['question_maximum'];
								break;
							}
						}
					}
				}
				
			} else {
				// Invalid token
				$error[] = $admin_language['invalid_token'];
			}
		}
?>
<strong><?php echo $banappeal_language['new_question']; ?></strong><br /><br />
<!-- Errors? Display here -->
<?php
if(isset($error)){
?>
<div class="alert alert-danger">
<?php
	foreach($error as $item){
		echo $item . '<br />';
	}
?>
</div>
<?php
}
?>
<form method="post" action="">
  <label for="name"><?php echo $banappeal_language['name']; ?></label>
  <input class="form-control" type="text" name="name" id="name" placeholder="<?php echo $banappeal_language['name']; ?>">
  <br />
  <label for="question"><?php echo $banappeal_language['question']; ?></label>
  <input class="form-control" type="text" name="question" id="question" placeholder="<?php echo $banappeal_language['question']; ?>">
  <br />
  <label for="type"><?php echo $banappeal_language['type']; ?></label>
  <select name="type" id="type" class="form-control">
	<option value="1"><?php echo $banappeal_language['dropdown']; ?></option>
	<option value="2"><?php echo $banappeal_language['text']; ?></option>
	<option value="3"><?php echo $banappeal_language['textarea']; ?></option>
  </select>
  <br />
  <label for="options"><?php echo $banappeal_language['options']; ?> - <em><?php echo $banappeal_language['options_help']; ?></em></label>
  <textarea rows="5" class="form-control" name="options" id="options" placeholder="<?php echo $banappeal_language['options']; ?>"></textarea>
  <br />
  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
  <input type="submit" class="btn btn-primary" value="<?php echo $banappeal_language['submit']; ?>">
</form>
<?php 
	} else if(isset($_GET['module_action']) && $_GET['module_action'] == 'delete' && isset($_GET['question'])) {
		// Get the question
		if(!is_numeric($_GET['question'])){
			echo '<script data-cfasync="false">window.location.replace(\'/admin/addons/?action=edit&addon=BanAppeal\');</script>';
			die();
		}
		$question_id = $_GET['question'];
		$question = $queries->getWhere('banappeal_questions', array('id', '=', $question_id));
		
		// Does the question exist?
		if(!count($question)){
			echo '<script data-cfasync="false">window.location.replace(\'/admin/addons/?action=edit&addon=BanAppeal\');</script>';
			die();
		}
		
		// Exists, we can delete it
		$queries->delete('banappeal_questions', array('id', '=', $question_id));
		
		Session::flash('apps_post_success', '<div class="alert alert-info">' . $banappeal_language['question_deleted'] . '</div>');
		echo '<script data-cfasync="false">window.location.replace(\'/admin/addons/?action=edit&addon=BanAppeal\');</script>';
		die();
	}
?>