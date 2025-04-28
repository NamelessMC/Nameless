<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.3.0
 *
 *  Content hooks
 */

class ContentHook extends HookBase
{
    public static function purify(AbstractEvent $event): void {
        if (isset($event->content) && empty($event->skip_purify)) {
            $event->content = Output::getPurified($event->content, true);
        }
    }

    public static function renderEmojis(AbstractEvent $event): void {
        if (isset($event->content)) {
            $event->content = Text::renderEmojis($event->content);
        }
    }

    public static function replaceAnchors(AbstractEvent $event): void {
        if (isset($event->content)) {
            $event->content = URL::replaceAnchorsWithText($event->content);
        }
    }
}
