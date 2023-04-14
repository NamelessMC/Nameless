<?php

class TopicReplyCreatedEvent extends AbstractEvent implements HasWebhookParams, DiscordDispatchable {

    public User $creator;
    public string $topic_title;
    public string $content;
    public int $topic_id;
    public ?array $available_hooks;

    public function __construct(
        User $creator,
        string $topic_title,
        string $content,
        int $topic_id,
        ?array $available_hooks
    ) {
        $this->creator = $creator;
        $this->topic_title = $topic_title;
        $this->content = $content;
        $this->topic_id = $topic_id;
        $this->available_hooks = $available_hooks;
    }

    public static function name(): string {
        return 'topicReply';
    }

    public static function description(): string {
        return (new Language(ROOT_PATH . '/modules/Forum/language'))->get('forum', 'topic_reply');
    }

    public function webhookParams(): array {
        $forum = new Forum();

        return [
            'user_id' => $this->creator->data()->id,
            'username' => $this->creator->getDisplayname(),
            'topic' => [
                'id' => $this->topic_id,
                'title' => $this->topic_title
            ],
            'content' => $this->content,
            'url' => URL::getSelfURL() . ltrim(URL::build('/forum/topic/' . urlencode($this->topic_id) . '-' . $forum->titleToURL($this->topic_title)), '/')
        ];
    }

    public function toDiscordWebhook(): DiscordWebhookBuilder {
        $language = new Language(ROOT_PATH . '/modules/Forum/language', DEFAULT_LANGUAGE);
        $forum = new Forum();

        return DiscordWebhookBuilder::make()
            ->setUsername($this->creator->getDisplayname() . ' | ' . SITE_NAME)
            ->setAvatarUrl($this->creator->getAvatar(128, true))
            ->setContent($language->get('forum', 'new_reply_in_topic', [
                'topic' => $this->topic_title,
                'author' => $this->creator->getDisplayname(),
            ]))
            ->addEmbed(function (DiscordEmbed $embed) use ($forum) {
                return $embed
                    ->setTitle($this->topic_title)
                    ->setDescription(Text::embedSafe($this->content))
                    ->setUrl(URL::getSelfURL() . ltrim(URL::build('/forum/topic/' . urlencode($this->topic_id) . '-' . $forum->titleToURL($this->topic_title)), '/'));
            });
    }
}
