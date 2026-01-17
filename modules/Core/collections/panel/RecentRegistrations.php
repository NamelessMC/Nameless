<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.2.0
 *
 *  Licence: MIT
 *
 *  Recent registrations dashboard collection item
 */

class RecentRegistrationsItem extends CollectionItemBase {

    private TemplateEngine $_engine;
    private Language $_language;
    private Cache $_cache;

    public function __construct(TemplateEngine $engine, Language $language, Cache $cache) {
        $order = 2;
        $enabled = 1;

        parent::__construct($order, $enabled);

        $this->_engine = $engine;
        $this->_language = $language;
        $this->_cache = $cache;
    }

    public function getContent(): string {
        // Get recent registrations
        $timeAgo = new TimeAgo(TIMEZONE);

        $this->_cache->setCache('dashboard_main_items_collection');

        $data = $this->_cache->fetch('recent_registrations_data', function () use ($timeAgo) {
            $query = DB::getInstance()->orderAll('users', 'joined', 'DESC LIMIT 5')->results();
            $data = [];

            if (count($query)) {
                $i = 0;

                foreach ($query as $item) {
                    $target_user = new User($item->id);
                    $data[] = [
                        'url' => URL::build('/panel/user/' . urlencode($item->id) . '-' . urlencode($item->username)),
                        'username' => $target_user->getDisplayname(true),
                        'nickname' => $target_user->getDisplayname(),
                        'style' => $target_user->getGroupStyle(),
                        'avatar' => $target_user->getAvatar(),
                        'groups' => $target_user->getAllGroupHtml(),
                        'time' => $timeAgo->inWords($item->joined, $this->_language),
                        'time_full' => date(DATE_FORMAT, $item->joined),
                    ];

                    if (++$i == 5) {
                        break;
                    }
                }
            }

            return $data;
        }, 60);

        $this->_engine->addVariables([
            'RECENT_REGISTRATIONS' => $this->_language->get('moderator', 'recent_registrations'),
            'REGISTRATIONS' => $data,
            'REGISTERED' => $this->_language->get('user', 'registered'),
            'VIEW' => $this->_language->get('general', 'view')
        ]);

        return $this->_engine->fetch('collections/dashboard_items/recent_registrations');
    }

    public function getWidth(): float {
        return 0.33; // 1/3 width
    }
}
