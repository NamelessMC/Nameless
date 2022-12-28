<?php
declare(strict_types=1);

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
    public ?int $updated;
    public ?string $upf_id;

    /**
     * @param object $row
     */
    public function __construct(object $row) {
        parent::__construct($row);
        $this->value = $row->value;
        $this->updated = $row->updated;
        $this->upf_id = $row->upf_id;
    }

    /**
     *
     * @return false|string
     */
    public function updated() {
        return date(DATE_FORMAT, $this->updated);
    }
}
