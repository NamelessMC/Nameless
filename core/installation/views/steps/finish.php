<?php

if (!isset($_SESSION['admin_setup']) || $_SESSION['admin_setup'] != true) {
	Redirect::to('?step=admin_account_setup');
	die();
}

try {

	if (!is_writable('core/config.php')) {
		$error = $language['config_not_writable'];
	} else {
		file_put_contents('core/config.php', PHP_EOL . '$CONFIG[\'installed\'] = true;', FILE_APPEND);
	}

	unset($_SESSION['admin_setup']);
	unset($_SESSION['database_initialized']);
	unset($_SESSION['site_initialized']);

} catch (Exception $e) {

	$error = $e->getMessage();

}

?>

<?php if (isset($error)) { ?>

	<div class="ui error message">
		<?php echo $error; ?> <a href="?step=finish"><?php echo $language['reload_page']; ?></a>
	</div>

<?php } else { ?>

	<div class="ui segments">
		<div class="ui secondary segment">
			<h4 class="ui header">
				<?php echo $language['finish']; ?>
			</h4>
		</div>
		<div class="ui segment">
			<p><?php echo $language['finish_message']; ?></p>
			<p><?php echo $language['support_message']; ?></p>
		</div>
		<div class="ui secondary segment">
			<h4 class="ui header">
				<?php echo $language['credits']; ?>
			</h4>
		</div>
		<div class="ui segment">
			<p><?php echo $language['credits_message']; ?></p>
		</div>
		<div class="ui secondary right aligned segment">
			<a href="index.php?route=/panel" class="ui small primary button">
				<?php echo $language['finish']; ?>
			</a>
		</div>
	</div>

<?php } ?>