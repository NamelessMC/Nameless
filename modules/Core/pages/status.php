<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Status page
 */

$cache->setCache('status_page');
if($cache->isCached('enabled')){
	$status_enabled = $cache->retrieve('enabled');

} else {
	$status_enabled = $queries->getWhere('settings', array('name', '=', 'status_page'));
	if($status_enabled[0]->value == 1)
		$status_enabled = 1;
	else
		$status_enabled = 0;

	$cache->store('enabled', $status_enabled);

}

if(!defined('MINECRAFT') || MINECRAFT !== true || $status_enabled != 1){
	require_once(ROOT_PATH . '/404.php');
	die();
}

define('PAGE', 'status');
?>
<!DOCTYPE html>
<html<?php if(defined('HTML_CLASS')) echo ' class="' . HTML_CLASS . '"'; ?> lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>" <?php if(defined('HTML_RTL') && HTML_RTL === true) echo ' dir="rtl"'; ?>>
	<head>
		<!-- Standard Meta -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

		<!-- Site Properties -->
		<?php
		$title = $language->get('general', 'status');
		require(ROOT_PATH . '/core/templates/header.php');
		?>
	</head>
	<body>
<?php
require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

$servers = $queries->getWhere('mc_servers', array('display', '=', 1));

$smarty->assign(array(
	'STATUS' => $language->get('general', 'status'),
	'IP' => $language->get('general', 'ip'),
	'TABLE_STATUS' => $language->get('general', 'table_status'),
	'DEFAULT_STATUS' => (isset($result) ? $result : null),
	'SERVERS' => $servers,
	'NO_SERVERS' => $language->get('general', 'no_servers'),
	'BUNGEE' => $language->get('general', 'bungee_instance')
));

// Display template
$smarty->display(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/status.tpl');

require(ROOT_PATH . '/core/templates/scripts.php');
?>
	</body>
</html>
