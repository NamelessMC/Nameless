<?php
require(__DIR__ . '/includes/functions.php');

if (isset($_GET['do'])) {
	$_SESSION['action'] = ($_GET['do'] == 'upgrade' ? 'upgrade' : 'install');

	Redirect::to('?step=requirements_validation');
	die();

} else if (isset($_GET['step'])) {
	$step = strtolower($_GET['step']);
	if (!file_exists(__DIR__ . '/steps/' . $step . '.php')) {
		$error = 'Unknown step.';
	}

}

if (isset($step) && $step == 'ajax_initialise') {
	require(__DIR__ . '/steps/' . $step . '.php');
	die();
}

require(__DIR__ . '/includes/header.php');
?>

<div class="main-content">
	<div class="ui container">
		<div class="ui stackable grid">
			<div class="five wide computer only column">
				<div class="ui fluid vertical steps">
					<?php
						create_step($language['step_home'], 'home icon', array('welcome'));
						create_step($language['step_requirements'], 'tasks icon', array('requirements_validation'));
						create_step($language['step_general_config'], 'cog icon', array('general_configuration'));
						create_step($language['step_database_config'], 'server icon', array('database_configuration', 'database_initialization', 'upgrade', 'upgrade_perform'));
						create_step($language['step_site_config'], 'globe icon', array('site_configuration', 'site_initialization'));
						create_step($language['step_admin_account'], 'user icon', array('admin_account_setup'));
						create_step($language['step_conversion'], 'exchange icon', array('conversion'));
						create_step($language['step_finish'], 'check icon', array('finish'));
					?>
				</div>
			</div>
			<div class="sixteen wide tablet eleven wide computer column">
				<?php if (!isset($step)) { ?>
					<div class="ui red message">
						<?php echo $language['pre-release_warning']; ?>
					</div>
					<div class="ui segments">
						<div class="ui secondary segment">
							<h4 class="ui header">
								<?php echo $language['installer_welcome']; ?>
							</h4>
						</div>
						<div class="ui segment">
							<p><?php echo $language['installer_information']; ?></p>
							<p><?php echo $language['terms_and_conditions']; ?></p>
							<div class="ui message"><?php echo $nameless_terms; ?></div>
							<div class="ui divider"></div>
							<p><?php echo $language['new_installation_question']; ?></p>
						</div>
						<div class="ui right aligned secondary segment">
							<a href="?do=upgrade" class="ui small button">
								<?php echo $language['upgrading_from_v1']; ?>
							</a>
							<a href="?do=install" class="ui small primary button">
								<?php echo $language['new_installation']; ?>
							</a>
						</div>
					</div>
				<?php } else if (isset($error)) { ?>
					<div class="ui red message">
						<?php echo $error; ?>
					</div>
				<?php
				} else {
					if (!isset($_SESSION['action'])) {
						?>
						<div class="ui red message">
							<?php echo $language['session_doesnt_exist']; ?>
						</div>
						<?php
					} else
						require(__DIR__ . '/steps/' . $step . '.php');
				}
				?>
			</div>
		</div>
	</div>
</div>

<?php require(__DIR__ . '/includes/footer.php'); ?>
