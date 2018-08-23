<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Admin page metadata page
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
		} else {
			if(!$user->hasPermission('admincp.pages.metadata')){
				require(ROOT_PATH . '/404.php');
				die();
			}
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

// Set page name for sidebar
$page = 'admin';
$admin_page = 'pages';
?>
<!DOCTYPE html>
<html lang="<?php echo(defined('HTML_LANG') ? HTML_LANG : 'en'); ?>" <?php if(defined('HTML_RTL') && HTML_RTL === true) echo ' dir="rtl"'; ?>>
<head>
	<!-- Standard Meta -->
	<meta charset="<?php echo (defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<meta name="robots" content="noindex">

	<?php
	$title = $language->get('admin', 'admin_cp');
	require(ROOT_PATH . '/core/templates/admin_header.php');
	?>

	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.css">
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">

</head>

<body>
<?php
require(ROOT_PATH . '/modules/Core/pages/admin/navbar.php');
?>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<?php require(ROOT_PATH . '/modules/Core/pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-block">
					<?php if($user->hasPermission('admincp.pages')){ ?>
						<ul class="nav nav-pills">
							<li class="nav-item">
								<a class="nav-link" href="<?php echo URL::build('/admin/pages'); ?>"><?php echo $language->get('admin', 'custom_pages'); ?></a>
							</li>
							<li class="nav-item">
								<a class="nav-link active" href="<?php echo URL::build('/admin/metadata'); ?>"><?php echo $language->get('admin', 'page_metadata'); ?></a>
							</li>
						</ul>
						<hr />
					<?php } ?>
					<h3 style="display:inline;"><?php echo $language->get('admin', 'page_metadata'); ?></h3>
					<?php if(isset($_GET['id'])){ echo '<a href="' . URL::build('/admin/metadata') . '" class="btn btn-primary float-right">' . $language->get('general', 'back') . '</a>'; } ?>
					<hr />
					<?php
					if(isset($_GET['id'])){
						$page = $pages->getPageById($_GET['id']);
						if(is_null($page)){
							Redirect::to(URL::build('/admin/metadata'));
							die();
						}

						$page_metadata = $queries->getWhere('page_descriptions', array('page', '=', $page['key']));

						if(Input::exists()){
							if(Token::check(Input::get('token'))){
								if(isset($_POST['description'])){
									if(strlen($_POST['description']) > 500){
										$error = $language->get('admin', 'description_max_500');
									} else {
										$description = $_POST['description'];
									}
								} else
									$description = null;

								if(isset($_POST['keywords']))
									$keywords = $_POST['keywords'];
								else
									$keywords = null;

								if(!isset($error)){
									if(count($page_metadata)){
										$page_id = $page_metadata[0]->id;

										$queries->update('page_descriptions', $page_id, array(
											'description' => $description,
											'tags' => $keywords
										));

									} else {
										$queries->create('page_descriptions', array(
											'page' => $page['key'],
											'description' => $description,
											'tags' => $keywords
										));
									}

									$page_metadata = $queries->getWhere('page_descriptions', array('page', '=', $page['key']));
								}
							}
						}

						if(count($page_metadata)){
							$description = Output::getClean($page_metadata[0]->description);
							$tags = Output::getClean($page_metadata[0]->tags);
						} else {
							$description = '';
							$tags = '';
						}
						?>
						<strong><?php echo str_replace('{x}', Output::getClean($page['key']), $language->get('admin', 'metadata_page_x')); ?></strong>
						<?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
						<form action="" method="post">
							<div class="form-group">
								<label for="inputDescription"><?php echo $language->get('admin', 'description'); ?></label>
								<textarea class="form-control" name="description" id="inputDescription"><?php echo $description; ?></textarea>
							</div>
							<div class="form-group">
								<label for="inputKeywords"><?php echo $language->get('admin', 'keywords'); ?></label>
								<input type="text" class="form-control" name="keywords" id="inputKeywords" value="<?php echo $tags; ?>" placeholder="<?php echo $language->get('admin' ,'keywords'); ?>">
							</div>
							<div class="form-group">
								<input type="hidden" name="token" value="<?php echo Token::get(); ?>">
								<input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
							</div>
						</form>
					<?php } else { ?>
					<div class="table-responsive">
						<table class="table table-striped">
							<tbody>
							<?php
							$allPages = $pages->returnPages();
							foreach($allPages as $key => $page){
								echo '<tr>';
								echo '<td><a href="' . URL::build('/admin/metadata/', 'id=' . $page['id']) . '">' . Output::getPurified($key) . '</a>';
								echo '</tr>';
							}
							?>
							</tbody>
						</table>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
require(ROOT_PATH . '/modules/Core/pages/admin/footer.php');
require(ROOT_PATH . '/modules/Core/pages/admin/scripts.php');
?>

</body>
</html>