<?php

/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Twitter Widget
 */

class TwitterWidget extends WidgetBase {

    public function __construct(Smarty $smarty, ?string $twitter = '', ?string $theme = '') {
        // Get widget
        $widget_query = self::getData('Twitter');

        parent::__construct(self::parsePages($widget_query), true);

        $this->_smarty = $smarty;

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Twitter';
        $this->_location = $widget_query->location;
        $this->_description = 'Display your Twitter feed on your site. Make sure you have entered your Twitter URL in the StaffCP -> Core -> Social Media tab first!';
        $this->_order = $widget_query->order;

        // Generate HTML code for widget
        $this->_content = '
            <a class="twitter-timeline" ' . (($theme == 'dark') ? 'data-theme="dark" ' : '') . ' data-height="600" href="' . Output::getClean($twitter) . '">Tweets</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

        <br>
        ';
    }

    public function initialise(): void {
        // Do nothing
    }
}
