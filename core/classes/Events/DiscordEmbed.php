<?php

class DiscordEmbed {

    private ?string $_title = null;
    private ?array $_author = null;
    /** @var float|int|null $_color  */
    private $_color = null;
    private ?string $_url = null;
    private ?string $_description = null;
    private ?array $_fields = null;
    private ?array $_image = null;
    private ?array $_thumbnail = null;
    private ?array $_footer = null;

    public function title(string $title): self {
        $this->_title = $title;
        return $this;
    }

    public function author(string $name, ?string $icon_url = null, ?string $url = null): self {
        $this->_author = array_filter([
            'name' => $name,
            'icon_url' => $icon_url,
            'url' => $url,
        ]);

        return $this;
    }

    public function color($color): self {
        if (is_string($color)) {
            $color = hexdec($color);
        }

        $this->_color = $color;
        return $this;
    }

    public function url(string $url): self {
        $this->_url = $url;
        return $this;
    }

    public function description(string $description): self {
        $this->_description = $description;
        return $this;
    }

    public function field(string $name, string $value, bool $inline = false): self {
        if ($this->_fields === null) {
            $this->_fields = [];
        }

        $this->_fields[] = [
            'name' => $name,
            'value' => $value,
            'inline' => $inline,
        ];
        return $this;
    }

    public function image(string $image_url): self {
        $this->_image = ['url' => $image_url];
        return $this;
    }

    public function thumbnail(string $thumbnail_url): self {
        $this->_thumbnail = ['url' => $thumbnail_url];
        return $this;
    }

    public function footer(string $footer_text, string $footer_icon_url = null): self {
        $this->_footer = [
            'text' => $footer_text,
            'icon_url' => $footer_icon_url,
        ];
        return $this;
    }

    public function toArray(): array {
        return array_filter([
            'title' => $this->_title,
            'author' => $this->_author,
            'color' => $this->_color,
            'url' => $this->_url,
            'description' => $this->_description,
            'fields' => $this->_fields,
            'image' => $this->_image,
            'thumbnail' => $this->_thumbnail,
            'footer' => $this->_footer,
        ]);
    }
}
