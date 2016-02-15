<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

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
 
$adm_page = "custom_pages"; // For admin sidebar

require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTML Purifier

if(!isset($_GET['action'])){
	// Deal with input for editing pages only
	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'url' => array(
					'required' => true,
					'min' => 1,
					'max' => 20
				),
				'title' => array(
					'required' => true,
					'min' => 1,
					'max' => 30
				),
				'content' => array(
					'required' => true,
					'min' => 5,
					'max' => 20480
				),
				'link_location' => array(
					'required' => true
				)
			));
			
			if($validation->passed()){
				try {
					$queries->update("custom_pages", $_GET["page"], array(
						"url" => htmlspecialchars(Input::get('url')),
						"title" => htmlspecialchars(Input::get('title')),
						"content" => htmlspecialchars(Input::get('content')),
						"link_location" => Input::get('link_location')
					));
				} catch(Exception $e){
					die($e->getMessage());
				}
				
				Session::flash('custom-pages', '<div class="alert alert-info">' . $admin_language['page_successfully_edited'] . '</div>');
				echo '<script>window.location.replace(\'/admin/pages\');</script>';
				die();
				
			} else {
				$error = '<div class="alert alert-warning"><p><strong>' . $admin_language['unable_to_edit_page'] . '</strong></p><p>' . $admin_language['create_page_error'] . '</p></div>';
			}
		} else {
			$error = '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>';
		}
	}
} else if(isset($_GET['action']) && $_GET['action'] == 'new'){
	// Deal with input for creating new pages
	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			// Valid token
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'url' => array(
					'required' => true,
					'min' => 1,
					'max' => 20
				),
				'title' => array(
					'required' => true,
					'min' => 1,
					'max' => 30
				),
				'content' => array(
					'required' => true,
					'min' => 5,
					'max' => 20480
				),
				'link_location' => array(
					'required' => true
				)
			));
			
			if($validation->passed()){
				// Go ahead and input the page data
				try {
					$queries->create("custom_pages", array(
						"url" => htmlspecialchars(Input::get('url')),
						"title" => htmlspecialchars(Input::get('title')),
						"content" => htmlspecialchars(Input::get('content')),
						"link_location" => Input::get('link_location')
					));
				} catch(Exception $e){
					die($e->getMessage());
				}
				
				Session::flash('custom-pages', '<div class="alert alert-info">' . $admin_language['page_successfully_created'] . '</div>');
				echo '<script>window.location.replace(\'/admin/pages\');</script>';
				die();
			} else {
				$error = '<div class="alert alert-warning"><p><strong>' . $admin_language['unable_to_create_page'] . '</strong></p><p>' . $admin_language['create_page_error'] . '</p></div>';
			}
		} else {
			// Invalid token
			$error = '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>';
		}
	}
}

