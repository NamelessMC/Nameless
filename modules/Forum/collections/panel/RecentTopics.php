<?php
declare(strict_types=1);

/**
 * Recent posts dashboard collection item
 *
 * @package Modules\Forum
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class RecentTopicsItem extends CollectionItemBase {

    private Smarty $_smarty;
    private Language $_language;
    private int $_topics;

    /**
     * @param Smarty $smarty
     * @param Language $language
     * @param Cache $cache
     * @param int $topics
     */
    public function __construct(Smarty $smarty, Language $language, Cache $cache, int $topics) {
        $cache->setCacheName('dashboard_stats_collection');
        if ($cache->hasCashedData('recent_topics')) {
            $from_cache = $cache->retrieve('recent_topics');
            $order = $from_cache['order'] ?? 3;

            $enabled = $from_cache['enabled'] ?? 1;
        } else {
            $order = 3;
            $enabled = 1;
        }

        parent::__construct($order, $enabled);

        $this->_smarty = $smarty;
        $this->_language = $language;
        $this->_topics = $topics;
    }

    /**
     *
     * @return string
     * @throws SmartyException
     */
    public function getContent(): string {
        $this->_smarty->assign([
            'TITLE' => $this->_language->get('forum', 'recent_topics'),
            'VALUE' => $this->_topics
        ]);

        return $this->_smarty->fetch('collections/dashboard_stats/recent_topics.tpl');
    }
}
