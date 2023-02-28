<?php

use Astrotomic\Twemoji\Twemoji;
use JoyPixels\Client;

/**
 * Helps with common text related tasks.
 *
 * @package NamelessMC\Misc
 * @author Aberdeener
 * @version 2.0.0
 * @license MIT
 */
class Text {

    /**
     * Truncates text.
     *
     * Cuts a string to the length of $length and replaces the last characters
     * with the ending if the text is longer than length.
     *
     * ### Options:
     *
     * - `ending` Will be used as Ending and appended to the trimmed string
     * - `exact` If false, $text will not be cut mid-word
     * - `html` If true, HTML tags would be handled correctly
     * @link http://book.cakephp.org/view/1469/Text#truncate-1625
     * @link https://github.com/cakephp/cakephp/blob/master/LICENSE
     *
     * @param string $text String to truncate.
     * @param int $length Length of returned string, including ellipsis.
     * @param array $options An array of html attributes and options.
     * @return string Trimmed string.
     */
    public static function truncate(string $text, int $length = 750, array $options = []): string {
        $default = [
            'ending' => '...', 'exact' => true, 'html' => false
        ];
        $options = array_merge($default, $options);
        extract($options);

        if ($html) {
            if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            $totalLength = mb_strlen(strip_tags($ending));
            $openTags = [];
            $truncate = '';

            preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
            foreach ($tags as $tag) {
                if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                    if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                        array_unshift($openTags, $tag[2]);
                    } else {
                        if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                            $pos = array_search($closeTag[1], $openTags);
                            if ($pos !== false) {
                                array_splice($openTags, $pos, 1);
                            }
                        }
                    }
                }
                $truncate .= $tag[1];

                $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
                if ($contentLength + $totalLength > $length) {
                    $left = $length - $totalLength;
                    $entitiesLength = 0;
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entitiesLength <= $left) {
                                $left--;
                                $entitiesLength += mb_strlen($entity[0]);
                            } else {
                                break;
                            }
                        }
                    }

                    $truncate .= mb_substr($tag[3], 0, $left + $entitiesLength);
                    break;
                }

                $truncate .= $tag[3];
                $totalLength += $contentLength;
                if ($totalLength >= $length) {
                    break;
                }
            }
        } else {
            if (mb_strlen($text) <= $length) {
                return $text;
            }

            $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
        }
        if (!$exact) {
            $spacepos = mb_strrpos($truncate, ' ');
            if ($html) {
                $bits = mb_substr($truncate, $spacepos);
                preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                if (!empty($droppedTags)) {
                    foreach ($droppedTags as $closingTag) {
                        if (!in_array($closingTag[1], $openTags)) {
                            array_unshift($openTags, $closingTag[1]);
                        }
                    }
                }
            }
            $truncate = mb_substr($truncate, 0, $spacepos);
        }
        $truncate .= $ending;

        if ($html) {
            foreach ($openTags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }

    /**
     * Wrap text in HTML `<strong>` tags. Used for when variables in translations are bolded,
     * since we want as little HTML in the translation strings as possible.
     *
     * @param string $text Text to wrap
     * @return string Text wrapped in `<strong>` tags
     */
    public static function bold(string $text): string {
        return '<strong>' . $text . '</strong>';
    }

    /**
     * Replace emojis with their style equivalent.
     *
     * @param string $text Text to parse
     * @param string|null $force_style Style to apply to the emoji image, will use the site default if null
     * @return string Text with emojis replaced with URLs to their Twemoji equivalent.
     */
    public static function renderEmojis(string $text, string $force_style = null): string {
        $style = $force_style ?? Util::getSetting('emoji_style', 'twemoji');
        switch ($style) {
            case 'twemoji':
                return Twemoji::text($text)->toHtml();
            case 'joypixels':
                // Jank workaround can be removed if/when https://github.com/joypixels/emoji-toolkit/issues/55 is implemented
                return (new class extends Client {
                    public $emojiSize = '64';
                    public $ignoredRegexp = '';
                })->toImage($text);
            case 'native':
            default:
                return $text;
        }
    }

    /**
     * @param string|null $content HTML content to use in Discord embed
     * @return string HTML content with tags removed and newlines converted to Discord's linebreaks
     */
    public static function embedSafe(?string $content): string {
        if ($content === null) {
            return '';
        }

        $content = strip_tags(str_ireplace(
            ['&nbsp;', '&bull;', '<br />', '<br>', '<br/>'],
            [' ', '', "\r\n", "\r\n", "\r\n"],
            $content
        ));

        return self::truncate($content, 512, [
            'html' => true,
        ]);
    }
}
