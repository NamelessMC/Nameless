<?php
/**
 * Represents a custom profile field which belongs to a user.
 *
 * @package NamelessMC\DTO
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class UserProfileField extends ProfileField {

    public ?string $value;
    public ?int $upf_id;

    public function __construct(object $row) {
        parent::__construct($row);
        $this->value = $row->value;
        $this->upf_id = $row->upf_id;
    }

}
