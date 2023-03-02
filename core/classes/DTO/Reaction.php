<?php
/**
 * Represents a reaction.
 *
 * @package NamelessMC\DTO
 * @author Aberdeener
 * @version 2.1.0
 * @license MIT
 */
class Reaction {

    public const TYPE_POSITIVE = 2;
    public const TYPE_NEGATIVE = 0;
    public const TYPE_NEUTRAL = 1;

    public int $id;
    public string $name;
    public string $html;
    public bool $enabled;
    public int $type;
    public int $order;

    public function __construct(object $row) {
        $this->id = $row->id;
        $this->name = $row->name;
        $this->html = $row->html;
        $this->enabled = $row->enabled;
        $this->type = $row->type;
        $this->order = $row->order;
    }

    /**
     * @return array<int, Reaction>
     */
    public static function all(): array {
        $rows = DB::getInstance()->query('SELECT * FROM nl2_reactions ORDER BY `order`')->results();
        $fields = [];
        foreach ($rows as $row) {
            $fields[$row->id] = new Reaction($row);
        }
        return $fields;
    }

    /**
     * @param string $value
     * @param string $column
     * @return array<int, Reaction>|Reaction
     */
    public static function find(string $value, string $column = 'id') {
        $rows = DB::getInstance()->query("SELECT * FROM nl2_reactions WHERE `$column` = $value ORDER BY `order`");
        if (!$rows->count()) {
            return [];
        }

        if ($rows->count() === 1) {
            return new Reaction($rows->first());
        }

        $fields = [];
        foreach ($rows->results() as $row) {
            $fields[$row->id] = new Reaction($row);
        }

        return $fields;
    }
}
