<?php

// TODO: discord avatar source, will need bot token but then we can use a UserLoggedInEvent to update the avatar every x minutes
abstract class AvatarSourceBase {

    protected string $_name;
    protected string $_module;
    protected ?string $_settings = null;
    protected bool $_can_be_disabled = true;

    protected int $_size;
    protected bool $_full_url;

    public function getAvatar(User $user, int $size = 128, bool $full_url = false): ?string {
        $this->_size = $size;
        $this->_full_url = $full_url;

        return $this->get($user);
    }

    public function getName(): string {
        return $this->_name;
    }

    public function getModule(): string {
        return $this->_module;
    }

    public function getSettings(): ?string {
        return $this->_settings;
    }

    public function getSettingsUrl(): ?string {
        if ($this->_settings === null) {
            return null;
        }

        return URL::build('/panel/core/avatars/', 'action=settings&source=' . $this->getSafeName());
    }

    public function isEnabled(): bool {
        return $this->getDatabaseSettings()['enabled'];
    }

    public function canBeDisabled(): bool {
        return $this->_can_be_disabled;
    }

    public function getOrder(): int {
        return $this->getDatabaseSettings()['order'];
    }

    public function getSafeName(): string {
        return static::class;
    }

    private function getDatabaseSettings(): array {
        $settings = json_decode(Util::getSetting('avatar_source_settings'), true);
        if (isset($settings[$this->getSafeName()])) {
            return $settings[$this->getSafeName()];
        }

        $settings[$this->getSafeName()] = [
            'enabled' => true,
            'order' => 10,
        ];

        Util::setSetting('avatar_source_settings', json_encode($settings));

        return $settings[$this->getSafeName()];
    }

    abstract protected function get(User $user): ?string;

}
