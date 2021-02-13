<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Total users dashboard collection item
 */

class TotalUsersItem extends CollectionItemBase {

    private $_smarty, 
            $_language;

    public function __construct($smarty, $language, $cache) {
        $cache->setCache('dashboard_stats_collection');
        if ($cache->isCached('total_users')) {
            $from_cache = $cache->retrieve('total_users');
            if (isset($from_cache['order']))
                $order = $from_cache['order'];
            else
                $order = 1;

            if (isset($from_cache['enabled']))
                $enabled = $from_cache['enabled'];
            else
                $enabled = 1;
        } else {
            $order = 1;
            $enabled = 1;
        }

        parent::__construct($order, $enabled);

        $this->_smarty = $smarty;
        $this->_language = $language;
    }

    public function getContent() {
        // Get the number of total users
        $queries = new Queries();
        $users_query = $queries->getWhere('users', array('id', '<>', 0));

        $this->_smarty->assign(array(
            'ICON' => $this->_language->get('admin', 'total_users_statistic_icon'),
            'TITLE' => $this->_language->get('admin', 'total_users'),
            'VALUE' => count($users_query)
        ));

        return $this->_smarty->fetch('collections/dashboard_stats/total_users.tpl');
    }
}
