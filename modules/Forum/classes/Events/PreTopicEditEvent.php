<?php

class PreTopicEditEvent extends AbstractEvent {

    public string $content;
    public User $user;
    public int $topic_id;
    public int $post_id;

    public function __construct(string $content, User $user, int $topic_id, int $post_id) {
        $this->content = $content;
        $this->user = $user;
        $this->topic_id = $topic_id;
        $this->post_id = $post_id;
    }

    public static function internal(): bool {
        return true;
    }
}