<?php

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Google as GoogleProvider;
use Wohali\OAuth2\Client\Provider\Discord as DiscordProvider;

class OAuth extends Instanceable {

    public const DISCORD = 'discord';
    public const GOOGLE = 'google';

    public const PAGE_REGISTER = 'register';
    public const PAGE_LOGIN = 'login';

    private DiscordProvider $discordProvider;
    private GoogleProvider $googleProvider;

    private DB $_db;

    private function db(): DB {
        return $this->_db ??= DB::getInstance();
    }

    public function isAvailable(): bool {
        foreach ([self::DISCORD, self::GOOGLE] as $provider) {
            if ($this->isSetup($provider)) {
                return true;
            }
        }
        return false;
    }

    public function getProvidersAvailable(string $page): array {
        $providers = [];
        foreach ([self::DISCORD, self::GOOGLE] as $provider) {
            if ($this->isSetup($provider)) {
                $instance = $this->getProviderInstance($provider, $page);

                $providers[$provider] = $instance->getAuthorizationUrl([
                    'scope' => ['identify', 'email'],
                ]);
            }
        }
        return $providers;
    }

    public function getProviderInstance(string $provider, string $page): AbstractProvider {
        [$clientId, $clientSecret] = $this->getCredentials($provider);
        $url = rtrim(Util::getSelfURL(), '/') . URL::build("/$page/oauth", "provider=$provider");
        $options = [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $url,
        ];

        switch ($provider) {
            case self::DISCORD:
                return $this->discordProvider ??= new DiscordProvider($options);

            case self::GOOGLE:
                return $this->googleProvider ??= new GoogleProvider($options);

            default:
                throw new RuntimeException('Unknown provider');
        }
    }

    public function isEnabled(string $provider): bool {
        return $this->db()->get('oauth', ['provider', '=', $provider])->first()->enabled == '1';
    }

    public function setEnabled(string $provider, int $enabled): void {
        $this->db()->createQuery("UPDATE nl2_oauth SET enabled = ? WHERE provider = ?", [$enabled, $provider]);
    }

    public function isSetup(string $provider): bool {
        if (!$this->isEnabled($provider)) {
            return false;
        }

        [$client_id, $client_secret] = $this->getCredentials($provider);

        return $client_id !== '' && $client_secret !== '';
    }

    public function getCredentials(string $provider): array {
        $data = $this->db()->get('oauth', ['provider', '=', $provider])->first();
        return [
            $data->client_id,
            $data->client_secret,
        ];
    }

    public function setCredentials(string $provider, string $client_id, string $client_secret): void {
        $this->db()->createQuery(
            "UPDATE nl2_oauth SET client_id = ?, client_secret = ? WHERE provider = ?",
            [$client_id, $client_secret, $provider]
        );
    }

    public function userExistsByProviderId(string $provider, string $provider_id): bool {
        return $this->db()->selectQuery('SELECT user_id FROM nl2_oauth_users WHERE provider = ? AND provider_id = ?', [$provider, $provider_id])->count() > 0;
    }

    public function getUserIdFromProviderId(string $provider, string $provider_id): int {
        return $this->db()->selectQuery('SELECT user_id FROM nl2_oauth_users WHERE provider = ? AND provider_id = ?', [$provider, $provider_id])->first()->user_id;
    }

    public function saveUserProvider($user_id, $provider, $provider_id): void {
        $this->db()->createQuery("INSERT INTO nl2_oauth_users (user_id, provider, provider_id) VALUES (?, ?, ?)", [$user_id, $provider, $provider_id]);
    }
}