$token = Token::generate(); // generate token
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin panel">
    <meta name="author" content="Samerton">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>

    <title><?php echo $admin_language['admin_cp']; ?> &bull; <?php echo $admin_language['custom_pages']; ?></title>
	
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
    <div class="container">	
	  <?php
	  // Index page
	  // Load navbar
	  $smarty->display('styles/templates/' . $template . '/navbar.tpl');
	  ?>
	  <br />
	  <div class="row">
		<div class="col-md-3">
		  <?php require('pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
			<div class="well">
				<?php if(!isset($_GET["page"]) && !isset($_GET['action'])) { ?>
				<h2 style="display:inline;"><?php echo $admin_language['custom_pages']; ?></h2>
				<span class="pull-right"><a href="/admin/pages/?action=new" class="btn btn-primary"><?php echo $admin_language['new_page']; ?></a></span>
				<br /><br />
				<?php
				if(Session::exists('custom-pages')){
					echo Session::flash('custom-pages');
				}
				?>
				<?php echo $admin_language['click_on_page_to_edit']; ?><br /><br />
				<?php 
				$pages = $queries->getAll("custom_pages", array("id", "<>", "0")); 
				foreach($pages as $page){
				?>
				<a href="/admin/pages/?page=<?php echo $page->id; ?>"><?php echo htmlspecialchars($page->title); ?></a><br />
				<?php 
				}
				?>
				<?php 
				} else if(isset($_GET['page']) && !isset($_GET['action'])) {
					$page = $queries->getWhere("custom_pages", array("id", "=", $_GET["page"]));
					if(!count($page)){
						echo '<script>window.location.replace(\'/admin/pages\');</script>';
						die();
					}
				?>
				<h2 style="display:inline;"><?php echo $admin_language['page']; ?> <?php echo htmlspecialchars($page[0]->title); ?></h2>
				<span class="pull-right"><a onclick="return confirm('<?php echo $admin_language['confirm_delete_page']; ?>');" href="/admin/pages/?action=delete&amp;pid=<?php echo $page[0]->id; ?>" class="btn btn-danger"><?php echo $admin_language['delete_page']; ?></a></span>
				<br /><br />
				<strong><?php echo $admin_language['url']; ?></strong> http://<?php echo $_SERVER['SERVER_NAME'] . htmlspecialchars($page[0]->url); ?><br /><br />
				<?php
				if(isset($error)){
					echo $error;	
				}
				?>
				<form action="" method="post">
				  <div class="form-group">
				    <label for="url"><?php echo $admin_language['page_url']; ?> <em><?php echo $admin_language['page_url_example']; ?></em></label>
				    <input class="form-control" type="text" name="url" id="url" value="<?php echo htmlspecialchars($page[0]->url); ?>" />
				  </div>
				  <div class="form-group">
				    <label for="title"><?php echo $admin_language['page_title']; ?></label>
					<input class="form-control" type="text" name="title" id="title" value="<?php echo htmlspecialchars($page[0]->title); ?>" />
				  </div>
				  <div class="form-group">
				    <label for="link_location"><?php echo $admin_language['page_link_location']; ?></label>
					<select class="form-control" id="link_location" name="link_location">
					  <option value="1" <?php if($page[0]->link_location == 1){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_navbar']; ?></option>
					  <!--<option value="2" <?php //if($page[0]->link_location == 2){ echo 'selected="selected"'; } ?>><?php //echo $admin_language['page_link_more']; ?></option>-->
					  <option value="3" <?php if($page[0]->link_location == 3){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_footer']; ?></option>
					  <option value="4" <?php if($page[0]->link_location == 4){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_none']; ?></option>
					</select>
				  </div>
				  <strong><?php echo $admin_language['page_content']; ?></strong><br />
				  <textarea rows="10" name="content" id="content_editor">
				  <?php 
			      // Initialise HTML Purifier
				  $config = HTMLPurifier_Config::createDefault();
				  $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
				  $config->set('URI.DisableExternalResources', false);
				  $config->set('HTML.Allowed', 'u,p,b,i,a,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
				  $config->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size', 'border-style', 'border-width', 'height', 'width'));
				  $config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style, frameborder');
				  $config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
				  $config->set('HTML.SafeIframe', true);
				  $config->set('URI.SafeIframeRegexp', '%%');
				  $purifier = new HTMLPurifier($config);
				  echo $purifier->purify(htmlspecialchars_decode($page[0]->content)); 
				  ?>
				  </textarea>
				  <input type="hidden" name="token" value="<?php echo $token; ?>">
				  <br /><br />
				  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
				</form>
				<?php
				} else if(isset($_GET['action'])){
					if(!isset($_GET['page']) && $_GET['action'] == 'new'){
						// new page
						?>
				<h2><?php echo $admin_language['new_page']; ?></h2>
				<?php
				if(isset($error)){
					echo $error;	
				}
				?>
				<form action="" method="post">
				  <div class="form-group">
				    <label for="url"><?php echo $admin_language['page_url']; ?> <em><?php echo $admin_language['page_url_example']; ?></em></label>
				    <input class="form-control" type="text" name="url" id="url" value="<?php echo htmlspecialchars(Input::get('url')); ?>" />
				  </div>
				  <div class="form-group">
				    <label for="title"><?php echo $admin_language['page_title']; ?></label>
					<input class="form-control" type="text" name="title" id="title" value="<?php echo htmlspecialchars(Input::get('title')); ?>" />
				  </div>
				  <div class="form-group">
				    <label for="link_location"><?php echo $admin_language['page_link_location']; ?></label>
					<select class="form-control" id="link_location" name="link_location">
					  <option value="1" selected><?php echo $admin_language['page_link_navbar']; ?></option>
					  <!--<option value="2"><?php //echo $admin_language['page_link_more']; ?></option>-->
					  <option value="3"><?php echo $admin_language['page_link_footer']; ?></option>
					  <option value="4"><?php echo $admin_language['page_link_none']; ?></option>
					</select>
				  </div>
				  <strong><?php echo $admin_language['page_content']; ?></strong><br />
				  <textarea rows="10" name="content" id="content_editor">
				  <?php 
			      // Initialise HTML Purifier
				  $config = HTMLPurifier_Config::createDefault();
				  $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
				  $config->set('URI.DisableExternalResources', false);
				  $config->set('URI.DisableResources', false);
				  $config->set('HTML.Allowed', 'u,p,b,i,a,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
				  $config->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size', 'border-style', 'border-width', 'height', 'width'));
				  $config->set('HTML.AllowedAttributes', 'href, src, height, width, alt, class, *.style, frameborder');
				  $config->set('HTML.SafeIframe', true);
				  $config->set('URI.SafeIframeRegexp', '%%');
				  $purifier = new HTMLPurifier($config);
				  echo $purifier->purify(Input::get('content')); 
				  ?>
				  </textarea>
				  <input type="hidden" name="token" value="<?php echo $token; ?>">
				  <br /><br />
				  <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
				</form>
						<?php
					} else if(isset($_GET['action']) && $_GET['action'] == 'delete'){
						// Delete a page
						// Check the page exists
						if(!isset($_GET['pid']) || !is_numeric($_GET['pid'])){
							echo '<script>window.location.replace(\'/admin/pages\');</script>';
							die();
						}
						
						// Try to delete it
						try {
							$queries->delete("custom_pages", array('id', '=', $_GET['pid']));
						} catch(Exception $e){
							die($e->getMessage());
						}
						
						Session::flash('custom-pages', '<div class="alert alert-info">' . $admin_language['page_deleted_successfully'] . '</div>');
						echo '<script>window.location.replace(\'/admin/pages\');</script>';
						die();
					}
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
		CKEDITOR.replace( 'content_editor', {
			// Define the toolbar groups as it is a more accessible solution.
			toolbarGroups: [
				{"name":"basicstyles","groups":["basicstyles"]},
				{"name":"links","groups":["links"]},
				{"name":"paragraph","groups":["list","align"]},
				{"name":"insert","groups":["insert"]},
				{"name":"styles","groups":["styles"]},
				{"name":"colors","groups":["colors"]},
				{"name":"about","groups":["about"]},
				{"name":"mode","groups":["mode"]}
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: 'Anchor,Styles,Specialchar,Font,About,Flash'
		} );
	</script>

  </body>
</html>