<?php

class ForumTopicReplyEmailTemplate extends EmailTemplate
{
    private User $author;
    private string $topicTitle;

    public function __construct(User $author, string $topicTitle, string $replyContent, string $link)
    {
        // TODO this should live in forum module lanaguage file
        $this->addPlaceholder('[Message]', new LanguageKey('emails', 'forum_topic_reply_message', [
            'author' => $author->data()->username,
            'content' => $replyContent,
        ]));

        $this->addPlaceholder('[Link]', $link);

        $this->author = $author;
        $this->topicTitle = $topicTitle;

        parent::__construct();
    }

    public function subject(): LanguageKey
    {
        return new LanguageKey('forum', 'new_reply_in_topic', [
            'author' => $this->author->data()->username, 'topic' => $this->topicTitle,
        ], ROOT_PATH . '/modules/Forum/language/');
    }
}
