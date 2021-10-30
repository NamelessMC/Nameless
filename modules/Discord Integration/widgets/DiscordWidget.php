<?php
/*
 *	Made by Partydragen
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
    private ?int $_guild_id;

    public function __construct(array $pages = [], Cache $cache) {
        $this->_cache = $cache;
        $this->_guild_id = Discord::getGuildId();

        parent::__construct($pages);

        // Get widget
        $widget_query = DB::getInstance()->selectQuery('SELECT `location`, `order` FROM nl2_widgets WHERE `name` = ?', ['Discord'])->first();

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
        // First, check to see if the Discord server has the widget enabled.
        $this->_cache->setCache('social_media');
        if ($this->_cache->isCached('discord_widget_check')) {
            $result = $this->_cache->retrieve('discord_widget_check');

        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_URL, 'https://discord.com/api/guilds/' . Output::getClean($this->_guild_id) . '/widget.json');
            $result = curl_exec($ch);
            $result = json_decode($result);
            curl_close($ch);

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
            if($this->_cache->isCached('discord_widget_theme'))
                $theme = $this->_cache->retrieve('discord_widget_theme');

            $this->_content = '<iframe src="https://discord.com/widget?id=' . Output::getClean($this->_guild_id) . '&theme=' . Output::getClean($theme) . '" width="100%" height="500" allowtransparency="true" frameborder="0"></iframe><br />';

        }
    }
}
