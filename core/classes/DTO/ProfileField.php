<?php
/**
 * Represents a custom profile field.
 *
 * @package NamelessMC\DTO
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class ProfileField {

    public int $id;
    public string $name;
    public int $type;
    public bool $public;
    public bool $required;
    public ?string $description;
    public ?int $length;
    public bool $forum_posts;
    public bool $editable;

    public function __construct(object $row) {
        $this->id = $row->id;
        $this->name = $row->name;
        $this->type = $row->type;
        $this->public = $row->public;
        $this->required = $row->required;
        $this->description = $row->description;
        $this->length = $row->length;
        $this->forum_posts = $row->forum_posts;
        $this->editable = $row->editable;
    }

    /**
     * @return array<int, ProfileField>
     */
    public static function all(): array {
        $rows = DB::getInstance()->get('profile_fields', ['id', '<>', 0])->results();
        $fields = [];
        foreach ($rows as $row) {
            $fields[$row->id] = new ProfileField($row);
        }
        return $fields;
    }

    /**
     * @param string $value
     * @param string $column
     * @return array<int, ProfileField>|ProfileField
     */
    public static function find(string $value, string $column = 'id') {
        $rows = DB::getInstance()->get('profile_fields', [$column, $value]);
        if (!$rows->count()) {
            return [];
        }

        if ($rows->count() === 1) {
            return new ProfileField($rows->first());
        }

        $fields = [];
        foreach ($rows->results() as $row) {
            $fields[$row->id] = new ProfileField($row);
        }

        return $fields;
    }

}
