<?php
if (!isset($_SESSION['requirements_validated']) || $_SESSION['requirements_validated'] != true) {
	Redirect::to('?step=requirements_validation');
	die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$_SESSION['hostname'] = (isset($_POST['hostname']) ? $_POST['hostname'] : $_SERVER['SERVER_NAME']);
	$_SESSION['install_path'] = (isset($_POST['install_path']) ? $_POST['install_path'] : '');
	$_SESSION['friendly_urls'] = (isset($_POST['friendly']) ? $_POST['friendly'] : false);

	Redirect::to('?step=database_configuration');
	die();

}
?>

<form action="" method="post">
	<div class="ui segments">
		<div class="ui secondary segment">
			<h4 class="ui header">
				<?php echo $language['general_configuration']; ?>
			</h4>
		</div>
		<div class="ui segment">
			<div class="ui centered grid">
				<div class="sixteen wide mobile twelve wide tablet ten wide computer column">
					<div class="ui form">
						<div <?php if (getenv("NAMELESS_HOSTNAME_HIDE") !== false) echo 'style="display: none"' ?>>
							<?php create_field('text', $language['host'], 'hostname', 'inputHostname', getenv('NAMELESS_HOSTNAME') ?: Output::getClean($_SERVER['SERVER_NAME'])); ?>
							<p><?php echo $language['host_help']; ?></p>
							<div class="ui divider"></div>
						</div>

						<div <?php if (getenv("NAMELESS_PATH_HIDE") !== false) echo 'style="display: none"' ?>>
							<?php create_field('text', $language['nameless_path'], 'install_path', 'inputPath', getenv('NAMELESS_PATH') ?: Output::getClean($install_path)); ?>
							<p><?php echo $language['nameless_path_info']; ?></p>
							<div class="ui divider"></div>
						</div>

						<div <?php if (getenv("NAMELESS_FRIENDLY_URLS_HIDE") !== false) echo 'style="display: none"' ?>>
							<?php create_field('select', $language['friendly_urls'], 'friendly', 'inputFriendly', getenv('NAMELESS_FRIENDLY_URLS') ?: 'false', array(
								'true' => $language['enabled'],
								'false' => $language['disabled'],
							)); ?>
							<p><?php echo $language['friendly_urls_info']; ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="ui right aligned secondary segment">
			<button type="submit" class="ui small primary button">
				<?php echo $language['proceed']; ?>
			</button>
		</div>
	</div>
</form>
