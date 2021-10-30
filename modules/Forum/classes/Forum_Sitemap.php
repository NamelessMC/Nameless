<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Forum module Sitemap method
 */

use SitemapPHP\Sitemap;

class Forum_Sitemap {

    /**
     * Generate sitemap for the Forum.
     *
     * @param Sitemap|null $sitemap Instance of sitemap generator.
     */
    public static function generateSitemap(Sitemap $sitemap = null): void {
        if (!$sitemap)
            return;

        // Forum
        $sitemap->addItem(URL::build('/forum'), 0.9);

        $db = DB::getInstance();

        $forums = $db->selectQuery('SELECT id, forum_title, last_post_date FROM nl2_forums WHERE id IN (SELECT forum_id FROM nl2_forums_permissions WHERE group_id = 0 AND `view` = 1)')->results();

        foreach ($forums as $forum)
            $sitemap->addItem(URL::build('/forum/view/' . $forum->id . '-' . Util::stringToURL($forum->forum_title)), 0.5, 'daily', date('Y-m-d', $forum->last_post_date));

        $forums = null;

        $topics = $db->selectQuery('SELECT id, forum_id, topic_title FROM nl2_topics WHERE deleted = 0 AND forum_id IN (SELECT forum_id FROM nl2_forums_permissions WHERE group_id = 0 AND `view` = 1)')->results();

        foreach ($topics as $topic)
            $sitemap->addItem(URL::build('/forum/topic/' . $topic->id . '-' . Util::stringToURL($topic->topic_title)), 0.5);

        $topics = null;
    }
}
