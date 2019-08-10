<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr6
 *
 *  Discord hook handler class
 */

class DiscordHook {
    private static $_url = '';

    // Set hook URL
    public static function setURL($url){
        self::$_url = $url;
    }

    // Execute hook
    public static function execute($params = array()){
        // Ensure URL has been set
        if(!strlen(self::$_url))
            return false;

        // Ensure hook is compatible
        $return = array();
        if(isset($params['username']) && isset($params['content']) && isset($params['avatar_url'])){
            if($params['event'] == 'registerUser') {
                $return['username'] = SITE_NAME;
                $return['content'] = '';
                $return['embeds'] = array(array(
                    'author' => array(
                        'name' => Output::getClean($params['username']),
                        'url' => $params['url'],
                        'icon_url' => $params['avatar_url']
                    ),
                    'description' => str_replace('{x}', Output::getClean($params['username']), $params['language']->get('user', 'user_x_has_registered'))
                ));
            } else {
                $return['username'] = $params['username'] . ' | ' . SITE_NAME;
                //$return['content'] = $params['content'];
                $return['avatar_url'] = $params['avatar_url'];
                $return['embeds'] = array(array(
                    'description' => substr(str_replace(array('&nbsp;', '&bull;'), array(' ', ''), $params['content_full']), 0, 512) . '...',
                    'title' => $params['title'],
                    'url' => $params['url'],
                    'footer' => array('text' => $params['content'])
                ));
            }

            $json = json_encode($return, JSON_UNESCAPED_SLASHES);

            $ch = curl_init();

			if(isset($params['webhook'])) {
				curl_setopt($ch, CURLOPT_URL, $params['webhook']);
			} else {
				curl_setopt($ch, CURLOPT_URL, self::$_url);
			}
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

            $response = curl_exec($ch);
            $response = json_decode($response, true);

            if(curl_getinfo($ch, CURLINFO_HTTP_CODE) != 204)
                trigger_error($response['message'], E_USER_NOTICE);

            curl_close($ch);
        }

        return true;
    }
}
