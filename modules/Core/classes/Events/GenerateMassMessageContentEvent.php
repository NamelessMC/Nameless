<?php

class GenerateMassMessageContentEvent extends AbstractEvent {

    public string $content;
    public bool $skip_purify;
    public string $title;

    public function __construct(string $content, string $title, bool $skip_purify = false) {
        $this->content = $content;
        $this->skip_purify = $skip_purify;
        $this->title = $title;
    }

    public static function internal(): bool {
        return true;
    }
}
