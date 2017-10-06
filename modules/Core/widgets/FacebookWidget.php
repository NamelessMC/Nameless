<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Facebook Widget
 */
class FacebookWidget extends WidgetBase {
    public function __construct($pages = array(), $fb_url = ''){
        parent::__construct($pages);

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Facebook';
        $this->_location = 'right';
        $this->_description = 'Display a feed from your Facebook page on your site. Make sure you have entered your Facebook URL in the AdminCP -> Core -> Social Media tab first!';

        // Generate HTML code for widget
        $this->_content = '
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.10";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, \'script\', \'facebook-jssdk\'));</script>
            
            <div class="fb-page" data-href="' . $fb_url . '" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="' . $fb_url . '" class="fb-xfbml-parse-ignore"><a href="' . $fb_url . '">' . SITE_NAME . '</a></blockquote></div>
        ';
    }
}