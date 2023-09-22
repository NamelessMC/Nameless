<?php
/**
 * Manages avatar sources and provides methods for fetching avatars.
 *
 * @package NamelessMC\Avatars
 * @author Aberdeener
 * @version 2.0.0-pr10
 * @license MIT
 */
class AvatarSource extends Instanceable {

    /** @var AvatarSourceBase[] */
    protected array $_sources = [];
    private Cache $_cache;

    protected function __construct() {
        $this->_cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);
    }

    /**
     * Get an avatar URL for a user.
     *
     * @param int|User $user User to fetch avatar for, or their ID.
     * @param int $size Size of avatar to fetch in pixels.
     * @param bool $full_url Whether to return the full external URL (ie: for display in Discord embed) or just the path.
     * @return string The URL to the avatar.
     */
    public function getAvatarForUser($user, int $size = 128, bool $full_url = false): string {
        if ($user instanceof User) {
            $user_id = $user->data()->id;
        } else {
            $user_id = (int) $user;
        }

        $this->_cache->setCache('avatars');

        foreach ($this->getAllSources() as $source) {
            if (!$source->isEnabled() && $source->canBeDisabled()) {
                continue;
            }

            $cache_key = $user_id . '_' . $source->getSafeName() . '_' . $size . '_' . (int) $full_url;
            if ($this->_cache->isCached($cache_key)) {
                $avatar = $this->_cache->retrieve($cache_key);
                if ($avatar) {
                    return $avatar;
                }
            }

            if (!($user instanceof User)) {
                $user = new User($user_id);
            }

            $avatar = $source->getAvatar($user, $size, $full_url);
            if ($avatar) {
                $url = $avatar;
                // Cache for an hour incase a module does not reset the users avatar cache
                $this->_cache->store($cache_key, $url, 3600);
                break;
            }
        }

        // Fallback to initials avatar
        if (!isset($url)) {
            $url = $this->_sources[InitialsAvatarSource::class]->getAvatar($user, $size, $full_url);
        }

        return $url;
    }

    /**
     * @param int|User $user
     * @param string|null $source_class
     * @return void
     */
    public function clearUserAvatarCache($user, string $source_class = null): void {
        if ($user instanceof User) {
            $user_id = $user->data()->id;
        } else {
            $user_id = (int) $user;
        }

        $this->_cache->setCache('avatars');

        foreach (array_keys($this->_cache->retrieveAll()) as $cache_key) {
            if (str_starts_with($cache_key, $user_id . '_' . ($source_class ?? ''))) {
                $this->_cache->erase($cache_key);
            }
        }
    }

    public function clearSourceAvatarCache(string $source_class): void {
        $this->_cache->setCache('avatars');

        foreach (array_keys($this->_cache->retrieveAll()) as $cache_key) {
            if (str_contains($cache_key, $source_class)) {
                $this->_cache->erase($cache_key);
            }
        }
    }

    /**
     * Get all registered sources.
     *
     * @return AvatarSourceBase[]
     */
    public function getAllSources(): array {
        $sources = $this->_sources;
        uasort($sources, static function (AvatarSourceBase $a, AvatarSourceBase $b) {
            return $a->getOrder() - $b->getOrder();
        });
        return $sources;
    }

    public function getSourceBySafeName(string $safe_name): ?AvatarSourceBase {
        return $this->_sources[$safe_name] ?? null;
    }

    /**
     * Register avatar source.
     *
     * @param AvatarSourceBase $source Instance of avatar source to register.
     */
    public function registerSource(AvatarSourceBase $source): void {
        $this->_sources[$source->getSafeName()] = $source;
    }
}
