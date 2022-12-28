<?php
declare(strict_types=1);

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
     * @var ?DB $_db
     */
    private static ?DB $_db;

    public function __construct() {
        if (!isset(self::$_db)) {
            self::$_db = DB::getInstance();
        }
    }

    /**
     * Creates an alert for the specified user.
     *
     * @param string $user_id Contains the ID of the user who we are creating the alert for.
     * @param string $type Contains the alert type, eg 'tag' for user tagging.
     * @param array<string, string> $text_short Contains the alert text in short form for the dropdown.
     * @param array<string, string> $text Contains full information about the alert.
     * @param string $link Contains link to view the alert, defaults to #.
     *
     * @throws Exception|RuntimeException If the language file cannot be found.
     */
    public static function create(string $user_id, string $type, array $text_short, array $text, string $link = '#'): void {
        $language = self::$_db->query('SELECT nl2_languages.short_code AS `short_code` FROM nl2_users LEFT JOIN nl2_languages ON nl2_languages.id = nl2_users.language_id WHERE nl2_users.id = ?', [$user_id]);

        if (!$language->count()) {
            return;
        }

        $language = new Language($text_short['path'], $language->first()->short_code);

        self::$_db->insert('alerts', [
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
     * @param string $user_id Contains the ID of the user who we are getting alerts for.
     * @param bool $all Do we want to get all alerts (including read), or not; defaults to false).
     *
     * @return array<int, array<string, mixed>> All their alerts.
     */
    public static function getAlerts(string $user_id, bool $all = false): array {
        if ($all) {
            return self::$_db->get('alerts', ['user_id', $user_id])->results();
        }

        return self::$_db->query('SELECT * FROM nl2_alerts WHERE user_id = ? AND `read` = 0', [$user_id])->results();
    }

    /**
     * Get a users unread messages.
     *
     * @param string $user_id The ID of the user who we are getting messages for.
     * @param bool $all Get all alerts (including read), or not. Defaults to false.
     *
     * @return array<int, array<string, mixed>> All their messages matching the $all filter.
     */
    public static function getPMs(string $user_id, bool $all = false): array {
        $pms = self::$_db->get('private_messages_users', ['user_id', $user_id])->results();
        $results = [];

        foreach ($pms as $pm) {
            if ($all || $pm->read === 0) {
                $pm_full = self::$_db->get('private_messages', ['id', $pm->pm_id])->results();

                if (!count($pm_full)) {
                    continue;
                }

                $results[] = self::getPMInfo($pm_full[0]);
            }
        }

        return $results;
    }

    /**
     * Extracts the relevant information from a PM object.
     *
     * @param object $pm The PM object.
     *
     * @return array<string, mixed> The relevant information for the PM.
     */
    private static function getPMInfo(object $pm): array {
        return [
            'id' => $pm->id,
            'title' => Output::getClean($pm->title),
            'created' => $pm->created,
            'author_id' => $pm->author_id,
            'last_reply_user' => $pm->last_reply_user,
            'last_reply_date' => $pm->last_reply_date
        ];
    }
}
