<?php

class PreUserRegistrationEvent extends AbstractEvent {

    use Cancellable;

    public array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public static function internal(): bool {
        return true;
    }
}
