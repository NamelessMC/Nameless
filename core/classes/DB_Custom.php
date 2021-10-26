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

    private static DB_Custom $_instance;

    private PDO $_pdo;
    private PDOStatement $_query;
    private bool $_error = false;
    private array $_results;
    private string $_prefix;
    private int $_count = 0;
    private QueryRecorder $_query_recorder;

    public function __construct(string $host, string $database, string $username, string $password, int $port = 3306) {
        try {
            $this->_pdo = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_prefix = '';
        } catch (PDOException $e) {
            die("<strong>Error:<br /></strong><div class=\"alert alert-danger\">" . $e->getMessage() . "</div>Please check your database connection settings.");
        }

        $this->_query_recorder = QueryRecorder::getInstance();
    }

    public static function getInstance(string $host, string $database, string $username, string $password, int $port = 3306): DB_Custom {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB_Custom($host, $database, $username, $password, $port);
        }
        return self::$_instance;
    }

    /**
     * @deprecated Use selectQuery function to select data from DB, or createQuery function to modify data in DB
     */
    public function query(string $sql,  array $params = array(), int $fetch_method = PDO::FETCH_OBJ): DB_Custom {
        return $this->selectQuery(...func_get_args());
    }

    public function selectQuery(string $sql,  array $params = array(), int $fetch_method = PDO::FETCH_OBJ): DB_Custom {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param, is_int($param) ? PDO::PARAM_INT : PDO::PARAM_STR);
                    $x++;
                }
            }

            $this->_query_recorder->pushQuery($sql, $params);

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

    public function createQuery(string $sql, array $params = array()): DB_Custom {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }

            $this->_query_recorder->pushQuery($sql, $params);

            if ($this->_query->execute()) {
                $this->_count = $this->_query->rowCount();
            } else {
                print_r($this->_pdo->errorInfo());
                $this->_error = true;
            }
        }

        return $this;
    }

    public function createTable(string $name, string $table_data, string $other) {
        $name = $this->_prefix . $name;
        $sql = "CREATE TABLE `{$name}` ({$table_data}) {$other}";
        if (!$this->createQuery($sql)->error()) {
            return $this;
        }
        return false;
    }

    public function action(string $action, string $table, array $where = array()) {
        if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=', '<>');

            $field         = $where[0];
            $operator     = $where[1];
            $value         = $where[2];

            $table = $this->_prefix . $table;

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                if (!$this->selectQuery($sql, array($value))->error()) {
                    return $this;
                }
            }
        }

        return false;
    }

    public function deleteAction(string $action, string $table, array $where = array()) {
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

    public function get(string $table, array $where) {
        return $this->action('SELECT *', $table, $where);
    }

    public function like(string $table, string $column, string $like) {
        $table = $this->_prefix . $table;
        $sql = "SELECT * FROM {$table} WHERE {$column} LIKE '{$like}'";

        if (!$this->selectQuery($sql)->error()) {
            return $this;
        }

        return false;
    }

    public function delete(string $table, array $where) {
        return $this->deleteAction('DELETE', $table, $where);
    }

    public function insert(string $table, array $fields = array()): bool {
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

    public function update(string $table, int $id, array $fields): bool {
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

    public function increment(string $table, int $id, string $field): bool {
        $table = $this->_prefix . $table;
        $sql = "UPDATE {$table} SET {$field} = {$field} + 1 WHERE id = ?";

        if (!$this->createQuery($sql, array($id))->error()) {
            return true;
        }

        return false;
    }

    public function decrement(string $table, int $id, string $field): bool {
        $table = $this->_prefix . $table;
        $sql = "UPDATE {$table} SET {$field} = {$field} - 1 WHERE id = ?";

        if (!$this->createQuery($sql, array($id))->error()) {
            return true;
        }

        return false;
    }

    public function results(): array {
        return $this->_results;
    }

    public function first(): ?object {
        $results = $this->results();

        return isset($results[0]) == null ? null : $results[0];
    }

    public function error(): bool {
        return $this->_error;
    }

    public function count(): int {
        return $this->_count;
    }

    public function lastId(): int {
        return $this->_pdo->lastInsertId();
    }

    public function alterTable(string $name, string $column, string $attributes) {
        $name = $this->_prefix . $name;
        $sql = "ALTER TABLE `{$name}` ADD {$column} {$attributes}";
        if (!$this->createQuery($sql)->error()) {
            return $this;
        }

        return false;
    }

    public function orderAll(string $table, string $order, string $sort) {
        $table = $this->_prefix . $table;
        if (isset($sort)) {
            $sql = "SELECT * FROM {$table} ORDER BY {$order} {$sort}";
        } else {
            $sql = "SELECT * FROM {$table} ORDER BY {$order}";
        }

        if (!$this->selectQuery($sql)->error()) {
            return $this;
        }

        return false;
    }

    public function orderWhere(string $table, string $where, string $order, string $sort) {
        $table = $this->_prefix . $table;
        if (isset($sort)) {
            $sql = "SELECT * FROM {$table} WHERE {$where} ORDER BY {$order} {$sort}";
        } else {
            $sql = "SELECT * FROM {$table} WHERE {$where} ORDER BY {$order}";
        }

        if (!$this->selectQuery($sql)->error()) {
            return $this;
        }

        return false;
    }

    public function showTables(string $showTable)  {
        $showTable = $this->_prefix . $showTable;
        $sql = "SHOW TABLES LIKE '{$showTable}'";

        if (!$this->selectQuery($sql)->error()) {
            return $this->_query->rowCount();
        }

        return false;
    }
}
