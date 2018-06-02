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
    public function __construct($pages = array(), $discord = '', $theme = ''){
        parent::__construct($pages);

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Discord';
        $this->_location = 'right';
        $this->_description = 'Display your Discord channel on your site. Make sure you have entered your Discord widget details in the AdminCP -> Core -> Social Media tab first!';

        // Generate HTML code for widget
        // First, check to see if the Discord server has the widget enabled.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/servers/" . Output::getClean($discord) . "/widget.json");
        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);
	    // Check if the widget is disabled.
        if ($result->code == "50004" | $result->message == "Widget Disabled") {
	        // Yes, it is: display message
            $this->_content = '
			<h4>Discord Widget</h4><p>The widget is disabled for the specified Discord server. Please go to \'server settings\' on Discord, then \'widget\', then finally toggle \'enable widget\', save, and reload this page.</p>
            
';
	    } else {
	        // No, it isn't: display the widget
            $this->_content = '
			<iframe src="https://discordapp.com/widget?id=' . Output::getClean($discord) . '&theme=dark" width="100%" height="500" allowtransparency="true" frameborder="0"></iframe>
';
	    }
    }
}
