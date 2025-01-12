<?php
/**
 * Represents a custom profile field which belongs to a user.
 *
 * @package NamelessMC\DTO
 * @author Aberdeener
 * @version 2.2.0
 * @license MIT
 */
class UserProfileField extends ProfileField
{
    public ?string $value;
    public ?int $updated;
    public ?int $upf_id;

    public function __construct(object $row)
    {
        parent::__construct($row);
        $this->value = $row->value;
        $this->updated = $row->updated;
        $this->upf_id = $row->upf_id;
    }

    public function purifyValue(): ?string
    {
        // TODO: option for field to support HTML
        return Output::getClean($this->value);
    }

    public function updated()
    {
        return date(DATE_FORMAT, $this->updated);
    }
}
