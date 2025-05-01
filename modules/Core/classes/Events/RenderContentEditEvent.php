<?php

class RenderContentEditEvent extends AbstractEvent {

    public string $content;
    public bool $skip_purify;

    /**
     * @param string $content     The content to render.
     * @param bool   $skip_purify Whenever to skip purification of the content, When false it will remove any dangerous html/scripts
     */
    public function __construct(string $content, bool $skip_purify = false) {
        $this->content = $content;
        $this->skip_purify = $skip_purify;
    }

    public static function internal(): bool {
        return true;
    }
}