<?php
/**
 * Provides access to create & get alerts for a user, as well as their PMs.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class Alert {

    /**
     * Creates an alert for the specified user.
     *
     * @param int $user_id Contains the ID of the user who we are creating the alert for.
     * @param string $type Contains the alert type, eg 'tag' for user tagging.
     * @param array $text_short Contains the alert text in short form for the dropdown.
     * @param array $text Contains full information about the alert.
     * @param string $link Contains link to view the alert, defaults to #.
     */
    public static function create(int $user_id, string $type, array $text_short, array $text, string $link = '#'): void {
        $db = DB::getInstance();

        $language = $db->query('SELECT nl2_languages.short_code AS `short_code` FROM nl2_users LEFT JOIN nl2_languages ON nl2_languages.id = nl2_users.language_id WHERE nl2_users.id = ?', [$user_id]);

        if (!$language->count()) {
            return;
        }

        $language = new Language($text_short['path'], $language->first()->short_code);

        $db->insert('alerts', [
            'user_id' => $user_id,
            'type' => $type,
            'url' => $link,
            'content_short' => str_replace(($text_short['replace'] ?? ''), ($text_short['replace_with'] ?? ''), $language->get($text_short['file'], $text_short['term'])),
            'content' => str_replace(($text['replace'] ?? ''), ($text['replace_with'] ?? ''), $language->get($text['file'], $text['term'])),
            'created' => date('U')
        ]);
    }

    /**
     * Get user alerts.
     *
     * @param int $user_id Contains the ID of the user who we are getting alerts for.
     * @param bool $all Do we want to get all alerts (including read), or not; defaults to false).
     *
     * @return array All their alerts.
     */
    public static function getAlerts(int $user_id, bool $all = false): array {
        $db = DB::getInstance();

        if ($all == true) {
            return $db->get('alerts', ['user_id', $user_id])->results();
        }

        return $db->query('SELECT * FROM nl2_alerts WHERE user_id = ? AND `read` = 0', [$user_id])->results();
    }

    /**
     * Get a users unread messages.
     *
     * @param int $user_id The ID of the user who we are getting messages for.
     * @param bool $all Get all alerts (including read), or not. Defaults to false.
     *
     * @return array All their messages matching the $all filter.
     */
    public static function getPMs(int $user_id, bool $all = false): array {
        $db = DB::getInstance();

        if ($all == true) {
            $pms_access = $db->get('private_messages_users', ['user_id', $user_id])->results();
            $pms = [];

            foreach ($pms_access as $pm) {
                // Get actual PM information
                $pm_full = $db->get('private_messages', ['id', $pm->pm_id])->results();

                if (!count($pm_full)) {
                    continue;
                }

                $pm_full = $pm_full[0];

                $pms[] = [
                    'id' => $pm_full->id,
                    'title' => Output::getClean($pm_full->title),
                    'created' => $pm_full->created,
                    'author_id' => $pm_full->author_id,
                    'last_reply_user' => $pm_full->last_reply_user,
                    'last_reply_date' => $pm_full->last_reply_date
                ];
            }

            return $pms;
        }

        $pms = $db->get('private_messages_users', ['user_id', $user_id])->results();
        $unread = [];

        foreach ($pms as $pm) {
            if ($pm->read == 0) {
                $pm_full = $db->get('private_messages', ['id', $pm->pm_id])->results();

                if (!count($pm_full)) {
                    continue;
                }

                $pm_full = $pm_full[0];

                $unread[] = [
                    'id' => $pm_full->id,
                    'title' => Output::getClean($pm_full->title),
                    'created' => $pm_full->created,
                    'author_id' => $pm_full->author_id,
                    'last_reply_user' => $pm_full->last_reply_user,
                    'last_reply_date' => $pm_full->last_reply_date
                ];
            }
        }

        return $unread;
    }
}
