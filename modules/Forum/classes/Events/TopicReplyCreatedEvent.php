<?php

class TopicReplyCreatedEvent extends AbstractEvent implements DiscordDispatchable {

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

    public static function description(): array {
        return ['forum', 'topic_reply'];
    }

    public function toDiscordWebook(): DiscordWebhookBuilder {
        $language = new Language(ROOT_PATH . '/modules/Forum/language', DEFAULT_LANGUAGE);
        $forum = new Forum();

        return DiscordWebhookBuilder::make()
            ->username($this->creator->getDisplayname() . ' | ' . SITE_NAME)
            ->avatarUrl($this->creator->getAvatar(128, true))
            ->content($language->get('forum', 'new_reply_in_topic', [
                'topic' => $this->topic_title,
                'author' => $this->creator->getDisplayname(),
            ]))
            ->embed(function (DiscordEmbed $embed) use ($forum) {
                return $embed
                    ->title($this->topic_title)
                    ->description($this->content)
                    ->url(URL::getSelfURL() . ltrim(URL::build('/forum/topic/' . urlencode($this->topic_id) . '-' . $forum->titleToURL($this->topic_title)), '/'));
            });
    }
}
