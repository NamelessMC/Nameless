<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Error page
 */

if(!defined('ERRORHANDLER'))
	die();

$language = new Language('core', LANGUAGE);
$user = new User();
?>

<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>" <?php if(defined('HTML_RTL') && HTML_RTL === true) echo ' dir="rtl"'; ?>>
	<head>
		<meta charset="<?php echo (defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8'); ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="<?php echo $language->get('errors', 'fatal_error') . ' - ' . SITE_NAME; ?>">

		<!-- Page Title -->
		<title><?php echo $language->get('errors', 'fatal_error'); ?> &bull; <?php echo SITE_NAME; ?></title>

		<meta name="author" content="<?php echo SITE_NAME; ?>">

		<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/css/custom.css">
		<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/css/font-awesome.min.css">

	</head>
	<body>
		<br /><br /><br />
		<div class="container">
			<div class="row">
				<div class="col-md-6 offset-md-3">
					<div class="jumbotron">
						<div style="text-align:center">
							<h2><?php echo $language->get('errors', 'fatal_error_title'); ?></h2>
							<?php
							if($user->isLoggedIn() && $user->hasPermission('admincp.errors')){
							?>
							<h4><?php echo $language->get('errors', 'fatal_error_message_admin'); ?></h4>
							<div class="card card-default">
								<div class="card-body">
									<pre style="white-space:pre-wrap;"><?php echo Output::getClean($errstr); ?></pre>
                </div>
              </div>
              <?php
                  echo '<div style="overflow-x: scroll;">' . str_replace('{x}', Output::getClean($errfile), $language->get('errors', 'in_file')) . '</div>' . str_replace('{x}', Output::getClean($errline), $language->get('errors', 'on_line')) . '<hr />';
							} else {
							?>
							<h4><?php echo $language->get('errors', 'fatal_error_message_user'); ?></h4>
							<?php
							}
							?>
							<div class="btn-group" role="group" aria-label="...">
								<button href="#" class="btn btn-primary btn-lg" onclick="javascript:history.go(-1)"><?php echo $language->get('general', 'back'); ?></button>
								<a href="<?php echo URL::build('/'); ?>" class="btn btn-success btn-lg"><?php echo $language->get('general', 'home'); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
