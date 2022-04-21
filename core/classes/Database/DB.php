<?php
/**
 * Creates a singleton connection to the database with credentials from the config file.
 *
 * @package NamelessMC\Database
 * @see InteractsWithDatabase
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */
class DB extends Instanceable {

    use InteractsWithDatabase;

    public function __construct() {
        try {
            $charset = '';
            if (Config::get('mysql/initialise_charset')) {
                $charset = 'charset=' . (Config::get('mysql/charset') ?: 'utf8mb4');
            }

            $this->_pdo = new PDO(
                'mysql:host=' . Config::get('mysql/host') . ';port=' . Config::get('mysql/port') . ';dbname=' . Config::get('mysql/db') . ';' . $charset,
                Config::get('mysql/username'),
                Config::get('mysql/password'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ],
            );
            $this->_prefix = Config::get('mysql/prefix');
        } catch (PDOException $e) {
            die("<strong>Error:<br /></strong><div class=\"alert alert-danger\">" . $e->getMessage() . '</div>Please check your database connection settings.');
        }

        $this->_query_recorder = QueryRecorder::getInstance();
    }
}
