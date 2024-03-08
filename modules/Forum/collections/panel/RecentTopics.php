<?php

/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.2.0
 *
 *  Licence: MIT
 *
 *  Recent topics dashboard collection item
 */

class RecentTopicsItem extends CollectionItemBase {

    private TemplateEngine $_engine;
    private Language $_language;
    private int $_topics;

    public function __construct(TemplateEngine $engine, Language $language, Cache $cache, int $topics) {
        $cache->setCache('dashboard_stats_collection');
        if ($cache->isCached('recent_topics')) {
            $from_cache = $cache->retrieve('recent_topics');
            $order = $from_cache['order'] ?? 3;

            $enabled = $from_cache['enabled'] ?? 1;
        } else {
            $order = 3;
            $enabled = 1;
        }

        parent::__construct($order, $enabled);

        $this->_engine = $engine;
        $this->_language = $language;
        $this->_topics = $topics;
    }

    public function getContent(): string {
        $this->_engine->addVariables([
            'TITLE' => $this->_language->get('forum', 'recent_topics'),
            'VALUE' => $this->_topics
        ]);

        return $this->_engine->fetch('collections/dashboard_stats/recent_topics');
    }
}
