<?php

if (isset($_SESSION['site_initialized']) && $_SESSION['site_initialized'] == true) {
	Redirect::to('?step=admin_account_setup');
	die();
}

if (!isset($_SESSION['database_initialized']) || $_SESSION['database_initialized'] != true) {
	Redirect::to('?step=database_configuration');
	die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$validate = new Validate();
	$validation = $validate->check($_POST, [
		'sitename' => [
            Validate::REQUIRED => true,
            Validate::MIN => 1,
            Validate::MAX => 32,
        ],
		'incoming' => [
            Validate::REQUIRED => true,
            Validate::MIN => 4,
            Validate::MAX => 64,
        ],
		'outgoing' => [
            Validate::REQUIRED => true,
            Validate::MIN => 4,
			Validate::MAX => 64,
        ],
		'language' => [
            Validate::REQUIRED => true,
		]
    ]);

	if (!$validation->passed()) {

		$error = $language['configuration_error'];
        
	} else {

		try {

			$queries = new Queries();
			$queries->create('settings', array(
				'name' => 'sitename',
				'value' => Output::getClean(Input::get('sitename'))
			));

			$cache = new Cache();
			$cache->setCache('sitenamecache');
			$cache->store('sitename', Output::getClean(Input::get('sitename')));

			$queries->create('settings', array(
				'name' => 'incoming_email',
				'value' => Output::getClean(Input::get('incoming'))
			));

			$queries->create('settings', array(
				'name' => 'outgoing_email',
				'value' => Output::getClean(Input::get('outgoing'))
			));

			$_SESSION['default_language'] = Output::getClean(Input::get('language'));

			Redirect::to('?step=site_initialization');
			die();
		} catch (Exception $e) {

			$error = $e->getMessage();
		}
	}
}

?>

<?php if (isset($error)) { ?>
	<div class="ui error message">
		<?php echo $error; ?>
	</div>
<?php } ?>

<form action="" method="post">
	<div class="ui segments">
		<div class="ui secondary segment">
			<h4 class="ui header">
				<?php echo $language['configuration']; ?>
			</h4>
		</div>
		<div class="ui segment">
			<p><?php echo $language['configuration_info']; ?></p>
			<div class="ui centered grid">
				<div class="sixteen wide mobile twelve wide tablet ten wide computer column">
					<div class="ui form">
						<?php create_field('text', $language['site_name'], 'sitename', 'inputSitename', getenv('NAMELESS_SITE_NAME') ?: ''); ?>
						<?php create_field('email', $language['contact_email'], 'incoming', 'contact_email', getenv('NAMELESS_SITE_CONTACT_EMAIL') ?: ''); ?>
						<?php create_field('email', $language['outgoing_email'], 'outgoing', 'outgoing_email', getenv('NAMELESS_SITE_OUTGOING_EMAIL') ?: ''); ?>
						<?php create_field('select', $language['language'], 'language', 'inputLanguage', $installer_language, $languages, true) ?>
					</div>
				</div>
			</div>
</form>
</div>
<div class="ui right aligned secondary segment">
	<button type="submit" class="ui small primary button">
		<?php echo $language['proceed']; ?>
	</button>
</div>
</div>
</form>