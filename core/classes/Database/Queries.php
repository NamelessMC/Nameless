<?php
/**
 * Provides simpler abstractions over common database queries.
 *
 * @package NamelessMC\Database
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */
class Queries {

    private DB $_db;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function getWhere(string $table, array $where): array {
        return $this->_db->get($table, $where)->results();
    }

    public function orderAll(string $table, string $order, string $sort = null): array {
        return $this->_db->orderAll($table, $order, $sort)->results();
    }

    public function orderWhere(string $table, string $where, string $order, string $sort = null): array {
        return $this->_db->orderWhere($table, $where, $order, $sort)->results();
    }

    public function update(string $table, $where, array $fields = []): void {
        if (!$this->_db->update($table, $where, $fields)) {
            throw new RuntimeException('There was a problem performing that action.');
        }
    }

    public function create(string $table, array $fields = []): void {
        if (!$this->_db->insert($table, $fields)) {
            throw new RuntimeException('There was a problem performing that action.');
        }
    }

    public function delete(string $table, array $where): void {
        if (!$this->_db->delete($table, $where)) {
            throw new RuntimeException('There was a problem performing that action.');
        }
    }

    public function increment(string $table, int $id, string $field): void {
        if (!$this->_db->increment($table, $id, $field)) {
            throw new RuntimeException('There was a problem performing that action.');
        }
    }

    public function createTable(string $table, string $columns, string $other): void {
        if (!$this->_db->createTable($table, $columns, $other)) {
            throw new RuntimeException('There was a problem performing that action.');
        }
    }

    /**
     * Get the last inserted ID
     *
     * @return string|false
     */
    public function getLastId() {
        return $this->_db->lastId();
    }

    public function addColumn(string $table, string $column, string $attributes): void {
        if (!$this->_db->addColumn($table, $column, $attributes)) {
            throw new RuntimeException('There was a problem performing that action.');
        }
    }

    public function tableExists(string $table) {
        return $this->_db->showTables($table);
    }

    public function addPermissionGroup(int $group_id, string $permission): void {
        $group = Group::find($group_id);
        if ($group) {
            $permissions = $group->permissions;
            $permissions = json_decode($permissions, true);
            if (is_array($permissions)) {
                $permissions[$permission] = 1;
                $perms_json = json_encode($permissions);
                $this->_db->update('groups', $group_id, ['permissions' => $perms_json]);
            }
        }
    }

    /**
     * Initialise the database structure on a fresh installation.
     *
     * @return bool True if the database was initialised, false if not.
     */
    public function dbInitialise(): bool {
        $data = $this->_db->showTables('settings');

        if (!empty($data)) {
            echo '<div class="alert alert-warning">Database already initialised!</div>';
            return false;
        }

        PhinxAdapter::migrate();
        return true;
    }
}
