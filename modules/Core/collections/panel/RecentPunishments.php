<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Recent punishments dashboard collection item
 */

class RecentPunishmentsItem extends CollectionItemBase {

    private $_smarty, 
            $_language, 
            $_cache;

    public function __construct($smarty, $language, $cache) {
        $cache->setCache('dashboard_main_items_collection');
        if ($cache->isCached('recent_punishments')) {
            $from_cache = $cache->retrieve('recent_punishments');
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
        $this->_cache = $cache;
    }

    public function getContent() {
        // Get recent punishments
        $timeago = new Timeago(TIMEZONE);

        $this->_cache->setCache('dashboard_main_items_collection');

        if ($this->_cache->isCached('recent_punishments_data')) {
            $data = $this->_cache->retrieve('recent_punishments_data');
        } else {
            $queries = new Queries();
            $query = $queries->orderAll('infractions', 'infraction_date', 'DESC');
            $data = array();

            if (count($query)) {
                $users = array();
                $i = 0;

                foreach ($query as $item) {
                    if (array_key_exists($item->punished, $users)) {
                        $punished_user = $users[$item->punished];
                    } else {
                        $punished_user = new User($item->punished);
                        if (!$punished_user->data())
                            continue;
                        $users[$item->punished] = $punished_user;
                    }

                    if (array_key_exists($item->staff, $users)) {
                        $staff_user = $users[$item->staff];
                    } else {
                        $staff_user = new User($item->staff);
                        if (!$staff_user->data())
                            continue;
                        $users[$item->staff] = $staff_user;
                    }

                    $revoked_by_user = null;
                    if ($item->revoked) {
                        if (array_key_exists($item->revoked_by, $users)) {
                            $revoked_by_user = $users[$item->revoked_by_user];
                        } else {
                            $revoked_by_user = new User($item->revoked_by);
                            if (!$revoked_by_user->data())
                                continue;
                            $users[$item->revoked_by] = $revoked_by_user;
                        }
                    }

                    $data[] = array(
                        'url' => URL::build('/panel/users/punishments/', 'user=' . Output::getClean($punished_user->data()->id)),
                        'punished_username' => $punished_user->getDisplayname(true),
                        'punished_nickname' => $punished_user->getDisplayname(),
                        'punished_style' => $punished_user->getGroupClass(),
                        'punished_avatar' => $punished_user->getAvatar(),
                        'punished_uuid' => Output::getClean($punished_user->data()->uuid),
                        'punished_profile' => URL::build('/panel/user/' . Output::getClean($punished_user->data()->id) . '-' . Output::getClean($punished_user->data()->username)),
                        'staff_username' => $staff_user->getDisplayname(true),
                        'staff_nickname' => $staff_user->getDisplayname(),
                        'staff_style' => $staff_user->getGroupClass(),
                        'staff_avatar' => $staff_user->getAvatar(),
                        'staff_uuid' => Output::getClean($staff_user->data()->uuid),
                        'staff_profile' => URL::build('/panel/user/' . Output::getClean($staff_user->data()->id) . '-' . Output::getClean($staff_user->data()->username)),
                        'time' => ($item->created ? $timeago->inWords(date('Y-m-d H:i:s', $item->created), $this->_language->getTimeLanguage()) : $timeago->inWords($item->infraction_date, $this->_language->getTimeLanguage())),
                        'time_full' => ($item->created ? date('d M Y, H:i', $item->created) : date('d M Y, H:i', strtotime($item->infraction_date))),
                        'type' => $item->type,
                        'reason' => Output::getPurified($item->reason),
                        'acknowledged' => $item->acknowledged,
                        'revoked' => $item->revoked,
                        'revoked_by_username' => ($revoked_by_user ? $revoked_by_user->getDisplayname(true) : ''),
                        'revoked_by_nickname' => ($revoked_by_user ? $revoked_by_user->getDisplayname() : ''),
                        'revoked_by_style' => ($revoked_by_user ? $revoked_by_user->getGroupClass() : ''),
                        'revoked_by_avatar' => ($revoked_by_user ? $revoked_by_user->getAvatar() : ''),
                        'revoked_by_uuid' => ($revoked_by_user ? Output::getClean($revoked_by_user->uuid) : ''),
                        'revoked_by_profile' => ($revoked_by_user ? URL::build('/panel/user/' . Output::getClean($revoked_by_user->data()->id) . '-' . Output::getClean($revoked_by_user->data()->username)) : ''),
                        'revoked_at' => $timeago->inWords(date('Y-m-d H:i:s', $item->revoked_at), $this->_language->getTimeLanguage())
                    );

                    if (++$i == 5)
                        break;
                }
            }

            $this->_cache->store('recent_punishments_data', $data, 60);
        }

        $this->_smarty->assign(array(
            'RECENT_PUNISHMENTS' => $this->_language->get('moderator', 'recent_punishments'),
            'PUNISHMENTS' => $data,
            'NO_PUNISHMENTS' => $this->_language->get('moderator', 'no_punishments_found'),
            'BAN' => $this->_language->get('moderator', 'ban'),
            'IP_BAN' => $this->_language->get('moderator', 'ip_ban'),
            'WARNING' => $this->_language->get('moderator', 'warning'),
            'CREATED' => $this->_language->get('moderator', 'created'),
            'STAFF' => $this->_language->get('moderator', 'staff:'),
            'REASON' => $this->_language->get('moderator', 'reason:'),
            'REVOKED' => $this->_language->get('moderator', 'revoked'),
            'VIEW' => $this->_language->get('general', 'view')
        ));

        return $this->_smarty->fetch('collections/dashboard_items/recent_punishments.tpl');
    }

    public function getWidth() {
        return 0.33; // 1/3 width
    }
}
