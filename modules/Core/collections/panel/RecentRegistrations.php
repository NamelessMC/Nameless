<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Recent registrations dashboard collection item
 */

class RecentRegistrationsItem extends CollectionItemBase {

    private Smarty $_smarty;
    private Language $_language;
    private Cache $_cache;

    public function __construct(Smarty $smarty, Language $language, Cache $cache) {
        $cache->setCache('dashboard_main_items_collection');
        if ($cache->isCached('recent_registrations')) {
            $from_cache = $cache->retrieve('recent_registrations');
            $order = $from_cache['order'] ?? 2;

            $enabled = $from_cache['enabled'] ?? 1;
        } else {
            $order = 2;
            $enabled = 1;
        }

        parent::__construct($order, $enabled);

        $this->_smarty = $smarty;
        $this->_language = $language;
        $this->_cache = $cache;
    }

    public function getContent(): string {
        // Get recent registrations
        $timeago = new TimeAgo(TIMEZONE);

        $this->_cache->setCache('dashboard_main_items_collection');

        if ($this->_cache->isCached('recent_registrations_data')) {
            $data = $this->_cache->retrieve('recent_registrations_data');
        } else {
            $queries = new Queries();
            $query = $queries->orderAll('users', 'joined', 'DESC LIMIT 5');
            $data = [];

            if (count($query)) {
                $i = 0;

                foreach ($query as $item) {
                    $target_user = new User($item->id);
                    $data[] = [
                        'url' => URL::build('/panel/user/' . Output::getClean($item->id) . '-' . Output::getClean($item->username)),
                        'username' => $target_user->getDisplayname(true),
                        'nickname' => $target_user->getDisplayname(),
                        'style' => $target_user->getGroupClass(),
                        'avatar' => $target_user->getAvatar(),
                        'uuid' => Output::getClean($item->uuid),
                        'groups' => $target_user->getAllGroupHtml(),
                        'time' => $timeago->inWords(date('d M Y, H:i', $item->joined), $this->_language->getTimeLanguage()),
                        'time_full' => date('d M Y, H:i', $item->joined)
                    ];

                    if (++$i == 5) {
                        break;
                    }
                }
            }

            $this->_cache->store('recent_registrations_data', $data, 60);
        }

        $this->_smarty->assign([
            'RECENT_REGISTRATIONS' => $this->_language->get('moderator', 'recent_registrations'),
            'REGISTRATIONS' => $data,
            'REGISTERED' => $this->_language->get('user', 'registered'),
            'VIEW' => $this->_language->get('general', 'view')
        ]);

        return $this->_smarty->fetch('collections/dashboard_items/recent_registrations.tpl');
    }

    public function getWidth(): float {
        return 0.33; // 1/3 width
    }
}
