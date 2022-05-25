<?php

use League\OAuth2\Client\Provider\AbstractProvider;

/**
 * OAuth utility class.
 *
 * @package NamelessMC\Misc
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class OAuth extends Instanceable {

    private array $_providers = [];
    private array $_provider_instances = [];

    private DB $_db;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    /**
     * Add an OAuth provider to the system.
     *
     * @param string $name The name of the provider (Discord, Google, etc).
     * @param array $data Metadata about the provider: class, user_id_name, scope_id_name, icon
     */
    public function registerProvider(string $name, array $data): void {
        $this->_providers[$name] = $data;
    }

    /**
     * Get an array of all registered provider names and their data.
     *
     * @return array An array of all registered OAuth providers.
     */
    public function getProviders(): array {
        return $this->_providers;
    }

    /**
     * Determine if OAuth is available if at least one provider is setup.
     *
     * @return bool If any provider is setup
     */
    public function isAvailable(): bool {
        foreach (array_keys($this->_providers) as $provider) {
            if ($this->isSetup($provider)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get an array of provider names and their URL & icon.
     *
     * @return array Array of provider names and their instances
     */
    public function getProvidersAvailable(): array {
        $providers = [];
        foreach ($this->_providers as $provider_name => $provider_data) {
            if (!$this->isSetup($provider_name)) {
                continue;
            }

            $provider = $this->getProviderInstance($provider_name);

            $providers[$provider_name] = [
                'url' => $provider->getAuthorizationUrl([
                    'scope' => [
                        $provider_data['scope_id_name'],
                        'email',
                    ],
                ]),
                'icon' => $provider_data['icon'],
            ];
        }

        return $providers;
    }

    /**
     * Get or create an instance of a specific provider.
     *
     * @param string $provider The provider name
     * @return AbstractProvider The provider instance
     */
    public function getProviderInstance(string $provider): AbstractProvider {
        [$clientId, $clientSecret] = $this->getCredentials($provider);
        $url = rtrim(Util::getSelfURL(), '/') . URL::build('/oauth', "provider=" . urlencode($provider), 'non-friendly');
        $options = [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $url,
        ];

        if (array_key_exists($provider, $this->_providers)) {
            return $this->_provider_instances[$provider] ??= new $this->_providers[$provider]['class']($options);
        }

        throw new RuntimeException("Unknown provider: $provider");
    }

    /**
     * Determine if a provider is enabled (different from setup!).
     *
     * @param string $provider The provider name
     * @return bool If the provider is enabled
     */
    public function isEnabled(string $provider): bool {
        return $this->_db->get('oauth', ['provider', $provider])->first()->enabled == '1';
    }

    /**
     * Set a provider as enabled or disabled (`1` or `0` respectively).
     *
     * @param string $provider The provider name
     * @param int $enabled Whether to enable or disable the provider
     */
    public function setEnabled(string $provider, int $enabled): void {
        $this->_db->query("UPDATE nl2_oauth SET enabled = ? WHERE provider = ?", [$enabled, $provider]);
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
    public function getUserIdName(string $provider): string {
        if (array_key_exists($provider, $this->_providers)) {
            return $this->_providers[$provider]['user_id_name'];
        }

        throw new RuntimeException("Unknown provider: $provider");
    }

    /**
     * Get the client ID and client secret for a specific provider.
     *
     * @param string $provider The provider name
     * @return array The configured credentials for this provider
     */
    public function getCredentials(string $provider): array {
        $data = $this->_db->get('oauth', ['provider', $provider])->first();
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
        $this->_db->query(
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
        return $this->_db->query(
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
        return $this->_db->query(
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
        $this->_db->query(
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
        return $this->_db->query(
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
        $this->_db->query(
            'DELETE FROM nl2_oauth_users WHERE user_id = ? AND provider = ?',
            [$user_id, $provider]
        );
    }
}
