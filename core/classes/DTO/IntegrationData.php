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
class IntegrationData {
    public string $id;
    public string $name;
    public bool $enabled;
    public bool $can_unlink;
    public bool $required;
    public int $order;

    /**
     * @param object $row
     */
    public function __construct(object $row) {
        $this->id = $row->id;
        $this->name = $row->name;
        $this->enabled = $row->enabled;
        $this->can_unlink = $row->can_unlink;
        $this->required = $row->required;
        $this->order = $row->order;
    }

}
