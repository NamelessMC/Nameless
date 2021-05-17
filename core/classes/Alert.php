<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Alert class
 */
class Alert {

    /**
     * Creates an alert for the specified user.
     * 
     * @param int $user_id Contains the ID of the user who we are creating the alert for.
     * @param string $type Contains the alert type, eg 'tag' for user tagging.
     * @param array $text_short Contains the alert text in short form for the dropdown.
     * @param array $text Contains full information about the alert.
     * @param string|null $link Contains link to view the alert, defaults to #
     * @throws Exception if unable to create alert
     */
    public static function create($user_id, $type, $text_short, $text, $link = '#') {
        $db = DB::getInstance();

        $language = $db->query('SELECT nl2_languages.name AS `name` FROM nl2_users LEFT JOIN nl2_languages ON nl2_languages.id = nl2_users.language_id WHERE nl2_users.id = ?', array($user_id));

        if ($language->count()) {
            $language_name = $language->first()->name;
            $language = new Language($text_short['path'], $language_name);

            if (!$db->insert('alerts', array(
                'user_id' => $user_id,
                'type' => $type,
                'url' => $link,
                'content_short' => str_replace((isset($text_short['replace']) ? $text_short['replace'] : ''), (isset($text_short['replace_with']) ? $text_short['replace_with'] : ''), $language->get($text_short['file'], $text_short['term'])),
                'content' => str_replace((isset($text['replace']) ? $text['replace'] : ''), (isset($text['replace_with']) ? $text['replace_with'] : ''), $language->get($text['file'], $text['term'])),
                'created' => date('U')
            ))) {
                throw new Exception('There was a problem creating an alert.');
            }
        }
    }

    /**
     * Get user alerts.
     * 
     * @param int $user_id Contains the ID of the user who we are getting alerts for.
     * @param bool|null $all Do we want to get all alerts (including read), or not; defaults to false).
     * @return array All their alerts.
     */
    public static function getAlerts($user_id, $all = false) {
        $db = DB::getInstance();

        if ($all == true) {
            return $db->get('alerts', array('user_id', '=', $user_id))->results();
        }

        return $db->query('SELECT * FROM nl2_alerts WHERE user_id = ? AND `read` = 0', array($user_id))->results();
    }

    /**
     * Get user unread messages.
     * 
     * @param int $user_id Contains the ID of the user who we are getting messages for.
     * @param bool|null $all Do we want to get all alerts (including read), or not; defaults to false)
     * @return array All their messages matching the $all filter.
     */
    public static function getPMs($user_id, $all = false) {
        $db = DB::getInstance();

        if ($all == true) {
            $pms_access = $db->get('private_messages_users', array('user_id', '=', $user_id))->results();
            $pms = array();

            foreach ($pms_access as $pm) {
                // Get actual PM information
                $pm_full = $db->get('private_messages', array('id', '=', $pm->pm_id))->results();

                if (!count($pm_full)) continue;
                else $pm_full = $pm_full[0];

                $pms[] = array(
                    'id' => $pm_full->id,
                    'title' => Output::getClean($pm_full->title),
                    'created' => $pm_full->created,
                    'author_id' => $pm_full->author_id,
                    'last_reply_user' => $pm_full->last_reply_user,
                    'last_reply_date' => $pm_full->last_reply_date
                );
            }

            return $pms;
        }

        $pms = $db->get('private_messages_users', array('user_id', '=', $user_id))->results();
        $unread = array();

        foreach ($pms as $pm) {
            if ($pm->read == 0) {
                $pm_full = $db->get('private_messages', array('id', '=', $pm->pm_id))->results();

                if (!count($pm_full)) continue;
                else $pm_full = $pm_full[0];

                $unread[] = array(
                    'id' => $pm_full->id,
                    'title' => Output::getClean($pm_full->title),
                    'created' => $pm_full->created,
                    'author_id' => $pm_full->author_id,
                    'last_reply_user' => $pm_full->last_reply_user,
                    'last_reply_date' => $pm_full->last_reply_date
                );
            }
        }
        
        return $unread;
    }
}
