<?php
/**
 * Builds a Discord webhook to represent an event as a Discord embed.
 *
 * @package NamelessMC\Events
 * @author Aberdeener
 * @version 2.2.0
 * @license MIT
 */
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

    public function getUsername(): ?string {
        return $this->_username;
    }

    public function setUsername(?string $username): self {
        $this->_username = $username;
        return $this;
    }

    public function getAvatarUrl(): ?string {
        return $this->_avatar_url;
    }

    public function setAvatarUrl(?string $avatar_url): self {
        $this->_avatar_url = $avatar_url;
        return $this;
    }

    public function getContent(): ?string {
        return $this->_content;
    }

    public function setContent(string $content): self {
        $this->_content = $content;
        return $this;
    }

    /**
     * @return DiscordEmbed[]|null
     */
    public function getEmbeds(): ?array {
        return $this->_embeds;
    }

    /**
     * @param Closure(DiscordEmbed): DiscordEmbed $closure
     * @return $this
     */
    public function addEmbed(Closure $closure): self {
        if ($this->_embeds === null) {
            $this->_embeds = [];
        }

        $embed = $closure(new DiscordEmbed());
        if (!($embed instanceof DiscordEmbed)) {
            throw new RuntimeException('Embed closure must return a DiscordEmbed instance');
        }

        $this->_embeds[] = $embed;
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
