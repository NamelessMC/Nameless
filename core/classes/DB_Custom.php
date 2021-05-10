<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Custom database class
 */

class DB_Custom {

    /** @var DB_Custom */
    private static $_instance = null;

    /** @var PDO */
    private $_pdo;

    private $_query,
        $_error = false,
        $_results,
        $_prefix,
        $_count = 0;

    public function __construct($host, $database, $username, $password, $port = 3306) {
        try {
            $this->_pdo = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_prefix = '';
        } catch (PDOException $e) {
            die("<strong>Error:<br /></strong><div class=\"alert alert-danger\">" . $e->getMessage() . "</div>Please check your database connection settings.");
        }
    }

    public static function getInstance($host, $database, $username, $password, $port = 3306) {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB_Custom($host, $database, $username, $password, $port);
        }
        return self::$_instance;
    }

    public function query($sql, $params = array()) {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param, is_int($param) ? PDO::PARAM_INT : PDO::PARAM_STR);
                    $x++;
                }
            }

            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                print_r($this->_pdo->errorInfo());
                $this->_error = true;
            }
        }
        return $this;
    }

    public function createQuery($sql, $params = array()) {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }

            if ($this->_query->execute()) {
                $this->_count = $this->_query->rowCount();
            } else {
                print_r($this->_pdo->errorInfo());
                $this->_error = true;
            }
        }

        return $this;
    }

    public function createTable($name, $table_data, $other) {
        $name = $this->_prefix . $name;
        $sql = "CREATE TABLE `{$name}` ({$table_data}) {$other}";
        if (!$this->createQuery($sql)->error()) {
            return $this;
        }
        return false;
    }

    public function action($action, $table, $where = array()) {
        if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=', '<>');

            $field         = $where[0];
            $operator     = $where[1];
            $value         = $where[2];

            $table = $this->_prefix . $table;

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }

        return false;
    }

    public function deleteAction($action, $table, $where = array()) {
        if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=', '<>');

            $field         = $where[0];
            $operator     = $where[1];
            $value         = $where[2];

            $table = $this->_prefix . $table;

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                if (!$this->createQuery($sql, array($value))->error()) {
                    return $this;
                }
            }
        }

        return false;
    }

    public function get($table, $where) {
        return $this->action('SELECT *', $table, $where);
    }

    public function like($table, $column, $like) {
        $table = $this->_prefix . $table;
        $sql = "SELECT * FROM {$table} WHERE {$column} LIKE '{$like}'";

        if (!$this->query($sql)->error()) {
            return $this;
        }

        return false;
    }

    public function delete($table, $where) {
        return $this->deleteAction('DELETE', $table, $where);
    }

    public function insert($table, $fields = array()) {
        $keys = array_keys($fields);
        $values = '';
        $x = 1;

        foreach ($fields as $field) {
            $values .= '?';
            if ($x < count($fields)) {
                $values .= ', ';
            }
            $x++;
        }

        $table = $this->_prefix . $table;
        $sql = "INSERT INTO {$table} (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

        if (!$this->createQuery($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    public function update($table, $id, $fields) {
        $set = '';
        $x = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";

            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }
        $table = $this->_prefix . $table;
        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if (!$this->createQuery($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    public function increment($table, $id, $field) {
        $table = $this->_prefix . $table;
        $sql = "UPDATE {$table} SET {$field} = {$field} + 1 WHERE id = ?";

        if (!$this->createQuery($sql, array($id))->error()) {
            return true;
        }

        return false;
    }

    public function decrement($table, $id, $field) {
        $table = $this->_prefix . $table;
        $sql = "UPDATE {$table} SET {$field} = {$field} - 1 WHERE id = ?";

        if (!$this->createQuery($sql, array($id))->error()) {
            return true;
        }

        return false;
    }

    public function results() {
        return $this->_results;
    }

    public function first() {
        $results = $this->results();

        return isset($results[0]) == null ? null : $results[0];
    }

    public function error() {
        return $this->_error;
    }

    public function count() {
        return $this->_count;
    }

    public function lastId() {
        return $this->_pdo->lastInsertId();
    }

    public function alterTable($name, $column, $attributes) {
        $name = $this->_prefix . $name;
        $sql = "ALTER TABLE `{$name}` ADD {$column} {$attributes}";
        if (!$this->createQuery($sql)->error()) {
            return $this;
        }

        return false;
    }

    public function orderAll($table, $order, $sort) {
        $table = $this->_prefix . $table;
        if (isset($sort)) {
            $sql = "SELECT * FROM {$table} ORDER BY {$order} {$sort}";
        } else {
            $sql = "SELECT * FROM {$table} ORDER BY {$order}";
        }

        if (!$this->query($sql)->error()) {
            return $this;
        }

        return false;
    }

    public function orderWhere($table, $where, $order, $sort) {
        $table = $this->_prefix . $table;
        if (isset($sort)) {
            $sql = "SELECT * FROM {$table} WHERE {$where} ORDER BY {$order} {$sort}";
        } else {
            $sql = "SELECT * FROM {$table} WHERE {$where} ORDER BY {$order}";
        }

        if (!$this->query($sql)->error()) {
            return $this;
        }

        return false;
    }

    public function showTables($showTable)  {
        $showTable = $this->_prefix . $showTable;
        $sql = "SHOW TABLES LIKE '{$showTable}'";

        if (!$this->query($sql)->error()) {
            return $this->_query->rowCount();
        }

        return false;
    }
}
