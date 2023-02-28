<?php

class TopicCreatedEvent extends AbstractEvent implements DiscordDispatchable {

    public User $creator;
    public string $forum_title;
    public string $topic_title;
    public string $content;
    public int $topic_id;
    public ?array $available_hooks;

    public function __construct(
        User $creator,
        string $forum_title,
        string $topic_title,
        string $content,
        int $topic_id,
        ?array $available_hooks
    ) {
        $this->creator = $creator;
        $this->forum_title = $forum_title;
        $this->topic_title = $topic_title;
        $this->content = $content;
        $this->topic_id = $topic_id;
        $this->available_hooks = $available_hooks;
    }

    public static function name(): string {
        return 'newTopic';
    }

    public static function description(): string {
        return (new Language())->get('admin', 'announcement_hook_info');
    }

    public function toDiscordWebook(): DiscordWebhookBuilder {
        $language = new Language(ROOT_PATH . '/modules/Forum/language', DEFAULT_LANGUAGE);
        $forum = new Forum();

        return DiscordWebhookBuilder::make()
            ->setUsername($this->creator->getDisplayname() . ' | ' . SITE_NAME)
            ->setAvatarUrl($this->creator->getAvatar(128, true))
            ->setContent($language->get('forum', 'new_topic_text', [
                'forum' => $this->forum_title,
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
