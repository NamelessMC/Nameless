<?php
/*
 *	Made by Partydragen
 *  Updated by BrightSkyz
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Discord Widget
 */
class DiscordWidget extends WidgetBase {
    public function __construct($pages = array(), $language, $cache, $discord = ''){
        parent::__construct($pages);

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Discord';
        $this->_location = 'right';
        $this->_description = 'Display your Discord channel on your site. Make sure you have entered your Discord widget details in the AdminCP -> Core -> Social Media tab first!';
        $this->_settings = ROOT_PATH . '/modules/Core/includes/admin_widgets/discord.php';

        // Generate HTML code for widget
        // First, check to see if the Discord server has the widget enabled.
        $cache->setCache('social_media');
        if($cache->isCached('discord_widget_check')){
            $result = $cache->retrieve('discord_widget_check');

        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/servers/" . Output::getClean($discord) . "/widget.json");
            $result = curl_exec($ch);
            $result = json_decode($result);
            curl_close($ch);

            // Cache for 60 seconds
            $cache->store('discord_widget_check', $result, 60);

        }

	    // Check if the widget is disabled.
        if (!isset($result->channels) || isset($result->code)) {
	        // Yes, it is: display message
            $this->_content = $language->get('general', 'discord_widget_disabled');

	    } else {
            // No, it isn't: display the widget
            // Check cache for theme
            $theme = 'dark';
            if($cache->isCached('discord_widget_theme'))
                $theme = $cache->retrieve('discord_widget_theme');

            $this->_content = '<iframe src="https://discordapp.com/widget?id=' . Output::getClean($discord) . '&theme=' . Output::getClean($theme) . '" width="100%" height="500" allowtransparency="true" frameborder="0"></iframe><br />';

        }
    }
}
