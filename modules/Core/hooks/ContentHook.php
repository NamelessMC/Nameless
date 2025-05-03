<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.3.0
 *
 *  Content hooks
 */

class ContentHook extends HookBase {

    /**
     * Transforms code blocks
     *
     * @deprecated Will be removed in 2.2.0!
     *
     * @param AbstractEvent $event
     */
    public static function codeTransform(AbstractEvent $event): void {
        if (isset($event->content)) {
            $domDocument = new DOMDocument();
            $domDocument->loadHTML(mb_convert_encoding($event->content, 'HTML-ENTITIES', 'UTF-8'));
            $tags = $domDocument->getElementsByTagName('code');
            foreach ($tags as $tag) {
                $code = '';
                $i = 1;
                foreach ($tag->childNodes as $child) {
                    $toAppend = Output::getClean($domDocument->saveHTML($child));

                    // </code> doesn't always get stripped for some reason
                    if ($i === $tag->childNodes->length && substr_compare($toAppend, '&lt;/code&gt;', -13, 13) === 0) {
                        $toAppend = substr($toAppend, 0, -13);
                    }

                    $code .= $toAppend;
                    $i++;
                }

                $tag->nodeValue = $code;
            }

            $event->content = $domDocument->saveHTML();
        }
    }

    /**
     * Decodes post content
     *
     * @deprecated Will be removed in 2.3.0!
     *
     * @param AbstractEvent $event
     */
    public static function decode(AbstractEvent $event): void {
        if (isset($event->content)) {
            $event->content = Output::getDecoded($event->content);
        }
    }

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
