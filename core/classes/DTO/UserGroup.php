<?php
/**
 * Represents a group which belongs to a user.
 *
 * @package NamelessMC\DTO
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class UserGroup extends Group {

    public int $recieved;
    public int $expire;

    public function __construct(object $row) {
        parent::__construct($row);
        $this->recieved = $row->recieved;
        $this->expire = $row->expire;
    }

}
