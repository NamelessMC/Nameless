<?php

class UserProfileField extends ProfileField {

    public ?string $value;
    public ?int $upf_id;

    public function __construct(object $row) {
        parent::__construct($row);
        $this->value = $row->value;
        $this->upf_id = $row->upf_id;
    }

}
