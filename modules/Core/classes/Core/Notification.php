<?php
/**
 * Notification class to handle sending notifications to a user or users
 * Notifications can be alerts or emails
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.2.0
 * @license MIT
 */

class Notification {

    private int $_authorId;
    private array $_recipients = [];
    private bool $_skipPurify;
    private string $_type;
    private ?string $_alertUrl = null;

    private static array $_types = [];

    /**
     * Instantiate a new notification
     *
     * @param string $type Type of notification
     * @param string|LanguageKey $title Title of notification
     * @param string|LanguageKey $content Notification content. For alerts, if $alertUrl is set, this will ignored. If $alertUrl is not set, this will be the content of the alert. This will always be the content of the email.
     * @param int|int[] $recipients Notification recipient or recipients - array of user IDs
     * @param int       $authorId        User ID that sent the notification
     * @param ?callable $contentCallback Optional callback to perform for each recipient's content
     * @param bool      $skipPurify      Whether to skip content purifying, default false
     * @param ?string $alertUrl        Optional URL to link to when clicking the alert
     *
     * @throws NotificationTypeNotFoundException
     */
    public function __construct(
        string $type,
        string|LanguageKey $title,
        string|LanguageKey $content,
        int|array $recipients,
        int $authorId,
        ?callable $contentCallback = null,
        bool $skipPurify = false,
        ?string $alertUrl = null,
    ) {
        if (!in_array($type, array_column(self::getTypes(), 'key'))) {
            throw new NotificationTypeNotFoundException("Type $type not registered");
        }

        $this->_authorId = $authorId;
        $this->_skipPurify = $skipPurify;
        $this->_type = $type;
        $this->_alertUrl = $alertUrl;

        if (!is_array($recipients)) {
            $recipients = [$recipients];
        }

        if (count($recipients) === 0) {
            return;
        }

        if ($title instanceof LanguageKey || $content instanceof LanguageKey) {
            $languageCodes = DB::getInstance()->query(
                'SELECT nl2_users.id, COALESCE(nl2_languages.short_code, NULL) AS `short_code` FROM nl2_users LEFT JOIN nl2_languages ON nl2_languages.id = nl2_users.language_id WHERE nl2_users.id IN (' . implode(',', array_map(static fn ($_) => '?', $recipients)) . ')',
                $recipients
            )->results();
            $languageCodes = array_column($languageCodes, 'short_code', 'id');
        }

        $this->_recipients = array_map(static function ($recipientId) use ($content, $contentCallback, $skipPurify, $title, $languageCodes) {
            $recipientLanguageCode = $languageCodes[$recipientId] ?? DEFAULT_LANGUAGE;
            if ($title instanceof LanguageKey) {
                $title = $title->translate($recipientLanguageCode);
            }
            if ($content instanceof LanguageKey) {
                $content = $content->translate($recipientLanguageCode);
            }

            $newContent = $contentCallback ? $contentCallback($recipientId, $title, $content, $skipPurify) : $content;

            return [
                'id' => $recipientId,
                'title' => $title,
                'content' => $newContent
            ];
        }, $recipients);
    }

    public function send(): void {
        /** @var array $recipient */
        foreach ($this->_recipients as $recipient) {
            $id = $recipient['id'];
            $title = $recipient['title'];
            $content = $recipient['content'];

            $preferences = DB::getInstance()->query(
                <<<SQL
                    SELECT `alert`, `email`
                    FROM nl2_users_notification_preferences
                    WHERE `type` = ? AND `user_id` = ?
                SQL,
                [$this->_type, $id]
            )->first();

            if ($preferences->alert) {
                $this->sendAlert($id, $title, $content);
            }
            if ($preferences->email) {
                $this->sendEmail($id, $title, $content);
            }
        }
    }

    private function sendAlert(int $userId, string $title, string $content): void {
        Alert::send(
            $userId,
            $title,
            // if $this->_alertUrl is set, we don't want to send the content as the alert content
            $this->_alertUrl ? null : $content,
            $this->_alertUrl,
            $this->_skipPurify
        );
    }

    private function sendEmail(int $userId, string $title, string $content): void {
        $task = (new SendEmail())->fromNew(
            Module::getIdFromName('Core'),
            'Send Email Notification',
            [
                'content' => $content,
                'title' => SITE_NAME . ' - ' . $title,
            ],
            date('U'), // TODO: schedule a date/time?
            'User',
            $userId,
            false,
            null,
            $this->_authorId
        );

        Queue::schedule($task);
    }

    /**
     * Register a custom notification type
     * @param string $type
     * @param string $value              Human-readable
     * @param int    $moduleId
     * @param array  $defaultPreferences Set of default preferences in form preferenceKey => true/false
     * @return void
     */
    public static function addType(string $type, string $value, int $moduleId, array $defaultPreferences = []): void {
        self::$_types[] = [
            'key' => $type,
            'value' => $value,
            'module' => $moduleId,
            'defaultPreferences' => $defaultPreferences
        ];
    }

    /**
     * Returns all registered notification types
     * @return array
     */
    public static function getTypes(): array {
        return self::$_types;
    }
}
