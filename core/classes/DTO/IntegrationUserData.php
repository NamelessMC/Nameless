<?php
declare(strict_types=1);

/**
 * TODO: Document this file.
 *
 * @package NamelessMC\DTO
 * @author Unknown
 * @version 2.1.0
 * @license MIT
 */
class IntegrationUserData {

    public string $id;
    public string $integration_id;
    public string $user_id;
    public ?string $identifier;
    public ?string $username;
    public bool $verified;
    public int $date;
    public ?string $code;
    public bool $show_publicly;
    public int $last_sync;

    /**
     * @param object $row
     */
    public function __construct(object $row) {
        $this->id = $row->id;
        $this->integration_id = $row->integration_id;
        $this->user_id = $row->user_id;
        $this->identifier = $row->identifier;
        $this->username = $row->username;
        $this->verified = $row->verified;
        $this->date = $row->date;
        $this->code = $row->code;
        $this->show_publicly = $row->show_publicly;
        $this->last_sync = $row->last_sync;
    }

}
