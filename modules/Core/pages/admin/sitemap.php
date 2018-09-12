<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Admin sitemap page
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
		} else if(!$user->hasPermission('admincp.sitemap')){
			// Can't view this page
			require(ROOT_PATH . '/404.php');
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

$page = 'admin';
$admin_page = 'sitemap';

$timeago = new Timeago(TIMEZONE);

if(Input::exists()){
	$errors = array();

	if(Token::check(Input::get('token'))){
		require_once(ROOT_PATH . '/core/includes/sitemapphp/Sitemap.php');
		$sitemap = new SitemapPHP\Sitemap(rtrim(Util::getSelfURL(), '/'));
		$sitemap->setPath(ROOT_PATH . '/cache/sitemaps/');

		$methods = $pages->getSitemapMethods();

		if(count($methods)){
			foreach($methods as $file => $method){
				if(file_exists($file)){
					require_once($file);

					call_user_func($method, $sitemap);

				} else
					$errors[] = str_replace('{x}', Output::getClean($file), $language->get('admin', 'unable_to_load_sitemap_file_x'));
			}
		}

		$sitemap->createSitemapIndex(rtrim(Util::getSelfURL(), '/') . URL::build('/cache/sitemaps/'));

		$cache->setCache('sitemap_cache');
		$cache->store('updated', date('d M Y, H:i'));

		$success = $language->get('admin', 'sitemap_generated');

	} else {
		$errors[] = $language->get('general', 'invalid_token');
	}
}
?>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>" <?php if(defined('HTML_RTL') && HTML_RTL === true) echo ' dir="rtl"'; ?>>
<head>
	<!-- Standard Meta -->
	<meta charset="<?php echo (defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<?php
	$title = $language->get('admin', 'admin_cp');
	require(ROOT_PATH . '/core/templates/admin_header.php');
	?>

</head>
<body>
<?php require(ROOT_PATH . '/modules/Core/pages/admin/navbar.php'); ?>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<?php require(ROOT_PATH . '/modules/Core/pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-block">
					<h3><?php echo $language->get('admin', 'sitemap'); ?></h3>
					<hr />
					<?php
					if(isset($errors) && count($errors)){
						echo '<div class="alert alert-danger">';
						foreach($errors as $error){
							echo $error . '<br />';
						}
						echo '</div>';
					}
					if(isset($success)){
						echo '<div class="alert alert-success">' . $success . '</div>';
					}

					if(!is_dir(ROOT_PATH . '/cache/sitemaps')){
						if(!is_writable(ROOT_PATH . '/cache')){
							echo '<div class="alert alert-danger">' . $language->get('admin', 'cache_not_writable') . '</div>';

						} else {
							mkdir(ROOT_PATH . '/cache/sitemaps');
							file_put_contents(ROOT_PATH . '/cache/sitemaps/.htaccess', 'Allow from all');
						}
					}

					if(!is_writable(ROOT_PATH . '/cache/sitemaps')){
						echo '<div class="alert alert-danger">' . $language->get('admin', 'sitemap_not_writable') . '</div>';

					} else {
						if(file_exists(ROOT_PATH . '/cache/sitemaps/sitemap-index.xml')){
							$cache->setCache('sitemap_cache');
							if($cache->isCached('updated')){
								$updated = $cache->retrieve('updated');
								$updated = $timeago->inWords($updated, $language->getTimeLanguage());
							} else
								$updated = $language->get('admin', 'unknown');

							echo '<div class="alert alert-info"><p>' . str_replace('{x}', $updated, $language->get('admin', 'sitemap_last_generated_x')) . '</p><a class="btn btn-primary" href="' . (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/cache/sitemaps/sitemap-index.xml' . '" download>' . $language->get('admin', 'download_sitemap') . '</a></div>';

						} else {
							echo '<div class="alert alert-info">' . $language->get('admin', 'sitemap_not_generated_yet') . '</div>';
						}

						?>
						<form action="" method="post">
							<div class="input-group">
								<input type="hidden" name="token" value="<?php echo Token::get(); ?>">
								<input type="submit" class="btn btn-primary" value="<?php echo $language->get('admin', 'generate_sitemap'); ?>">
							</div>
						</form>


						<?php
					}

					?>

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
