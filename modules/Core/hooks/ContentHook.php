<?php

/**
 * Clone group event listener handler class
 *
 * @package Modules\Core\Hooks
 * @author Samerton
 * @version 2.0.0 pre-13
 * @license MIT
 */
class ContentHook extends HookBase {

    /**
     * @param array{content: ?string} $params
     *
     * @return array{content: ?string}
     */
    public static function codeTransform(array $params = ["content" => null]): array {
        if (!parent::validateParams($params, ["content"])) {
            return $params;
        }

        $domDocument = new DOMDocument();
        $domDocument->loadHTML(mb_convert_encoding($params['content'], 'HTML-ENTITIES', 'UTF-8'));
        $tags = $domDocument->getElementsByTagName('code');
        foreach ($tags as $tag) {
            $code = '';
            $count = 1;
            foreach ($tag->childNodes as $child) {
                $toAppend = Output::getClean($domDocument->saveHTML($child));

                // </code> doesn't always get stripped for some reason
                if ($count === $tag->childNodes->length && substr_compare($toAppend, '&lt;/code&gt;', -13, 13) === 0) {
                    $toAppend = substr($toAppend, 0, -13);
                }

                $code .= $toAppend;
                $count++;
            }

            $tag->nodeValue = $code;
        }

        return ['content' => $domDocument->saveHTML()];
    }

    /**
     * @param array{content: ?string} $params
     *
     * @return array{content: ?string}
     */
    public static function decode(array $params = ["content" => null]): array {
        return (parent::validateParams($params, ['content']))
            ? $params : ['content' => Output::getDecoded($params['content'])];
    }

    /**
     * @param array{content: ?string, skip_purify: ?bool} $params
     *
     * @return array{content: ?string}
     */
    public static function purify(array $params = ["content" => null, "skip_purify" => null]): array {
        return parent::validateParams($params, ['content', 'skip_purify']) || $params['skip_purify'] === false
            ? $params : ['content' => Output::getPurified($params['content'], true)];
    }

    /**
     * @param array{content: ?string} $params
     *
     * @return array{content: ?string}
     */
    public static function renderEmojis(array $params = ["content" => null]): array {
        return (parent::validateParams($params, ['content']))
            ? $params : ['content' => Text::renderEmojis($params['content'])];
    }

    /**
     * @param array{content: ?string} $params
     *
     * @return array{content: ?string}
     */
    public static function replaceAnchors(array $params = ["content" => null]): array {
        return (parent::validateParams($params, ['content']))
            ? $params : ['content' => URL::replaceAnchorsWithText($params['content'])];
    }
}
