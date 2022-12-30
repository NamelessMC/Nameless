<?php

/**
 * Facebook Widget
 *
 * @package Modules\Core\Widgets
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class FacebookWidget extends WidgetBase {

    /**
     * @param Smarty $smarty
     * @param ?string $fb_url
     */
    public function __construct(Smarty $smarty, ?string $fb_url = '') {
        $this->_smarty = $smarty;

        // Get widget
        $widget_query = self::getData('Facebook');

        parent::__construct(self::parsePages($widget_query), true);

        // Set widget variables
        $this->_module = 'Core';
        $this->_name = 'Facebook';
        $this->_location = $widget_query->location;
        $this->_description = 'Display a feed from your Facebook page on your site. Make sure you have entered your Facebook URL in the StaffCP -> Core -> Social Media tab first!';
        $this->_order = $widget_query->order;

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

            <div class="fb-page" data-href="' . Output::getClean($fb_url) . '" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="' . Output::getClean($fb_url) . '" class="fb-xfbml-parse-ignore"><a href="' . Output::getClean($fb_url) . '">' . Output::getClean(SITE_NAME) . '</a></blockquote></div>';
    }

    /**
     * Generate this widget's `$_content`.
     */
    public function initialise(): void {
        // Do nothing
    }
}
