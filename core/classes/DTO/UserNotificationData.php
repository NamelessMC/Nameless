<?php
/**
 * Represents notification data which belongs to a user.
 *
 * @package NamelessMC\DTO
 * @author Samerton
 * @version 2.2.0
 * @license MIT
 */
class UserNotificationData
{
    public bool $alert;
    public bool $email;
    public string $type;

    public function __construct(object $row)
    {
        $this->alert = $row->alert;
        $this->email = $row->email;
        $this->type = $row->type;
    }
}
