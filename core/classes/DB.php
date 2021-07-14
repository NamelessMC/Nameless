<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  Database class
 */

class DB {

    /** @var DB */
    private static $_instance = null;

    /** @var PDO */
    private $_pdo;

    private $_query,
            $_error = false,
            $_results,
            $_prefix,
            $_count = 0;

    private function __construct() {
        try {
            $charset = '';
            if(Config::get('mysql/initialise_charset')) {
                $charset = Config::get('mysql/charset');
                if (!$charset) $charset = 'utf8mb4';
                
                $charset = 'charset=' . $charset;
            }

            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';port=' . Config::get('mysql/port') . ';dbname=' . Config::get('mysql/db') . ';'.$charset, Config::get('mysql/username'), Config::get('mysql/password'));
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_prefix = Config::get('mysql/prefix');
        } catch (PDOException $e) {
            die("<strong>Error:<br /></strong><div class=\"alert alert-danger\">" . $e->getMessage() . "</div>Please check your database connection settings.");
        }
    }

    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new DB();
        }

        return self::$_instance;
    }

    public function query($sql, $params = array(), $fetch_method = PDO::FETCH_OBJ) {
        $this->_error = false;
        if($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if(count($params)) {
                foreach($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }

            if($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll($fetch_method);
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
        if($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if(count($params)) {
                foreach($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }

            if($this->_query->execute()) {
                $this->_count = $this->_query->rowCount();
            } else {
                print_r($this->_pdo->errorInfo());
                $this->_error = true;
            }
        }

        return $this;
    }

    public function createTable($name, $table_data, $other){
        $name = $this->_prefix . $name;
        $sql = "CREATE TABLE `{$name}` ({$table_data}) {$other}";

        if(!$this->createQuery($sql)->error()) {
            return $this;
        }

        return false;
    }

    public function action($action, $table, $where = array()) {
        if(count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=', '<>');

            $field 		= $where[0];
            $operator 	= $where[1];
            $value 		= $where[2];

            $table = $this->_prefix . $table;

            if(in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                if(!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }

        return false;
    }

    public function deleteAction($action, $table, $where = array()) {
        if(count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=', '<>');

            $field 		= $where[0];
            $operator 	= $where[1];
            $value 		= $where[2];

            $table = $this->_prefix . $table;

            if(in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                if(!$this->createQuery($sql, array($value))->error()) {
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

        if(!$this->query($sql)->error()) {
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

        foreach($fields as $field) {
            $values .= '?';
            if ($x < count($fields)) {
                $values .= ', ';
            }
            $x++;
        }

        $table = $this->_prefix . $table;
        $sql = "INSERT INTO {$table} (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

        return (!$this->createQuery($sql, $fields)->error());
    }

    public function update($table, $id, $fields) {
        $set = '';
        $x = 1;

        foreach($fields as $name => $value) {
            $set .= "{$name} = ?";

            if($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }
        $table = $this->_prefix . $table;
        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        return (!$this->createQuery($sql, $fields)->error());
    }

    public function increment($table, $id, $field) {
        $table = $this->_prefix . $table;
        $sql = "UPDATE {$table} SET {$field} = {$field} + 1 WHERE id = ?";

        return (!$this->createQuery($sql, array($id))->error());
    }

    public function decrement($table, $id, $field) {
        $table = $this->_prefix . $table;
        $sql = "UPDATE {$table} SET {$field} = {$field} - 1 WHERE id = ?";

        return (!$this->createQuery($sql, array($id))->error());
    }

    public function results() {
        return $this->_results;
    }

    public function first() {
        $results = $this->results();

        return isset($results[0]) ? $results[0] : null;
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

        if(!$this->query($sql)->error()) {
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

        if(!$this->query($sql)->error()) {
            return $this;
        }

        return false;
    }

    public function showTables($showTable) {
        $showTable = $this->_prefix . $showTable;
        $sql = "SHOW TABLES LIKE '{$showTable}'";

        if (!$this->query($sql)->error()) {
            return $this->_query->rowCount();
        }

        return false;
    }
}
