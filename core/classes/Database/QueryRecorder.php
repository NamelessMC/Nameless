<?php
/**
 * Records PDO queries to display on exception page.
 *
 * @package NamelessMC\Database
 * @see ErrorHandler
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class QueryRecorder extends Instanceable {

    private array $_query_stack = [];
    private int $_query_stack_num = 1;

    /**
     * Get an array of all the SQL queries that have been executed in this request.
     *
     * @return array SQL queries
     */
    public function getSqlStack(): array {
        $stack = array_reverse($this->_query_stack);

        // Compile queries - replace bound parameters with their values and syntax highlight
        foreach ($stack as &$query) {
            $query['sql_query'] = $this->compileQuery(
                $query['sql_string'],
                $query['sql_params']
            );
        }

        return $stack;
    }

    /**
     * Add a query to the stack.
     *
     * @param string $sql Raw SQL query executed
     * @param array $params Bound parameters used in the query
     */
    public function pushQuery(string $sql, array $params): void {
        if (!Debugging::canViewDetailedError()) {
            return;
        }

        $backtrace = $this->lastReleventBacktrace();

        $this->_query_stack[] = [
            'number' => $this->_query_stack_num,
            'frame' => ErrorHandler::parseFrame(null, $backtrace['file'], $backtrace['line'], $this->_query_stack_num),
            'sql_string' => $sql,
            'sql_params' => $params,
        ];

        $this->_query_stack_num++;
    }

    /**
     * Get the last debug_backtrace entry which is not the file that executed this query or a database class.
     *
     * @return array debug_backtrace entry
     */
    private function lastReleventBacktrace(): array {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        $current_file = $last_file = $backtrace[0]['file'];
        $i = 1;

        while (
            $current_file === $last_file
            || str_ends_with($backtrace[$i]['file'], 'DB.php')
        ) {
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

        if (!str_ends_with($comp, ';')) {
            $comp .= ';';
        }

        return SqlFormatter::highlight(trim($comp));
    }
}
