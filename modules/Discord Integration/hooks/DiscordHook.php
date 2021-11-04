<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  Discord webhook handler class
 */

class DiscordHook {

    public static function execute(array $params = []): void {
        // Ensure hook is compatible
        $return = [];
        if ($params['event'] == 'registerUser') {
            $return['username'] = SITE_NAME;
            $return['content'] = '';
            $return['embeds'] = [[
                'author' => [
                    'name' => Output::getClean($params['username']),
                    'url' => $params['url'],
                    'icon_url' => $params['avatar_url']
                ],
                'description' => str_replace('{x}', Output::getClean($params['username']), $params['language']->get('user', 'user_x_has_registered'))
            ]];
        } else {
            $content = html_entity_decode(str_replace(['&nbsp;', '&bull;'], [' ', ''], $params['content_full']));
            if (mb_strlen($content) > 512) {
                $content = mb_substr($content, 0, 512) . '...';
            }

            $return['username'] = $params['username'] . ' | ' . SITE_NAME;
            $return['avatar_url'] = $params['avatar_url'];
            $return['embeds'] = [[
                'description' =>  $content,
                'title' => $params['title'],
                'url' => $params['url'],
                'footer' => ['text' => $params['content']]
            ]];
        }

        $json = json_encode($return, JSON_UNESCAPED_SLASHES);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $params['webhook']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $response = curl_exec($ch);
        $response = json_decode($response, true);

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 204)
            trigger_error($response['message']);

        curl_close($ch);
    }
}
