<?php

use SitemapPHP\Sitemap;

/**
 * Forum sitemap class
 *
 * @package Modules\Forum
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class Forum_Sitemap {

    /**
     * Generate sitemap for the Forum.
     *
     * @param Sitemap $sitemap Instance of sitemap generator.
     */
    public static function generateSitemap(Sitemap $sitemap): void {

        // Forum
        $sitemap->addItem(URL::build('/forum'), 0.9);

        $db = DB::getInstance();

        $forums = $db->query('SELECT id, forum_title, last_post_date FROM nl2_forums WHERE id IN (SELECT forum_id FROM nl2_forums_permissions WHERE group_id = 0 AND `view` = 1)')->results();

        foreach ($forums as $forum) {
            $sitemap->addItem(URL::build('/forum/view/' . urlencode($forum->id) . '-' . urlencode($forum->forum_title)), 0.5, 'daily', date('Y-m-d', $forum->last_post_date));
        }

        $forums = null;

        $topics = $db->query('SELECT id, forum_id, topic_title FROM nl2_topics WHERE deleted = 0 AND forum_id IN (SELECT forum_id FROM nl2_forums_permissions WHERE group_id = 0 AND `view` = 1)')->results();

        foreach ($topics as $topic) {
            $sitemap->addItem(URL::build('/forum/topic/' . urlencode($topic->id) . '-' . urlencode($topic->topic_title)), 0.5);
        }

        $topics = null;
    }
}
