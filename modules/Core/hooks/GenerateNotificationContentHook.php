<?php

class GenerateNotificationContentHook {

    public static function execute(GenerateNotificationContentEvent $event): GenerateNotificationContentEvent {

        $content = str_replace(
            [
                '{username}',
                '{sitename}',
            ],
            [
                $event->user->getDisplayname(),
                SITE_NAME,
            ],
            $event->content
        );

        $event->content = $content;

        return $event;
    }
}
