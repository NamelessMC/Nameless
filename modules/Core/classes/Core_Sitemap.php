<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Core module Sitemap method
 */
class Core_Sitemap {
	public static function generateSitemap($sitemap = null){
		if(!$sitemap)
			return;

		// Core pages
		$sitemap->addItem(URL::build('/'), 1.0);
		$sitemap->addItem(URL::build('/contact'), 0.6);
		$sitemap->addItem(URL::build('/privacy'));
		$sitemap->addItem(URL::build('/terms'));
		$sitemap->addItem(URL::build('/login'), 0.8);
		$sitemap->addItem(URL::build('/register'));

		$portal = file_get_contents(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . sha1('portal_cache') . '.cache');
		$portal = json_decode($portal);
		$portal = unserialize($portal->portal->data);

		if($portal == 1)
			$sitemap->addItem(URL::build('/home'), 0.9);

		$db = DB::getInstance();

		$users = $db->query('SELECT username FROM nl2_users')->results();

		foreach($users as $user)
			$sitemap->addItem(URL::build('/profile/' . Output::getClean($user->username)));

		$users = null;

		$pages = $db->query('SELECT id, url FROM nl2_custom_pages WHERE sitemap = 1 AND id IN (SELECT page_id FROM nl2_custom_pages_permissions WHERE group_id = 0 AND `view` = 1)')->results();

		foreach($pages as $page)
			$sitemap->addItem(URL::build(Output::getClean($page->url)));

	}
}