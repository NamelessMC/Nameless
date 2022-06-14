<?php

class Announcement {

    public int $id;
    public string $pages;
    public string $groups;
    public string $text_colour;
    public string $background_colour;
    public string $icon;
    public bool $closable;
    public string $header;
    public string $message;
    public int $order;

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

    public static function find(int $id): ?Announcement {
        $row = DB::getInstance()->query('SELECT * FROM nl2_custom_announcements WHERE id = ?', [$id])->results();
        return $row
            ? new Announcement($row[0])
            : null;
    }

}
