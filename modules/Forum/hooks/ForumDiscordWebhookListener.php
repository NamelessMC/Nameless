<?php

class ForumDiscordWebhookListener {

    public static function execute(DiscordWebhookFormatterEvent $event): DiscordWebhookFormatterEvent {
        $format = $event->format;

        if ($format instanceof DiscordWebhookBuilder) {
            $format->setUsername($format->getUsername() . ' (Edited by Forum, on the ' . $event->event . ' event)');
        }

        return $event;
    }
}
