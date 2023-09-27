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

    private array $_recipients = [];
    private string $_title;
    private string $_type;

    private static array $_types = [];

    /**
     * Instantiate a new notification
     *
     * @param string $type Type of notification
     * @param string $title Title of notification
     * @param string $content Notification content
     * @param int|int[] $recipients Notification recipient or recipients - array of user IDs
     * @param ?callable $contentCallback Optional callback to perform for each recipient's content
     *
     * @throws NotificationTypeNotFoundException
     */
    public function __construct(
        string $type,
        string $title,
        string $content,
        $recipients,
        callable $contentCallback = null
    ) {
        if (!in_array($type, self::getTypes())) {
            throw new NotificationTypeNotFoundException("Type $type not registered");
        }

        $this->_title = $title;
        $this->_type = $type;

        if (!is_array($recipients)) {
            $recipients = [$recipients];
        }

        $this->_recipients = array_map(static function ($recipient) use ($content, $contentCallback) {
            $newContent = $contentCallback($recipient, $content);
            return ['id' => $recipient, 'content' => $newContent];
        }, $recipients);
    }

    public function send(): void {
        /** @var int $recipient */
        foreach ($this->_recipients as $recipient) {
            $preferences = DB::getInstance()->query(
                <<<SQL
                    SELECT `alert`, `email`
                    FROM nl2_users_notification_preferences
                    WHERE `type` = ? AND `user_id` = ?
                SQL,
                [$this->_type, $recipient]
            )->first();

            if ($preferences->alert) {
                $this->sendAlert();
            }
            if ($preferences->email) {
                $this->sendEmail();
            }
        }
    }

    public function sendAlert(): void {

    }

    public function sendEmail(): void {

    }

    /**
     * Register a custom notification type
     * @param string $type
     * @return void
     */
    public static function addType(string $type): void {
        self::$_types[] = $type;
    }

    /**
     * Returns all registered notification types
     * @return string[]
     */
    public static function getTypes(): array {
        return self::$_types;
    }
}
