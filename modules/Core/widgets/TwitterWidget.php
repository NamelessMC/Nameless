<?php

/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.2.0
 *
 *  Licence: MIT
 *
 *  Twitter Widget
 */

class TwitterWidget extends WidgetBase {

    private string $_twitter_url;
    private string $_theme;

    public function __construct(TemplateEngine $engine, ?string $twitter = '', ?string $theme = '') {
        $this->_module = 'Core';
        $this->_name = 'Twitter';
        $this->_description = 'Display your Twitter feed on your site. Make sure you have entered your Twitter URL in the StaffCP -> Core -> Social Media tab first!';
        $this->_engine = $engine;

        $this->_twitter_url = $twitter;
        $this->_theme = $theme;
    }

    public function initialise(): void {
        $this->_content = '
            <a class="twitter-timeline" ' . (($this->_theme == 'dark') ? 'data-theme="dark" ' : '') . ' data-height="600" href="' . Output::getClean($this->_twitter_url) . '">
                Tweets
            </a>
            <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
            <br>
        ';
    }
}
