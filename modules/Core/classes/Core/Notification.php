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

    private AlertTemplate $_alertTemplate;
    private EmailTemplate $_emailTemplate;
    private int $_authorId;
    private array $_recipients = [];
    private string $_type;
    private bool $_bypassNotificationSettings;

    private static array $_types = [];

    /**
     * Instantiate a new notification
     *
     * @param string             $type                       Type of notification
     * @param string|LanguageKey $title                      Title of notification
     * @param string|LanguageKey $content                    Notification content. For alerts, if $alertUrl is set, this will ignored. If $alertUrl is not set, this will be the content of the alert. This will always be the content of the email.
     * @param int|int[]          $recipients                 Notification recipient or recipients - array of user IDs
     * @param int                $authorId                   User ID that sent the notification
     * @param bool               $bypassNotificationSettings Whether to bypass the user's notification settings
     * @param ?string            $link                       Optional URL to link to when clicking the alert
     *
     * @throws NotificationTypeNotFoundException
     */
    public function __construct(
        string $type,
        string|LanguageKey $title,
        string|LanguageKey $content,
        int|array $recipients,
        int $authorId,
        bool $bypassNotificationSettings = false,
        ?string $link = null,
    ) {
        if (!in_array($type, array_column(self::getTypes(), 'key'))) {
            throw new NotificationTypeNotFoundException("Type $type not registered");
        }

        $this->_type = $type;
        $this->_alertTemplate = new AlertTemplate($title, $link ? null : $content, $link);
        $this->_emailTemplate = new NotificationEmailTemplate($title, $content, $link);
        $this->_authorId = $authorId;
        $this->_bypassNotificationSettings = $bypassNotificationSettings;

        if (!is_array($recipients)) {
            $recipients = [$recipients];
        }

        if (count($recipients) === 0) {
            return;
        }

        $languageCodes = DB::getInstance()->query(
            'SELECT nl2_users.id, COALESCE(nl2_languages.short_code, NULL) AS `short_code` FROM nl2_users LEFT JOIN nl2_languages ON nl2_languages.id = nl2_users.language_id WHERE nl2_users.id IN (' . implode(',', array_map(static fn ($_) => '?', $recipients)) . ')',
            $recipients
        )->results();
        $languageCodes = array_column($languageCodes, 'short_code', 'id');

        $this->_recipients = array_map(static function ($recipientId) use ($languageCodes) {
            return [
                'id' => $recipientId,
                'language_code' => $languageCodes[$recipientId] ?? DEFAULT_LANGUAGE,
            ];
        }, $recipients);
    }

    public function setAlertTemplate(AlertTemplate $template): void {
        $this->_alertTemplate = $template;
    }

    public function setEmailTemplate(EmailTemplate $template): void {
        $this->_emailTemplate = $template;
    }

    public function send(): void {
        /** @var array $recipient */
        foreach ($this->_recipients as $recipient) {
            $userId = $recipient['id'];
            $languageCode = $recipient['language_code'];

            if ($this->_bypassNotificationSettings) {
                $this->sendAlert($userId, $languageCode);
                $this->sendEmail($userId, $languageCode);
                continue;
            }

            $preferences = DB::getInstance()->query(
                <<<SQL
                    SELECT `alert`, `email`
                    FROM nl2_users_notification_preferences
                    WHERE `type` = ? AND `user_id` = ?
                SQL,
                [$this->_type, $userId]
            )->first();

            if ($preferences->alert) {
                $this->sendAlert($userId, $languageCode);
            }
            if ($preferences->email) {
                $this->sendEmail($userId, $languageCode);
            }
        }
    }

    private function sendAlert(int $userId, string $languageCode): void {
        if ($this->_alertTemplate->title instanceof LanguageKey) {
            $title = $this->_alertTemplate->title->translate($languageCode);
        } else {
            $title = $this->_alertTemplate->title;
        }

        if ($this->_alertTemplate->link) {
            $content = null;
        } else if ($this->_alertTemplate->content instanceof LanguageKey) {
            $content = $this->_alertTemplate->content->translate($languageCode);
        } else {
            $content = $this->_alertTemplate->content;
        }

        Alert::send(
            $userId,
            $title,
            // if the alert has a link set, we don't want to send the content as the alert content
            $content,
            $this->_alertTemplate->link,
        );
    }

    private function sendEmail(int $userId, string $languageCode): void {
        if ($this->_emailTemplate->subject() instanceof LanguageKey) {
            $content = $this->_emailTemplate->subject()->translate($languageCode);
        } else {
            $content = $this->_emailTemplate->subject();
        }

        $task = (new SendEmail())->fromNew(
            Module::getIdFromName('Core'),
            'Send Email Notification',
            [
                'subject' => $content,
                'content' => $this->_emailTemplate->renderContent($languageCode),
                'mailer' => str_replace('EmailTemplate', '', $this->_emailTemplate::class),
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
