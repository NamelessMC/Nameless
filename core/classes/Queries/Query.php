<?php

namespace NamelessMC\Framework\Queries;

abstract class Query {

    abstract public function handle();

    /**
     * @return never
     */
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        die;
    }
}
