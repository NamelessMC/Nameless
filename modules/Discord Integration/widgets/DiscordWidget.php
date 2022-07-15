<?php

/*
 *  Made by Partydragen
 *  Updated by BrightSkyz
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Discord Widget
 */

class DiscordWidget extends WidgetBase {

    private Cache $_cache;
    private ?string $_guild_id;

    public function __construct(Cache $cache, Smarty $smarty) {
        $this->_cache = $cache;
        $this->_guild_id = Discord::getGuildId();
        $this->_smarty = $smarty;

        // Get widget
        $widget_query = self::getData('Discord');

        parent::__construct(self::parsePages($widget_query), true);

        // Set widget variables
        $this->_module = 'Discord Integration';
        $this->_name = 'Discord';
        $this->_location = $widget_query->location ?? null;
        $this->_description = 'Display your Discord channel on your site. Make sure you have entered your Discord widget details in the StaffCP -> Integrations -> Discord tab first!';
        $this->_settings = ROOT_PATH . '/modules/Discord Integration/includes/admin_widgets/discord.php';
        $this->_order = $widget_query->order ?? null;
    }

    public function initialise(): void {
        // Generate HTML code for widget
        // If there is no Guild ID set, display error message
        if ($this->_guild_id === null) {
            $this->_content = Discord::getLanguageTerm('discord_widget_disabled');
            return;
        }

        // First, check to see if the Discord server has the widget enabled.
        $this->_cache->setCache('social_media');
        if ($this->_cache->isCached('discord_widget_check')) {
            $result = $this->_cache->retrieve('discord_widget_check');

        } else {
            $request = HttpClient::get('https://discord.com/api/guilds/' . urlencode($this->_guild_id) . '/widget.json');
            if ($request->hasError()) {
                $this->_content = Discord::getLanguageTerm('discord_widget_error', [
                    'error' => $request->getError()
                ]);
                return;
            }

            $result = $request->json();

            // Cache for 60 seconds
            $this->_cache->store('discord_widget_check', $result, 60);
        }

        // Check if the widget is disabled.
        if (!isset($result->channels) || isset($result->code)) {
            // Yes, it is: display message
            $this->_content = Discord::getLanguageTerm('discord_widget_disabled');

        } else {
            // No, it isn't: display the widget
            // Check cache for theme
            $theme = 'dark';
            if ($this->_cache->isCached('discord_widget_theme')) {
                $theme = $this->_cache->retrieve('discord_widget_theme');
            }

            $this->_content = '<iframe src="https://discord.com/widget?id=' . urlencode($this->_guild_id) . '&theme=' . urlencode($theme) . '" width="100%" height="500" allowtransparency="true" frameborder="0"></iframe><br />';
        }
    }
}
