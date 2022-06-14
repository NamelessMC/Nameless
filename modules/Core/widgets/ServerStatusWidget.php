<?php

/*
 *  Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Profile Posts Widget
 */

class ServerStatusWidget extends WidgetBase {

    private Cache $_cache;
    private Language $_language;

    public function __construct(Smarty $smarty, Language $language, Cache $cache) {
        $this->_language = $language;
        $this->_smarty = $smarty;
        $this->_cache = $cache;

        // Get widget
        $widget_query = self::getData('Server Status');

        parent::__construct(self::parsePages($widget_query));

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Server Status';
        $this->_location = $widget_query->location ?? null;
        $this->_description = 'Display your Minecraft server status.';
        $this->_order = $widget_query->order ?? null;
    }

    public function initialise(): void {
        // Generate HTML code for widget
        $this->_cache->setCache('server_status_widget');

        $server_array = [];

        if ($this->_cache->isCached('server_status')) {
            $server_array = $this->_cache->retrieve('server_status');
        } else {
            $server = DB::getInstance()->query('SELECT * FROM nl2_mc_servers WHERE is_default = 1')->results();
            $server = $server[0];

            if ($server != null) {
                $server_array_request = HttpClient::get(rtrim(URL::getSelfURL(), '/') . URL::build('/queries/server/', 'id=' . $server->id));
                if (!$server_array_request->hasError()) {
                    $server_array = $server_array_request->json(true);
                    foreach ($server_array as $key => $value) {
                        // we have to NOT escape the player list or the formatted player list. luckily these are the only arrays
                        if (is_array($value)) {
                            $server_array[$key] = $value;
                        } else {
                            $server_array[$key] = Output::getClean($value);
                        }
                    }
                    $server_array['name'] = $server->name;
                    $server_array['join_at'] = $server->ip;
                }

                $this->_cache->store('server_status', $server_array, 120);
            }
        }

        if (count($server_array) >= 1) {
            $this->_smarty->assign(
                [
                    'SERVER' => $server_array,
                    'ONLINE' => $this->_language->get('general', 'online'),
                    'OFFLINE' => $this->_language->get('general', 'offline'),
                    'IP' => $this->_language->get('general', 'ip'),
                    'VERSION' => isset($server_array['version']) ? $this->_language->get('general', 'version', ['version' => $server_array['version']]) : null
                ]
            );
        }
        $this->_smarty->assign(
            [
                'SERVER_STATUS' => $this->_language->get('general', 'server_status'),
                'NO_SERVERS' => $this->_language->get('general', 'no_default_server')
            ]
        );
        $this->_content = $this->_smarty->fetch('widgets/server_status.tpl');
    }
}
