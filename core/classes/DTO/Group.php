<?php
/**
 * Represents a group.
 *
 * @package NamelessMC\DTO
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class Group {

    public int $id;
    public string $name;
    public string $group_html;
    public string $group_html_lg;
    public ?string $group_username_color;
    public ?string $group_username_css;
    public bool $admin_cp;
    public bool $staff;
    public ?string $permissions;
    public bool $default_group;
    public int $order;
    public bool $force_tfa;
    public bool $deleted;

    public function __construct(object $row) {
        $this->id = $row->id;
        $this->name = $row->name;
        $this->group_html = $row->group_html;
        $this->group_html_lg = $row->group_html_lg;
        $this->group_username_color = $row->group_username_color;
        $this->group_username_css = $row->group_username_css;
        $this->admin_cp = $row->admin_cp;
        $this->staff = $row->staff;
        $this->permissions = $row->permissions;
        $this->default_group = $row->default_group;
        $this->order = $row->order;
        $this->force_tfa = $row->force_tfa;
        $this->deleted = $row->deleted;
    }

    /**
     * @return Group[]
     */
    public static function all(): array {
        return array_map(static function (object $row) {
            return new Group($row);
        }, DB::getInstance()->selectQuery('SELECT * FROM nl2_groups ORDER BY `order`')->results());
    }

    /**
     * @param int $value
     * @param string $column
     * @return Group|null
     */
    public static function find(int $value, string $column = 'id'): ?Group {
        $row = DB::getInstance()->selectQuery('SELECT * FROM nl2_groups WHERE ' . $column . ' = ?', [$value])->first();
        return $row
            ? new Group($row)
            : null;
    }

}
