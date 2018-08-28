<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Latest Posts Widget
 */
class LatestPostsWidget extends WidgetBase {
    public function __construct($pages = array(), $template_array, $latest_posts_language, $by_language, $smarty){
        parent::__construct($pages);

        // Get order
        $order = DB::getInstance()->query('SELECT `order` FROM nl2_widgets WHERE `name` = ?', array('Latest Posts'))->first();

        // Set widget variables
        $this->_module = 'Forum';
        $this->_name = 'Latest Posts';
        $this->_location = 'right';
        $this->_description = 'Display latest posts from your forum.';
        $this->_order = $order->order;

        // Generate HTML code for widget
        $smarty->assign('LATEST_POSTS', $latest_posts_language);
        $smarty->assign('LATEST_POSTS_ARRAY', $template_array);
        $smarty->assign('BY', $by_language);

        $this->_content = $smarty->fetch('widgets/forum/latest_posts.tpl');
    }
}