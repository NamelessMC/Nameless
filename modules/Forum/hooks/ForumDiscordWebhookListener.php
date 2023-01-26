<?php

class ForumDiscordWebhookListener {

    public static function execute(DiscordWebhookFormatterEvent $event): DiscordWebhookFormatterEvent {
        $format = $event->format;

        if ($format instanceof DiscordWebhookBuilder) {
            $format->username('Edited by Forum moduleEEE, on the ' . $event->event . ' event');
        }

        return $event;
    }
}
