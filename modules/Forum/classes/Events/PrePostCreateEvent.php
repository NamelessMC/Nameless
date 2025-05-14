<?php

class PrePostCreateEvent extends AbstractEvent {

    public string $content;
    public User $user;
    public string $mention_notification_type;
    public AlertTemplate $mention_notification_alert_template;
    public EmailTemplate $mention_notification_email_template;

    public function __construct(
        string $content,
        User $user,
        string $mention_notificiation_type,
        AlertTemplate $mention_notification_alert_template,
        EmailTemplate $mention_notification_email_template,
    ) {
        $this->content = $content;
        $this->user = $user;
        $this->mention_notification_type = $mention_notificiation_type;
        $this->mention_notification_alert_template = $mention_notification_alert_template;
        $this->mention_notification_email_template = $mention_notification_email_template;
    }

    public static function internal(): bool {
        return true;
    }
}
