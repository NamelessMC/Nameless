<?php

if (!isset($_SESSION['action'])) {
	Redirect::to('install.php');
	die();
}

unset($_SESSION['requirements_validated']);

?>

<div class="ui segments">
	<div class="ui secondary segment">
		<h4 class="ui header">
			<?php echo rtrim($language['requirements'], ':'); ?>
		</h4>
	</div>
	<div class="ui segment">
		<div class="ui centered grid">
			<div class="sixteen wide mobile eight wide tablet seven wide computer column">
				<?php
					validate_requirement('PHP 5.4+', version_compare(phpversion(), '5.4', '>='));
					validate_requirement('PHP MySQL', extension_loaded('mysql') || extension_loaded('mysqlnd'));
					validate_requirement('PHP PDO', extension_loaded('PDO'));
					validate_requirement('PHP XML', extension_loaded('xml'));
					validate_requirement('PHP MBString', extension_loaded('mbstring'));
					validate_requirement('PHP GD', extension_loaded('gd'));
					validate_requirement('PHP cURL', function_exists('curl_version'));
					validate_requirement('PHP exif_imagetype', function_exists('exif_imagetype'));
					validate_requirement('PHP JSON', function_exists('json_decode'));
				?>
			</div>
			<div class="sixteen wide mobile eight wide tablet eight wide computer column">
				<?php
					validate_requirement('Core Writable <span class="ui basic label">/core</span>', is_writable('core'));
					validate_requirement('Core Config Writable <span class="ui basic label">/core/config.php</span>', is_writable('core/config.php'));
					validate_requirement('Core Email Writable <span class="ui basic label">/core/email.php</span>', is_writable('core/email.php'));
					validate_requirement('Cache Writable <span class="ui basic label">/cache</span>', is_writable('cache'));
					validate_requirement('Template Cache Writable <span class="ui basic label">/cache/templates_c</span>', is_writable('cache/templates_c'));
				?>
			</div>
		</div>
	</div>
	<?php if (isset($_SESSION['requirements_validated']) && $_SESSION['requirements_validated'] == true) { ?>
		<div class="ui right aligned secondary segment">
			<a href="?step=general_configuration" class="ui small primary button">
				<?php echo $language['proceed']; ?>
			</a>
		</div>
	<?php } else { ?>
		<div class="ui inverted red segment">
			<i class="exclamation circle icon"></i>
			<?php echo $language['requirements_error']; ?>
		</div>
	<?php } ?>
</div>