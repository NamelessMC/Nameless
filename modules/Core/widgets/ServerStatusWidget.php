<?php
/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Profile Posts Widget
 */
class ServerStatusWidget extends WidgetBase {

    private $_cache,
            $_smarty,
            $_language;

    public function __construct($pages = array(), $smarty, $language, $cache) {
        $this->_language = $language;
        $this->_smarty = $smarty;
        $this->_cache = $cache;

        parent::__construct($pages);

        // Get widget
        $widget_query = DB::getInstance()->query('SELECT `location`, `order` FROM nl2_widgets WHERE `name` = ?', array('Server Status'))->first();

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Server Status';
        $this->_location = isset($widget_query->location) ? $widget_query->location : null;
        $this->_description = 'Display your Minecraft server status.';
        $this->_order = isset($widget_query->order) ? $widget_query->order : null;
    }

    public function initialise() {
        // Generate HTML code for widget
        $this->_cache->setCache('server_status_widget');

        if ($this->_cache->isCached('server_status')) {
            $server_array = $this->_cache->retrieve('server_status');
        } else {
            $server = DB::getInstance()->query('SELECT * FROM nl2_mc_servers WHERE is_default = 1')->results();
            $server = $server[0];

            if ($server != null) {
                $server_array = json_decode(Util::curlGetContents(Util::getSelfURL() . URL::build('/queries/server/', 'id=' . $server->id)), true);
                $server_array['name'] = Output::getClean($server->name);
                $server_array['join_at'] = Output::getClean($server->ip);

                $this->_cache->store('server_status', $server_array, 120);
            }
       }

        if (count($server_array) >= 1) {
            $this->_smarty->assign(
                array(
                    'SERVER' => $server_array,
                    'ONLINE' => $this->_language->get('general', 'online'),
                    'OFFLINE' => $this->_language->get('general', 'offline'),
                    'IP' => $this->_language->get('general', 'ip'),
                    'VERSION' => str_replace('{x}', '<strong>' . $server_array['version'] . '</strong>' , $this->_language->get('general', 'version'))
                )
            );
        }
        $this->_smarty->assign(
            array(
                'SERVER_STATUS' => $this->_language->get('general', 'server_status'),
                'NO_SERVERS' => $this->_language->get('general', 'no_default_server')
            )
        );
        $this->_content = $this->_smarty->fetch('widgets/server_status.tpl');;
    }
}
