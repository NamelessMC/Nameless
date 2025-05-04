<?php

class NotificationEmailTemplate extends EmailTemplate
{
    private string $subject;

    public function __construct(string $subject, string $content, ?string $link)
    {
        $this->subject = $subject;

        $this->addPlaceholder('[Title]', $subject);
        $this->addPlaceholder('[Content]', $content);

        // Register all notifications to nl2_alerts even if alert preference is off? Then all links can be sent to /user/alerts/?id={ID} then it will direct user + mark as read
        $this->addPlaceholder('[Link]', $link ?? URL::getSelfURL());

        parent::__construct();
    }

    public function subject(): string
    {
        return $this->subject;
    }
}