<?php
/**
 * CookieConsent module main class
 *
 * @package Modules\CookieConsent
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */
class CookieConsent {

    /**
     * Generates the cookie consent JavaScript script.
     *
     * @param array $options Array of options
     * @return string Cookie consent JavaScript
     */
    public static function generateScript(array $options): string {
        $script_options = [];
        $background_colour = '#000';
        $text_colour = '#000';
        $button_text_colour = '#f1d600';
        $border_colour = '#f1d600';

        if (
            isset($options['position'])
            && in_array($options['position'], ['top', 'top_static', 'bottom-left', 'bottom-right'])
        ) {
            if ($options['position'] == 'top_static') {
                $script_options['position'] = 'bottom-right';
            } else {
                $script_options['position'] = $options['position'];
            }
        }

        if (isset($options['colours'])) {
            if ($options['colours']['background']) {
                $background_colour = Output::getClean($options['colours']['background']);
            }

            if ($options['colours']['text']) {
                $text_colour = Output::getClean($options['colours']['text']);
            }

            if ($options['colours']['button_text']) {
                $button_text_colour = Output::getClean($options['colours']['button_text']);
            }

            if ($options['colours']['border']) {
                $border_colour = Output::getClean($options['colours']['border']);
            }
        }

        if (
            isset($options['theme'])
            && in_array($options['theme'], ['classic', 'edgeless'])
        ) {
            $script_options['theme'] = $options['theme'];
        }

        if (isset($options['type'])
            && in_array($options['type'], ['opt-out', 'opt-in'])
        ) {
            $script_options['type'] = $options['type'];
        }

        $script_options['palette'] = [
            'button' => ['background' => 'transparent', 'text' => $button_text_colour, 'border' => $border_colour],
            'popup' => ['background' => $background_colour, 'text' => $text_colour],
        ];

        $script_options['content'] = [
            'policy' => $options['cookies'],
            'message' => $options['message'],
            'deny' => $options['dismiss'],
            'allow' => $options['allow'],
            'link' => $options['link'],
            'href' => $options['href'],
        ];

        $json = json_encode($script_options, JSON_PRETTY_PRINT);

        return str_replace(
            '//"{x}"',
            substr($json, 1, -1),
            file_get_contents(ROOT_PATH . '/modules/Cookie Consent/assets/js/template.js')
        );
    }
}
