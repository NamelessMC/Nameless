<?php

class DiscordWebhookBuilder {

    private ?string $_username = null;
    private ?string $_avatar_url = null;
    private ?string $_content = null;
    /** @var DiscordEmbed[] */
    private ?array $_embeds = null;

    private function __construct() {
        // ...
    }

    public static function make(): self {
        return new self();
    }

    public function username(string $username): self {
        $this->_username = $username;
        return $this;
    }

    public function avatarUrl(string $avatar_url): self {
        $this->_avatar_url = $avatar_url;
        return $this;
    }

    public function content(string $content): self {
        $this->_content = $content;
        return $this;
    }

    /**
     * @param Closure(DiscordEmbed): DiscordEmbed $closure
     * @return $this
     */
    public function embed(Closure $closure): self {
        if ($this->_embeds === null) {
            $this->_embeds = [];
        }

        $this->_embeds[] = $closure(new DiscordEmbed());
        return $this;
    }

    public function toArray(): array {
        return array_filter([
            'username' => $this->_username,
            'avatar_url' => $this->_avatar_url,
            'content' => $this->_content,
            'embeds' => array_map(static fn (DiscordEmbed $embed) => $embed->toArray(), $this->_embeds),
        ]);
    }
}
