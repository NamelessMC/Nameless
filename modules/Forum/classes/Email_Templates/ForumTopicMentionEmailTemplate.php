<?php

class ForumTopicMentionEmailTemplate extends EmailTemplate
{
    private User $author;

    public function __construct(User $author, string $content, string $link)
    {
        // TODO this should live in forum module lanaguage file
        $this->addPlaceholder('[Message]', new LanguageKey('emails', 'forum_topic_mention_message', [
            'author' => $author->data()->username,
            'content' => $content,
        ]));

        $this->addPlaceholder('[Link]', $link);

        $this->author = $author;

        parent::__construct();
    }

    public function subject(): LanguageKey
    {
        return new LanguageKey('user', 'user_tag_info', [
            'author' => $this->author->data()->username
        ]);
    }
}
