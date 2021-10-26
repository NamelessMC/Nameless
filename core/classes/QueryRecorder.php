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

    public function getSqlStack(): array {
        return array_reverse($this->_query_stack);
    }

    public function pushQuery(string $sql, array $params): void {

        $backtrace = $this->lastReleventBacktrace(debug_backtrace());

        $this->_query_stack[] = [
            'number' => $this->_query_stack_num,
            'frame' => ErrorHandler::parseFrame(null, $backtrace['file'], $backtrace['line'], $this->_query_stack_num),
            'sql_query' => $this->compileQuery($sql, $params)
        ];

        $this->_query_stack_num++;
    }

    private function lastReleventBacktrace(array $backtrace): array {

        $current_file = $last_file = $backtrace[0]['file'];
        $i = 1;

        while ($current_file == $last_file) {
            $last_file = $backtrace[$i]['file'];
            $i++;
        }

        return $backtrace[$i];
    }

    private function compileQuery(string $sql, array $params): string {
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

        $comp .= ';';

        require_once(ROOT_PATH . '/core/includes/sqlformatter/SQLFormatter.php');

        return SQLFormatter::highlight(trim($comp));
    }

}