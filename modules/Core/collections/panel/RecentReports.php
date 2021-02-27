<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Recent reports dashboard collection item
 */

class RecentReportsItem extends CollectionItemBase {

    private $_smarty, 
            $_language, 
            $_cache;

    public function __construct($smarty, $language, $cache) {
        $cache->setCache('dashboard_main_items_collection');
        if ($cache->isCached('recent_reports')) {
            $from_cache = $cache->retrieve('recent_reports');
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
        $this->_cache = $cache;
    }

    public function getContent() {
        // Get recent reports
        $timeago = new Timeago(TIMEZONE);

        $this->_cache->setCache('dashboard_main_items_collection');

        if ($this->_cache->isCached('recent_reports_data')) {
            $data = $this->_cache->retrieve('recent_reports_data');
        } else {
            $queries = new Queries();
            $query = $queries->orderWhere('reports', 'status = 0', 'date_reported', 'DESC');
            $data = array();

            if (count($query)) {
                $users = array();
                $i = 0;

                foreach ($query as $item) {
                    if (array_key_exists($item->reporter_id, $users)) {
                        $reporter_user = $users[$item->reporter_id];
                    } else {
                        $reporter_user = new User($item->reporter_id);
                        if (!$reporter_user->data())
                            continue;
                        $users[$item->reporter_id] = $reporter_user;
                    }

                    if (array_key_exists($item->reported_id, $users)) {
                        $reported_user = $users[$item->reported_id];
                    } else {
                        $reported_user = new User($item->reported_id);
                        if (!$reported_user->data())
                            continue;
                        $users[$item->reported_id] = $reported_user;
                    }

                    $data[] = array(
                        'url' => URL::build('/panel/users/reports/', 'id=' . Output::getClean($item->id)),
                        'reporter_username' => $reporter_user->getDisplayname(true),
                        'reporter_nickname' => $reporter_user->getDisplayname(),
                        'reporter_style' => $reporter_user->getGroupClass(),
                        'reporter_avatar' => $reporter_user->getAvatar(),
                        'reporter_uuid' => Output::getClean($reporter_user->data()->uuid),
                        'reporter_profile' => URL::build('/panel/user/' . Output::getClean($reporter_user->data()->id) . '-' . Output::getClean($reporter_user->data()->username)),
                        'reported_username' => $reported_user->getDisplayname(true),
                        'reported_nickname' => $reported_user->getDisplayname(),
                        'reported_style' => $reported_user->getGroupClass(),
                        'reported_avatar' => $reported_user->getAvatar(),
                        'reported_uuid' => Output::getClean($reported_user->data()->uuid),
                        'reported_profile' => URL::build('/panel/user/' . Output::getClean($reported_user->data()->id) . '-' . Output::getClean($reported_user->data()->username)),
                        'time' => $timeago->inWords($item->date_reported, $this->_language->getTimeLanguage()),
                        'time_full' => date('d M Y, H:i', strtotime($item->date_reported)),
                        'type' => $item->type,
                        'reason' => Output::getPurified($item->report_reason),
                        'link' => Output::getClean($item->link),
                        'ig_reported_mcname' => ($item->reported_mcname ? Output::getClean($item->reported_mcname) : ''),
                        'ig_reported_uuid' => ($item->reported_uuid ? Output::getClean($item->reported_uuid) : '')
                    );

                    if (++$i == 5)
                        break;
                }
            }

            $this->_cache->store('recent_reports_data', $data, 60);
        }

        $this->_smarty->assign(array(
            'RECENT_REPORTS' => $this->_language->get('moderator', 'recent_reports'),
            'REPORTS' => $data,
            'NO_REPORTS' => $this->_language->get('moderator', 'no_open_reports'),
            'CREATED' => $this->_language->get('moderator', 'created'),
            'REPORTED_BY' => $this->_language->get('moderator', 'reported_by'),
            'REASON' => $this->_language->get('moderator', 'reason:'),
            'WEBSITE' => $this->_language->get('moderator', 'website'),
            'INGAME' => $this->_language->get('moderator', 'ingame'),
            'VIEW' => $this->_language->get('general', 'view')
        ));

        return $this->_smarty->fetch('collections/dashboard_items/recent_reports.tpl');
    }

    public function getWidth() {
        return 0.33; // 1/3 width
    }
}
