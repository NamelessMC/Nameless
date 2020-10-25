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

    public static function getAll() {
        $cache = new Cache();
        $cache->setCache('custom_announcements');
        if ($cache->isCached('custom_announcements')) return (array) $cache->retrieve('custom_announcements');
        $cache->store('custom_announcements', DB::getInstance()->query("SELECT * FROM nl2_custom_announcements")->results());
        return (array) $cache->retrieve('custom_announcements');
    }

    public static function getAvailable($page = null, $custom_page = null, $group_id = null, $secondary_groups = null) {
        if ($group_id == null) $group_id = 0;
        else {
            if ($secondary_groups) $secondary_groups = json_decode($secondary_groups);
        }
        $announcements = array();
        foreach(self::getAll() as $announcement) {
			if (Cookie::exists('announcement-' . $announcement->id)) {
				continue;
			}
            $pages = json_decode($announcement->pages, true);
            $groups = json_decode($announcement->groups, true);
            if (in_array($page, $pages) || $page == 'api' || in_array($custom_page, $pages)) {
                if (in_array($group_id, $groups)) {
                    $announcements[] = $announcement;
                } else if (is_array($secondary_groups) && count($secondary_groups)) {
                    foreach($secondary_groups as $secondary_group) {
                        if(in_array($secondary_group, $groups)) {
                            $announcements[] = $announcement;
                            break;
                        }
                    }
                }
            }
        }
        return $announcements;
    }

    public static function getPages($pages) {
        $available_pages = array();
        foreach ($pages->returnPages() as $page) if (!empty($page['name'])) $available_pages[] = $page;
        return $available_pages;
    }

    public static function getPagesCsv($pages_json = null){
        return implode(', ', array_map('ucfirst', json_decode($pages_json)));
    }

    public static function edit($id = null, $pages = null, $groups = null, $text_colour = null, $background_colour = null, $icon = null, $closable = null, $header = null, $message = null) {
        $queries = new Queries;
        $queries->update('custom_announcements', $id, array('pages' => json_encode($pages), 'groups' => json_encode($groups), 'text_colour' => $text_colour, 'background_colour' => $background_colour, 'icon' => $icon, 'closable' => $closable ? 1 : 0, 'header' => $header, 'message' => $message));
        self::resetCache();
        return true;
    }

    public static function create($pages = null, $groups = null, $text_colour = null, $background_colour = null, $icon = null, $closable = null, $header = null, $message = null) {
        $queries = new Queries;
        $queries->create('custom_announcements', array('pages' => json_encode($pages), 'groups' => json_encode($groups), 'text_colour' => $text_colour, 'background_colour' => $background_colour, 'icon' => $icon, 'closable' => $closable ? 1 : 0, 'header' => $header, 'message' => $message));
        self::resetCache();
        return true;
    }

    public static function resetCache(){
        $cache = new Cache();
        $cache->setCache('custom_announcements');
        if ($cache->isCached('custom_announcements')) $cache->erase('custom_announcements');
        $cache->store('custom_announcements', self::getAll());
    }
}
