<?php

/**
 * TODO: Add description
 *
 * @package NamelessMC\Core
 * @author UNKNOWN
 * @version UNKNOWN
 * @license MIT
 */
class Announcement {

    public string $id;
    public string $pages;
    public string $groups;
    public string $text_colour;
    public string $background_colour;
    public string $icon;
    public bool $closable;
    public string $header;
    public string $message;
    public int $order;

    /**
     * @param object $row
     */
    public function __construct(object $row) {
        $this->id = $row->id;
        $this->pages = $row->pages;
        $this->groups = $row->groups;
        $this->text_colour = $row->text_colour;
        $this->background_colour = $row->background_colour;
        $this->icon = $row->icon;
        $this->closable = $row->closable;
        $this->header = Output::getClean($row->header);
        $this->message = Output::getPurified($row->message);
        $this->order = $row->order;
    }

    /**
     * @param string $id
     *
     * @return ?Announcement
     */
    public static function find(string $id): ?Announcement {
        $row = DB::getInstance()->query('SELECT * FROM nl2_custom_announcements WHERE id = ?', [$id])->results();
        return $row
            ? new Announcement($row[0])
            : null;
    }
}
