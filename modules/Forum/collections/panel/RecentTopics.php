<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Recent topics dashboard collection item
 */
class RecentTopicsItem extends CollectionItemBase {

    private $_smarty, 
            $_language, 
            $_topics;

    public function __construct($smarty, $language, $cache, $topics) {
        $cache->setCache('dashboard_stats_collection');
        if ($cache->isCached('recent_topics')) {
            $from_cache = $cache->retrieve('recent_topics');
            if (isset($from_cache['order']))
                $order = $from_cache['order'];
            else
                $order = 3;

            if (isset($from_cache['enabled']))
                $enabled = $from_cache['enabled'];
            else
                $enabled = 1;
        } else {
            $order = 3;
            $enabled = 1;
        }

        parent::__construct($order, $enabled);

        $this->_smarty = $smarty;
        $this->_language = $language;
        $this->_topics = $topics;
    }

    public function getContent() {
        $this->_smarty->assign(array(
            'ICON' => $this->_language->get('forum', 'recent_topics_statistic_icon'),
            'TITLE' => $this->_language->get('forum', 'recent_topics'),
            'VALUE' => $this->_topics
        ));

        return $this->_smarty->fetch('collections/dashboard_stats/recent_topics.tpl');
    }
}
