<?php
/*
 *	Made by Samerton
 *  Announcements by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Announcements class
 */
class Announcements {

    /**
     * Get all announcements for listing in StaffCP.
     * 
     * @return array All announcements.
     */
    public static function getAll() {
        $cache = new Cache();
        $cache->setCache('custom_announcements');

        if ($cache->isCached('custom_announcements')) {
            return $cache->retrieve('custom_announcements');
        }

        $cache->store('custom_announcements', DB::getInstance()->query("SELECT * FROM nl2_custom_announcements")->results());
        
        return $cache->retrieve('custom_announcements');
    }

    /**
     * Get all announcements matching the param filters.
     * If they have a cookie set for an announcement, it will be skipped.
     * 
     * @param string|null $page Name of the page they're viewing.
     * @param string|null $custom_page Title of custom page they're viewing.
     * @param array $user_groups All this user's groups.
     * @return array Array of announcements they should see on this specific page with their groups.
     */
    public static function getAvailable($page = null, $custom_page = null, $user_groups = [0]) {
        $announcements = array();

        foreach(self::getAll() as $announcement) {

            if (Cookie::exists('announcement-' . $announcement->id)) {
                continue;
            }

            $pages = json_decode($announcement->pages, true);
            $groups = json_decode($announcement->groups, true);

            if (in_array($page, $pages) || $page == 'api' || in_array($custom_page, $pages)) {
                foreach($user_groups as $group) {
                    if (in_array($group, $groups)) {
                        $announcements[] = $announcement;
                        break;
                    }
                }
            }
        }

        return $announcements;
    }

    /**
     * Get all pages which can have announcements on them (they will have a 'name' attribute).
     * 
     * @param Pages $pages Instance of Pages class.
     * @return array Name of all pages announcements can be on.
     */
    public static function getPages(Pages $pages) {
        $available_pages = array();

        foreach ($pages->returnPages() as $page) {
            if (!empty($page['name'])) {
                $available_pages[] = $page;
            }
        }

        return $available_pages;
    }

    /**
     * Get prettified output of the pages a specific announcement is on.
     * 
     * @param string $pages_json JSON array of pages to implode.
     * @return string Comma seperated list of page names.
     */
    public static function getPagesCsv($pages_json = null){
        $pages = json_decode($pages_json);

        if (!$pages) {
            return null;
        }

        return implode(', ', array_map('ucfirst', $pages));
    }

    /**
     * Edit an existing announcement.
     * 
     * @param int $id ID of announcement to edit.
     * @param array $pages Array of page names this announcement should be on.
     * @param array $groups Array of group IDs this announcement should be visible to.
     * @param string $text_colour Hex code of text colour to use.
     * @param string $background_colour Hex code of background banner colour of announcement.
     * @param string $icon HTML to use to display icon on announcement.
     * @param bool $closable Whether this announcement should have an "x" to close and hide, or be shown 24/7.
     * @param string $header Header text to show at top of announcement.
     * @param string $message Main text to show in announcement.
     */
    public static function edit($id = null, $pages = null, $groups = null, $text_colour = null, $background_colour = null, $icon = null, $closable = null, $header = null, $message = null) {
        $queries = new Queries();
        
        $queries->update('custom_announcements', $id, array(
            'pages' => json_encode($pages), 
            'groups' => json_encode($groups), 
            'text_colour' => $text_colour, 
            'background_colour' => $background_colour, 
            'icon' => $icon, 
            'closable' => $closable ? 1 : 0, 
            'header' => $header, 
            'message' => $message
        ));

        self::resetCache();
    }

    /**
     * Create an announcement.
     * 
     * @param array $pages Array of page names this announcement should be on.
     * @param array $groups Array of group IDs this announcement should be visible to.
     * @param string $text_colour Hex code of text colour to use.
     * @param string $background_colour Hex code of background banner colour of announcement.
     * @param string $icon HTML to use to display icon on announcement.
     * @param bool $closable Whether this announcement should have an "x" to close and hide, or be shown 24/7.
     * @param string $header Header text to show at top of announcement.
     * @param string $message Main text to show in announcement.
     */
    public static function create($pages = null, $groups = null, $text_colour = null, $background_colour = null, $icon = null, $closable = null, $header = null, $message = null) {
        $queries = new Queries();

        $queries->create('custom_announcements', array(
            'pages' => json_encode($pages), 
            'groups' => json_encode($groups), 
            'text_colour' => $text_colour, 
            'background_colour' => $background_colour, 
            'icon' => $icon, 
            'closable' => $closable ? 1 : 0, 
            'header' => $header, 
            'message' => $message
        ));

        self::resetCache();
    }

    /**
     * Erase and regenerate announcement cache file.
     * Used when creating or editing announcements.
     */
    public static function resetCache() {
        $cache = new Cache();
        $cache->setCache('custom_announcements');

        if ($cache->isCached('custom_announcements')) {
            $cache->erase('custom_announcements');
        }

        $cache->store('custom_announcements', self::getAll());
    }
}
