<?php

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Google as GoogleProvider;
use Wohali\OAuth2\Client\Provider\Discord as DiscordProvider;

class OAuth extends Instanceable {

    public const DISCORD = 'discord';
    public const GOOGLE = 'google';

    private const PROVIDERS = [
        self::DISCORD,
        self::GOOGLE,
    ];

    public const PAGE_LINK = 'link';
    public const PAGE_LOGIN = 'login';

    private DiscordProvider $_discord_provider;
    private GoogleProvider $_google_provider;

    private DB $_db;

    private function db(): DB {
        return $this->_db ??= DB::getInstance();
    }

    /**
     * Determine if OAuth is available if at least one provider is setup.
     *
     * @return bool If any provider is setup
     */
    public function isAvailable(): bool {
        foreach (self::PROVIDERS as $provider) {
            if ($this->isSetup($provider)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get an array of provider names and their URL & icon.
     *
     * @param string $page Either "login" or "register" for generating the callback URL
     * @return array Array of provider names and their instances
     */
    public function getProvidersAvailable(string $page): array {
        $providers = [];
        foreach (self::PROVIDERS as $provider_name) {
            if (!$this->isSetup($provider_name)) {
                continue;
            }

            $provider = $this->getProviderInstance($provider_name, $page);

            $providers[$provider_name] = [
                'url' => $provider->getAuthorizationUrl([
                    'scope' => [
                        $provider_name === self::DISCORD ? 'identify' : 'openid',
                        'email' // we don't use this for anything yet
                    ],
                ]),
                'icon' => $this->getIcon($provider_name),
            ];
        }

        return $providers;
    }

    /**
     * Get or create an instance of a specific provider.
     *
     * @param string $provider The provider name
     * @param string $page Either "login" or "register" for generating the callback URL
     * @return AbstractProvider The provider instance
     */
    public function getProviderInstance(string $provider, string $page): AbstractProvider {
        [$clientId, $clientSecret] = $this->getCredentials($provider);
        // Login: http(s)://example.com/index.php?route=/login/oauth/&provider=<provider>
        // Link: http(s)://example.com/index.php?route=/user/oauth/&provider=<provider>
        $url = rtrim(Util::getSelfURL(), '/') . URL::build($page === self::PAGE_LINK ? '/user/oauth' : '/login/oauth', "provider=$provider", 'non-friendly');
        $options = [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $url,
        ];

        switch ($provider) {
            case self::DISCORD:
                return $this->_discord_provider ??= new DiscordProvider($options);

            case self::GOOGLE:
                return $this->_google_provider ??= new GoogleProvider($options);

            default:
                throw new RuntimeException("Unknown provider: $provider");
        }
    }

    /**
     * Determine if a provider is enabled (different from setup!).
     *
     * @param string $provider The provider name
     * @return bool If the provider is enabled
     */
    public function isEnabled(string $provider): bool {
        return $this->db()->get('oauth', ['provider', '=', $provider])->first()->enabled == '1';
    }

    /**
     * Set a provider as enabled or disabled (`1` or `0` respectively).
     *
     * @param string $provider The provider name
     * @param int $enabled Whether to enable or disable the provider
     */
    public function setEnabled(string $provider, int $enabled): void {
        $this->db()->createQuery("UPDATE nl2_oauth SET enabled = ? WHERE provider = ?", [$enabled, $provider]);
    }

    /**
     * Determine if a provider is setup.
     * A provider is considered setup if it has a client ID and a client secret set.
     *
     * @param string $provider The provider name
     * @return bool If the provider is setup
     */
    public function isSetup(string $provider): bool {
        if (!$this->isEnabled($provider)) {
            return false;
        }

        [$client_id, $client_secret] = $this->getCredentials($provider);

        return $client_id !== '' && $client_secret !== '';
    }

    /**
     * Get the array key for a specific providers client ID.
     * Discord uses `id` and Google uses `sub`, so we have to be able to differentiate.
     *
     * @param string $provider The provider name
     * @return string The array key for the provider's client ID
     */
    public function getIdName(string $provider): string {
        switch ($provider) {
            case self::DISCORD:
                return 'id';
            case self::GOOGLE:
                return 'sub';
            default:
                throw new RuntimeException("Unknown provider: $provider");
        }
    }

    /**
     * Get the FontAwesome icon for a specific provider.
     *
     * @param string $provider The provider name
     * @return string The FontAwesome icon for the provider
     */
    public function getIcon(string $provider): string {
        switch ($provider) {
            case self::DISCORD:
                return 'fab fa-discord';
            case self::GOOGLE:
                return 'fab fa-google';
            default:
                throw new RuntimeException("Unknown provider: $provider");
        }
    }

    /**
     * Get the client ID and client secret for a specific provider.
     *
     * @param string $provider The provider name
     * @return array The configured credentials for this provider
     */
    public function getCredentials(string $provider): array {
        $data = $this->db()->get('oauth', ['provider', '=', $provider])->first();
        return [
            $data->client_id,
            $data->client_secret,
        ];
    }

    /**
     * Update the client ID and client secret for a specific provider.
     *
     * @param string $provider The provider name
     * @param string $client_id The new client ID
     * @param string $client_secret The new client secret
     */
    public function setCredentials(string $provider, string $client_id, string $client_secret): void {
        $this->db()->createQuery(
            'INSERT INTO nl2_oauth (provider, client_id, client_secret) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE client_id=?, client_secret=?',
            [$provider, $client_id, $client_secret, $client_id, $client_secret]
        );
    }

    /**
     * Check if a NamelessMC user has already connected their account to a specific provider.
     *
     * @param string $provider The provider name
     * @param string $provider_id The provider user ID
     * @return bool Whether the user is already linked to the provider
     */
    public function userExistsByProviderId(string $provider, string $provider_id): bool {
        return $this->db()->selectQuery(
            'SELECT user_id FROM nl2_oauth_users WHERE provider = ? AND provider_id = ?',
            [$provider, $provider_id]
        )->count() > 0;
    }

    /**
     * Get the NamelessMC user ID for a specific provider user ID.
     *
     * @param string $provider The provider name
     * @param string $provider_id The provider user ID for lookup
     * @return int The NamelessMC user ID of the user linked to the provider
     */
    public function getUserIdFromProviderId(string $provider, string $provider_id): int {
        return $this->db()->selectQuery(
            'SELECT user_id FROM nl2_oauth_users WHERE provider = ? AND provider_id = ?',
            [$provider, $provider_id]
        )->first()->user_id;
    }

    /**
     * Save a new user linked to a specific provider.
     *
     * @param string $user_id The NamelessMC user ID
     * @param string $provider The provider name
     * @param string $provider_id  The provider user ID
     */
    public function saveUserProvider(string $user_id, string $provider, string $provider_id): void {
        $this->db()->createQuery(
            'INSERT INTO nl2_oauth_users (user_id, provider, provider_id) VALUES (?, ?, ?)',
            [$user_id, $provider, $provider_id]
        );
    }

    /**
     * Get an array of provider names and provider user IDs for a specific user
     *
     * @param int $user_id The NamelessMC user ID
     * @return array The array
     */
    public function getAllProvidersForUser(int $user_id): array {
        return $this->db()->selectQuery(
            'SELECT * FROM nl2_oauth_users WHERE user_id = ?',
            [$user_id]
        )->results();
    }

    /**
     * Delete a user's provider data.
     *
     * @param int $user_id The provider name
     * @param string $provider The provider user ID
     */
    public function unlinkProviderForUser(int $user_id, string $provider): void {
        $this->db()->createQuery(
            'DELETE FROM nl2_oauth_users WHERE user_id = ? AND provider = ?',
            [$user_id, $provider]
        );
    }
}
