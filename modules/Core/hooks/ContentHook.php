<?php
declare(strict_types=1);

/**
 * Content hooks
 *
 * @package Core\Hooks
 * @author Samerton
 * @version 2.0.0 pre-13
 * @license MIT
 */
class ContentHook extends HookBase {

    /**
     * @param array<string, string> $params
     *
     * @return array<string, string>
     */
    public static function codeTransform(array $params = []): array {
        if (parent::validateParams($params, ['content'])) {
            $domDocument = new DOMDocument();
            $domDocument->loadHTML((string)mb_convert_encoding($params['content'], 'HTML-ENTITIES', 'UTF-8'));
            $tags = $domDocument->getElementsByTagName('code');
            foreach ($tags as $tag) {
                $code = '';
                $i = 1;
                foreach ($tag->childNodes as $child) {
                    $toAppend = Output::getClean($domDocument->saveHTML($child)) ?? '';

                    // </code> doesn't always get stripped for some reason
                    if ($i === $tag->childNodes->length && substr_compare($toAppend, '&lt;/code&gt;', -13, 13) === 0) {
                        $toAppend = substr($toAppend, 0, -13);
                    }

                    $code .= $toAppend;
                    $i++;
                }

                $tag->nodeValue = $code;
            }

            $params['content'] = $domDocument->saveHTML();
        }

        return $params;
    }

    /**
     * @param array<string, string> $params
     *
     * @return array<string, string>
     */
    public static function decode(array $params = []): array {
        if (parent::validateParams($params, ['content'])) {
            $params['content'] = Output::getDecoded($params['content']);
        }

        return $params;
    }

    /**
     * @param array $params
     * @return array<string, string>
     */
    public static function purify(array $params = []): array {
        if (parent::validateParams($params, ['content']) && empty($params['skip_purify'])) {
            $params['content'] = Output::getPurified($params['content'], true);
        }

        return $params;
    }

    /**
     * @param array<string, string> $params
     *
     * @return array<string, string>
     */
    public static function renderEmojis(array $params = []): array {
        if (parent::validateParams($params, ['content'])) {
            $params['content'] = Text::renderEmojis($params['content']);
        }

        return $params;
    }

    /**
     * @param array<string, string> $params
     *
     * @return array<string, string>
     */
    public static function replaceAnchors(array $params = []): array {
        if (parent::validateParams($params, ['content'])) {
            $params['content'] = URL::replaceAnchorsWithText($params['content']);
        }

        return $params;
    }
}
