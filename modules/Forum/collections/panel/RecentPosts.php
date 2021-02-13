<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Recent posts dashboard collection item
 */

class RecentPostsItem extends CollectionItemBase {

    private $_smarty, 
            $_language, 
            $_posts;

    public function __construct($smarty, $language, $cache, $posts) {
        $cache->setCache('dashboard_stats_collection');
        if ($cache->isCached('recent_posts')) {
            $from_cache = $cache->retrieve('recent_posts');
            if (isset($from_cache['order']))
                $order = $from_cache['order'];
            else
                $order = 4;

            if (isset($from_cache['enabled']))
                $enabled = $from_cache['enabled'];
            else
                $enabled = 1;
        } else {
            $order = 4;
            $enabled = 1;
        }

        parent::__construct($order, $enabled);

        $this->_smarty = $smarty;
        $this->_language = $language;
        $this->_posts = $posts;
    }

    public function getContent() {
        $this->_smarty->assign(array(
            'ICON' => $this->_language->get('forum', 'recent_posts_statistic_icon'),
            'TITLE' => $this->_language->get('forum', 'recent_posts'),
            'VALUE' => $this->_posts
        ));

        return $this->_smarty->fetch('collections/dashboard_stats/recent_posts.tpl');
    }
}
