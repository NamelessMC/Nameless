<?php
/*
 *	Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  QueryRecorder class
 */

class QueryRecorder extends Instanceable {

    private array $_query_stack;
    private int $_query_stack_num = 1;

    /**
     * Get an array of all the SQL queries that have been executed in this request.
     *
     * @return array SQL queries
     */
    public function getSqlStack(): array {
        return array_reverse($this->_query_stack);
    }

    /**
     * Add a query to the stack.
     *
     * @param string $sql Raw SQL query executed
     * @param array $params Bound parameters used in the query
     */
    public function pushQuery(string $sql, array $params): void {

        $backtrace = $this->lastReleventBacktrace();

        $this->_query_stack[] = [
            'number' => $this->_query_stack_num,
            'frame' => ErrorHandler::parseFrame(null, $backtrace['file'], $backtrace['line'], $this->_query_stack_num),
            'sql_query' => $this->compileQuery($sql, $params)
        ];

        $this->_query_stack_num++;
    }

    /**
     * Get the last debug_backtrace entry which is not the file that executed this query.
     *
     * @return array debug_backtrace entry
     */
    private function lastReleventBacktrace(): array {

        $backtrace = debug_backtrace();

        $current_file = $last_file = $backtrace[0]['file'];
        $i = 1;

        while ($current_file == $last_file) {
            $last_file = $backtrace[$i]['file'];
            $i++;
        }

        return $backtrace[$i];
    }

    /**
     * Get a compiled SQL query with bound parameters replaced with their values
     * and syntax highlighted.
     *
     * @param string $sql Raw SQL query
     * @param array $params Bound parameters
     * @return string Compiled + syntax highlighted SQL query
     */
    private function compileQuery(string $sql, array $params): string {
        $comp = '';

        $split = explode(' ?', $sql);

        $i = 0;
        foreach ($split as $section) {

            if ($section == '') {
                continue;
            }

            if (!isset($params[$i])) {
                $comp .= $section;
                $i++;
                continue;
            }

            $param = $params[$i];

            $comp .= "$section '$param'";
            $i++;
        }

        $comp .= ';';

        require_once(ROOT_PATH . '/core/includes/sqlformatter/SQLFormatter.php');

        return SQLFormatter::highlight(trim($comp));
    }
}
