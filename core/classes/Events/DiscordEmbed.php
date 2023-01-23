<?php

class DiscordEmbed {

    private ?string $title = null;
    private ?string $author = null;
    private ?string $color = null;
    private ?string $url = null;
    private ?string $description = null;
    private ?array $fields = null;
    private ?array $image = null;
    private ?array $thumbnail = null;
    private ?array $footer = null;

    public function title(string $title): self {
        $this->title = $title;
        return $this;
    }

    public function author(string $author): self {
        $this->author = $author;
        return $this;
    }

    public function color($color): self {
        if (is_string($color)) {
            $color = hexdec($color);
        }

        $this->color = $color;
        return $this;
    }

    public function url(string $url): self {
        $this->url = $url;
        return $this;
    }

    public function description(string $description): self {
        $this->description = $description;
        return $this;
    }

    public function field(string $name, string $value, bool $inline = false): self {
        if ($this->fields === null) {
            $this->fields = [];
        }

        $this->fields[] = [
            'name' => $name,
            'value' => $value,
            'inline' => $inline,
        ];
        return $this;
    }

    public function image(string $image_url): self {
        $this->image = ['url' => $image_url];
        return $this;
    }

    public function thumbnail(string $thumbnail_url): self {
        $this->thumbnail = ['url' => $thumbnail_url];
        return $this;
    }

    public function footer(string $footer_text, string $footer_icon_url = null): self {
        $this->footer = [
            'text' => $footer_text,
            'icon_url' => $footer_icon_url,
        ];
        return $this;
    }

    public function toArray(): array {
        return array_filter([
            'title' => $this->title,
            'author' => $this->author,
            'color' => $this->color,
            'url' => $this->url,
            'description' => $this->description,
            'fields' => $this->fields,
            'image' => $this->image,
            'thumbnail' => $this->thumbnail,
            'footer' => $this->footer,
        ]);
    }
}
