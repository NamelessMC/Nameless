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
     * @param int       $authorId        User ID that sent the notification
     * @param ?callable $contentCallback Optional callback to perform for each recipient's content
     * @param bool      $skipPurify      Whether to skip content purifying, default false
     *
     * @throws NotificationTypeNotFoundException
     */
    public function __construct(
        string $type,
        string $title,
        string $content,
        $recipients,
        int $authorId,
        callable $contentCallback = null,
        bool $skipPurify = false
    ) {
        if (!in_array($type, array_column(self::getTypes(), 'key'))) {
            throw new NotificationTypeNotFoundException("Type $type not registered");
        }

        $this->_authorId = $authorId;
        $this->_skipPurify = $skipPurify;
        $this->_title = $title;
        $this->_type = $type;

        if (!is_array($recipients)) {
            $recipients = [$recipients];
        }

        $this->_recipients = array_map(static function ($recipient) use ($content, $contentCallback, $skipPurify, $title) {
            $newContent = $contentCallback($recipient, $title, $content, $skipPurify);
            return ['id' => $recipient, 'content' => $newContent];
        }, $recipients);
    }

    public function send(): void {
        /** @var array $recipient */
        foreach ($this->_recipients as $recipient) {
            $id = $recipient['id'];
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
                $this->sendAlert($id, $content);
            }
            if ($preferences->email) {
                $this->sendEmail($id, $content);
            }
        }
    }

    private function sendAlert(int $userId, string $content): void {
        Alert::send($userId, $this->_title, $content, null, $this->_skipPurify);
    }

    private function sendEmail(int $userId, string $content): void {
        $task = (new SendEmail())->fromNew(
            Module::getIdFromName('Core'),
            'Send Email Notification',
            [
                'content' => $content,
                'title' => $this->_title,
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
     * @param string $value Human readable
     * @param int $moduleId
     * @return void
     */
    public static function addType(string $type, string $value, int $moduleId): void {
        self::$_types[] = ['key' => $type, 'value' => $value, 'module' => $moduleId];
    }

    /**
     * Returns all registered notification types
     * @return array
     */
    public static function getTypes(): array {
        return self::$_types;
    }
}
