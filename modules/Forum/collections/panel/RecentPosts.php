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
class RecentPostsItem extends CollectionItemBase {

    private Smarty $_smarty;
    private Language $_language;
    private int $_posts;

    /**
     * @param Smarty $smarty
     * @param Language $language
     * @param Cache $cache
     * @param int $posts
     */
    public function __construct(Smarty $smarty, Language $language, Cache $cache, int $posts) {
        $cache->setCacheName('dashboard_stats_collection');
        if ($cache->hasCashedData('recent_posts')) {
            $from_cache = $cache->retrieve('recent_posts');
            $order = $from_cache['order'] ?? 4;

            $enabled = $from_cache['enabled'] ?? 1;
        } else {
            $order = 4;
            $enabled = 1;
        }

        parent::__construct($order, $enabled);

        $this->_smarty = $smarty;
        $this->_language = $language;
        $this->_posts = $posts;
    }

    /**
     *
     * @return string
     * @throws SmartyException
     */
    public function getContent(): string {
        $this->_smarty->assign([
            'TITLE' => $this->_language->get('forum', 'recent_posts'),
            'VALUE' => $this->_posts
        ]);

        return $this->_smarty->fetch('collections/dashboard_stats/recent_posts.tpl');
    }
}
