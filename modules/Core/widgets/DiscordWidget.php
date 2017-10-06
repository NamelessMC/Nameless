<?php
/*
 *	Made by Partydragen
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Discord Widget
 */
class DiscordWidget extends WidgetBase {
    public function __construct($pages = array(), $discord = '', $theme = ''){
        parent::__construct($pages);

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Discord';
        $this->_location = 'right';
        $this->_description = 'Display your Discord channel on your site. Make sure you have entered your Discord widget details in the AdminCP -> Core -> Social Media tab first!';

        // Generate HTML code for widget
        $this->_content = '
			<iframe src="https://discordapp.com/widget?id=' . Output::getClean($discord) . '&theme=dark" width="350" height="500" allowtransparency="true" frameborder="0"></iframe>
        ';
    }
}