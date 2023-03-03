<?php

class MinecraftAccountProfileWidget extends ProfileWidgetBase {

    private Cache $_cache;
    private Language $_language;

    public function __construct(Smarty $smarty, Cache $cache, Language $language) {
        parent::__construct($smarty);

        $widget_query = self::getData('Minecraft Account');

        $this->_name = 'Minecraft Account';
        $this->_order = $widget_query->order;
        $this->_description = "Displays a users Minecraft account on their profile.";
        $this->_module = 'Core';
        $this->_location = $widget_query->location;
        $this->_cache = $cache;
        $this->_language = $language;
    }

    public function initialise(User $user): void {
        $integrationUser = $user->getIntegration('Minecraft');
        if ($integrationUser === null || !$integrationUser->exists() || !$integrationUser->isVerified()) {
            $this->_content = '';
            return;
        }

        $this->_smarty->assign([
            'USERNAME' => $integrationUser->data()->username,
            'UUID' => $integrationUser->data()->identifier,
            'UUID_FORMATTED' => ProfileUtils::formatUUID($integrationUser->data()->identifier),
        ]);

        $this->_cache->setCache('minecraft_last_online');
        if ($this->_cache->isCached($integrationUser->data()->identifier)) {
            $last_online = $this->_cache->retrieve($integrationUser->data()->identifier);
            $this->_smarty->assign([
                'LAST_ONLINE' => date(DATE_FORMAT, $last_online),
                'LAST_ONLINE_AGO' => (new TimeAgo(TIMEZONE))->inWords($last_online, $this->_language)
            ]);
        } else {
            $this->_smarty->assign([
                'LAST_ONLINE' => $this->_language->get('admin', 'unknown'),
                'LAST_ONLINE_AGO' => $this->_language->get('admin', 'unknown')
            ]);
        }

        $this->_content = $this->_smarty->fetch('widgets/minecraft_account.tpl');
    }
}
