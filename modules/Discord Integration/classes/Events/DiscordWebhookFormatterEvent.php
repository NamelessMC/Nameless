<?php

class DiscordWebhookFormatterEvent extends AbstractEvent {

    public string $event;

    /**
     * @var array|DiscordWebhookBuilder $format
     */
    public $format;
    public array $data;

    /**
     * @param string $event
     * @param DiscordWebhookBuilder|array $format
     * @param array $data
     */
    public function __construct(string $event, $format, array $data) {
        $this->event = $event;
        $this->format = $format;
        $this->data = $data;
    }

    public static function description(): string {
        return 'Discord webhook formatter';
    }

    public static function return(): bool {
        return true;
    }

    public static function internal(): bool {
        return true;
    }
}
