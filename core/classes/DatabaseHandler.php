<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  DatabaseHandler class
 */

class DatabaseHandler {

    private $_query_stack;
    private $_query_stack_num = 1;

    /** @var DatabaseHandler */
    private static $_instance;

    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new DatabaseHandler();
        }
        
        return self::$_instance;
    }

    public function getMostRecentSqlQuery() {
        return end($this->_query_stack)['sql_query'];
    }

    public function getSqlStack() {
        return array_reverse($this->_query_stack);
    }

    public function pushQuery($sql, $params) {

        $backtrace = array_shift(debug_backtrace());

        $this->_query_stack[] = [
            'num' => $this->_query_stack_num,
            'frame_file' => $backtrace['file'],
            'frame_line' => $backtrace['line'],
            'sql_query' => $this->compileQuery($sql, $params)
        ];

        $this->_query_stack_num++;
    }

    private function compileQuery($sql, $params) {
        $comp = '';

        $split = explode(' ?', $sql);

        $i = 0;
        foreach ($split as $section) {

            if ($section == '') {
                continue;
            }

            $param = $params[$i];

            $comp .= "$section '$param'";
            $i++;
        }

        if (!str_ends_with(';', $comp)) {
            $comp .= ';';
        }

        return SQLFormatter::highlight(trim($comp));
    }

}