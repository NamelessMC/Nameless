<?php 

class NamelessPDOException extends PDOException {

    private $_sql;
    private array $_params;
    private $_sql_query;

    public function __construct(PDOException $exception, $sql, $params) {
        $this->_sql = $sql;
        $this->_params = array_values($params);
        $this->_sql_query = $this->compileQuery();

        parent::__construct($exception->getMessage() . ' (SQL Query: ' . $this->_sql_query . ')');
    }

    private function compileQuery() {

        $comp = '';

        $split = explode('?', $this->_sql);

        $i = 0;
        foreach ($split as $section) {

            if ($section == '') {
                continue;
            }

            $param = $this->_params[$i];

            $comp .= "$section '$param'";
            $i++;
        }

        return trim($comp);
    }

    public function getSqlQuery() {
        return $this->_sql_query;
    }

}