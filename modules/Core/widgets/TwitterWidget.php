<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Twitter Widget
 */
class TwitterWidget extends WidgetBase {
    public function __construct($pages = array(), $twitter = '', $theme = ''){
        parent::__construct($pages);

        // Set widget variables
        $this->_name = 'Twitter';
        $this->_location = 'right';
        $this->_description = 'Display your Twitter feed on your site. Configure in the AdminCP -> Core -> Social Media tab.';

        // Generate HTML code for widget
        $this->_content = '
            <a class="twitter-timeline" ' . (($theme == 'dark') ? 'data-theme="dark" ' : '') . ' data-height="600" href="' . Output::getClean($twitter) . '">Tweets</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
        ';
    }
}