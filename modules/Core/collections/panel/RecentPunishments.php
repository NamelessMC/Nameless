<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Recent punishments dashboard collection item
 */

class RecentPunishmentsItem extends CollectionItemBase {

    private Smarty $_smarty;
    private Language $_language;
    private Cache $_cache;

    public function __construct(Smarty $smarty, Language $language, Cache $cache) {
        $cache->setCache('dashboard_main_items_collection');
        if ($cache->isCached('recent_punishments')) {
            $from_cache = $cache->retrieve('recent_punishments');
            $order = $from_cache['order'] ?? 1;

            $enabled = $from_cache['enabled'] ?? 1;
        } else {
            $order = 1;
            $enabled = 1;
        }

        parent::__construct($order, $enabled);

        $this->_smarty = $smarty;
        $this->_language = $language;
        $this->_cache = $cache;
    }

    public function getContent(): string {
        // Get recent punishments
        $timeago = new TimeAgo(TIMEZONE);

        $this->_cache->setCache('dashboard_main_items_collection');

        if ($this->_cache->isCached('recent_punishments_data')) {
            $data = $this->_cache->retrieve('recent_punishments_data');
        } else {
            $query = DB::getInstance()->query('SELECT * FROM nl2_infractions ORDER BY `infraction_date` DESC LIMIT 5');
            $data = [];

            if ($query->count()) {
                $users = [];

                foreach ($query->results() as $item) {
                    if (array_key_exists($item->punished, $users)) {
                        $punished_user = $users[$item->punished];
                    } else {
                        $punished_user = new User($item->punished);
                        if (!$punished_user->exists()) {
                            continue;
                        }
                        $users[$item->punished] = $punished_user;
                    }

                    if (array_key_exists($item->staff, $users)) {
                        $staff_user = $users[$item->staff];
                    } else {
                        $staff_user = new User($item->staff);
                        if (!$staff_user->exists()) {
                            continue;
                        }
                        $users[$item->staff] = $staff_user;
                    }

                    $revoked_by_user = null;
                    if ($item->revoked) {
                        if (array_key_exists($item->revoked_by, $users)) {
                            $revoked_by_user = $users[$item->revoked_by_user];
                        } else {
                            $revoked_by_user = new User($item->revoked_by);
                            if (!$revoked_by_user->exists()) {
                                continue;
                            }
                            $users[$item->revoked_by] = $revoked_by_user;
                        }
                    }

                    $data[] = [
                        'url' => URL::build('/panel/users/punishments/', 'user=' . urlencode($punished_user->data()->id)),
                        'punished_username' => $punished_user->getDisplayname(true),
                        'punished_nickname' => $punished_user->getDisplayname(),
                        'punished_style' => $punished_user->getGroupStyle(),
                        'punished_avatar' => $punished_user->getAvatar(),
                        'punished_profile' => URL::build('/panel/user/' . urlencode($punished_user->data()->id) . '-' . urlencode($punished_user->data()->username)),
                        'staff_username' => $staff_user->getDisplayname(true),
                        'staff_nickname' => $staff_user->getDisplayname(),
                        'staff_style' => $staff_user->getGroupStyle(),
                        'staff_avatar' => $staff_user->getAvatar(),
                        'staff_profile' => URL::build('/panel/user/' . urlencode($staff_user->data()->id) . '-' . urlencode($staff_user->data()->username)),
                        'time' => ($item->created ? $timeago->inWords($item->created, $this->_language) : $timeago->inWords($item->infraction_date, $this->_language)),
                        'time_full' => ($item->created ? date(DATE_FORMAT, $item->created) : date(DATE_FORMAT, strtotime($item->infraction_date))),
                        'type' => $item->type,
                        'reason' => Output::getPurified($item->reason),
                        'acknowledged' => $item->acknowledged,
                        'revoked' => $item->revoked,
                        'revoked_by_username' => ($revoked_by_user ? $revoked_by_user->getDisplayname(true) : ''),
                        'revoked_by_nickname' => ($revoked_by_user ? $revoked_by_user->getDisplayname() : ''),
                        'revoked_by_style' => ($revoked_by_user ? $revoked_by_user->getGroupStyle() : ''),
                        'revoked_by_avatar' => ($revoked_by_user ? $revoked_by_user->getAvatar() : ''),
                        'revoked_by_profile' => ($revoked_by_user ? URL::build('/panel/user/' . urlencode($revoked_by_user->data()->id) . '-' . urlencode($revoked_by_user->data()->username)) : ''),
                        'revoked_at' => $timeago->inWords($item->revoked_at, $this->_language)
                    ];
                }
            }

            $this->_cache->store('recent_punishments_data', $data, 60);
        }

        $this->_smarty->assign([
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
        ]);

        return $this->_smarty->fetch('collections/dashboard_items/recent_punishments.tpl');
    }

    public function getWidth(): float {
        return 0.33; // 1/3 width
    }
}
