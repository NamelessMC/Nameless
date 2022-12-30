<?php

/**
 * Total users dashboard collection item
 *
 * @package Modules\Core\Collections
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class TotalUsersItem extends CollectionItemBase {

    private Smarty $_smarty;
    private Language $_language;

    /**
     * @param Smarty $smarty
     * @param Language $language
     * @param Cache $cache
     */
    public function __construct(Smarty $smarty, Language $language, Cache $cache) {
        $cache->setCache('dashboard_stats_collection');
        if ($cache->isCached('total_users')) {
            $from_cache = $cache->retrieve('total_users');
            $order = $from_cache['order'] ?? 1;

            $enabled = $from_cache['enabled'] ?? 1;
        } else {
            $order = 1;
            $enabled = 1;
        }

        parent::__construct($order, $enabled);

        $this->_smarty = $smarty;
        $this->_language = $language;
    }

    /**
     *
     * @return string
     * @throws SmartyException
     */
    public function getContent(): string {
        // Get the number of total users
        $users_query = DB::getInstance()->query('SELECT COUNT(*) AS c FROM nl2_users')->first()->c;

        $this->_smarty->assign([
            'TITLE' => $this->_language->get('admin', 'total_users'),
            'VALUE' => $users_query
        ]);

        return $this->_smarty->fetch('collections/dashboard_stats/total_users.tpl');
    }
}
