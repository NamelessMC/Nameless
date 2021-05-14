<?php 

class NamelessPDOException extends PDOException {

    private $_sql_stack;

    public function __construct(PDOException $exception, $sql_stack) {
        $this->_sql_stack = $sql_stack;

        parent::__construct($exception->getMessage());
    }

    public function getMostRecentSqlQuery() {
        return end($this->_sql_stack)['sql_query'];
    }

    public function getSqlStack() {
        return array_reverse($this->_sql_stack);
    }

}