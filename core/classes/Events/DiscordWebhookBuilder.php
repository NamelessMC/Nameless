<?php

class DiscordWebhookBuilder {

    private ?string $username = null;
    private ?string $avatar_url;
    private ?string $content;
    /** @var DiscordEmbed[] */
    private ?array $embeds = null;

    private function __construct() {
        // ...
    }

    public static function make(): self {
        return new self();
    }

    public function username(string $username): self {
        $this->username = $username;
        return $this;
    }

    public function avatarUrl(string $avatar_url): self {
        $this->avatar_url = $avatar_url;
        return $this;
    }

    public function content(string $content): self {
        $this->content = $content;
        return $this;
    }

    public function embed(Closure $closure): self {
        if ($this->embeds === null) {
            $this->embeds = [];
        }

        $this->embeds[] = $closure(new DiscordEmbed());
        return $this;
    }

    public function toArray(): array {
        return array_filter([
            'username' => $this->username,
            'avatar_url' => $this->avatar_url,
            'content' => $this->content,
            'embeds' => array_map(static fn (DiscordEmbed $embed) => $embed->toArray(), $this->embeds),
        ]);
    }
}
