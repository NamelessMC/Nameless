<?php
declare(strict_types=1);

use GuzzleHttp\Exception\GuzzleException;

/**
 * DiscordIntegration class
 *
 * @package Modules\Core\Integrations
 * @author Partydragen
 * @version 2.1.0
 * @license MIT
 */
class DiscordIntegration extends IntegrationBase {

    protected Language $_language;

    /**
     * @param Language $language
     */
    public function __construct(Language $language) {
        $this->_name = 'Discord';
        $this->_icon = 'fab fa-discord';
        $this->_language = $language;
        $this->_settings = ROOT_PATH . '/modules/Discord Integration/includes/admin_integrations/discord.php';

        parent::__construct();
    }

    /**
     * Called when user wants to link their account from user connections page, Does not need to be verified
     *
     * @throws GuzzleException
     * @throws Exception
     */
    public function onLinkRequest(User $user): void {
        $link_method = Util::getSetting('integration_link_method', 'bot', 'Discord Integration');
        if ($link_method === 'oauth') {
            // Link with oauth
            Session::put('oauth_method', 'link_integration');

            $providers = NamelessOAuth::getInstance()->getProvidersAvailable();
            $provider = $providers['discord'];

            Redirect::to($provider['url']);
        } else {
            // Discord bot linking
            $token = uniqid('', true);

            $integrationUser = new IntegrationUser($this);
            $integrationUser->linkIntegration($user, null, null, false, $token);

            Session::flash('connections_success', Discord::getLanguageTerm('discord_id_confirm', ['token' => $token]));
        }
    }

    /**
     * Called when user wants to continue to verify their integration user from connections page
     * @throws Exception
     */
    public function onVerifyRequest(User $user): void {
        $token = uniqid('', true);

        $integrationUser = new IntegrationUser($this, (string)$user->data()->id, 'user_id');
        $integrationUser->update([
            'code' => $token
        ]);

        Session::flash('connections_success', Discord::getLanguageTerm('discord_id_confirm', ['token' => $token]));
    }

    /**
     * Called when user wants to unlink their integration user from connections page
     *
     * @throws GuzzleException
     */
    public function onUnlinkRequest(User $user): void {
        $integrationUser = new IntegrationUser($this, (string)$user->data()->id, 'user_id');
        $integrationUser->unlinkIntegration();

        Session::flash('connections_success', $this->_language->get('user', 'integration_unlinked', ['integration' => Output::getClean($this->_name)]));
    }

    /**
     * Called when the user have successfully validated the ownership of the account
     *
     * @throws GuzzleException
     * @throws Exception
     */
    public function onSuccessfulVerification(IntegrationUser $integrationUser): void {
        // attempt to update their Discord roles
        $user = $integrationUser->getUser();

        $roles = array_unique(array_map(static function ($group_id) {
            return Discord::getDiscordRoleId(DB::getInstance(), $group_id);
        }, $user->getAllGroupIds()));

        Discord::updateDiscordRoles($user, $roles, []);
    }

    /**
     * Should we allow linking with this integration?
     *
     * @return bool Whether to allow linking with this integration
     */
    public function allowLinking(): bool {
        $link_method = Util::getSetting('integration_link_method', 'bot', 'Discord Integration');
        if ($link_method === 'oauth') {
            return NamelessOAuth::getInstance()->isEnabled('discord');
        }

        return Discord::isBotSetup();
    }

    /**
     * Called when register page being loaded
     */
    public function onRegistrationPageLoad(Fields $fields): void {
        // Nothing to do here
    }

    /**
     * Called before registration validation
     */
    public function beforeRegistrationValidation(Validate $validate): void {
        // Nothing to do here
    }

    /**
     * Called after registration validation
     */
    public function afterRegistrationValidation(): void {
        // Nothing to do here
    }

    /**
     * Called when user is successfully registered
     *
     * @throws GuzzleException
     * @throws Exception
     */
    public function successfulRegistration(User $user): void {
        // Link integration if user registered using discord oauth
        if (Session::exists('oauth_register_data')) {
            $data = json_decode(Session::get('oauth_register_data'), true);
            if (isset($data['data']['username'], $data['data']['discriminator']) && $data['provider'] === 'discord') {

                $username = $data['data']['username'] . '#' . $data['data']['discriminator'];
                $discord_id = $data['data']['id'];
                if ($this->validateIdentifier($discord_id) && $this->validateUsername($username)) {
                    $integrationUser = new IntegrationUser($this);
                    $integrationUser->linkIntegration($user, $discord_id, $username, true);
                    $integrationUser->verifyIntegration();
                }
            }
        }
    }

    /**
     * Validate identifier when it being linked or updated.
     *
     * @param string $identifier The identifier value to validate.
     * @param string $integration_user_id The integration user id to ignore during duplicate check.
     *
     * @return bool Whether this validation passed or not.
     * @throws Exception
     */
    public function validateIdentifier(string $identifier, string $integration_user_id = '0'): bool {
        $validation = Validate::check(['identifier' => $identifier], [
            'identifier' => [
                Validate::REQUIRED => true,
                Validate::NUMERIC => true,
                Validate::MIN => 17,
                Validate::MAX => 20
            ]
        ])->messages([
            'identifier' => [
                Validate::REQUIRED => $this->_language->get('admin', 'integration_identifier_required', ['integration' => $this->getName()]),
                Validate::NUMERIC => $this->_language->get('admin', 'integration_identifier_invalid', ['integration' => $this->getName()]),
                Validate::MIN => $this->_language->get('admin', 'integration_identifier_invalid', ['integration' => $this->getName()]),
                Validate::MAX => $this->_language->get('admin', 'integration_identifier_invalid', ['integration' => $this->getName()]),
            ]
        ]);

        if (count($validation->errors())) {
            // Validation errors
            foreach ($validation->errors() as $error) {
                $this->addError($error);
            }
        } else {
            // Ensure identifier doesn't already exist
            $exists = DB::getInstance()->query("SELECT * FROM nl2_users_integrations WHERE integration_id = ? AND identifier = ? AND id <> ?", [$this->data()->id, $identifier, $integration_user_id]);
            if ($exists->count()) {
                $this->addError($this->_language->get('user', 'integration_identifier_already_linked', ['integration' => $this->getName()]));
                return false;
            }
        }

        return $validation->passed();
    }

    /**
     * Validate username when it being linked or updated.
     *
     * @param string $username The username value to validate.
     * @param string $integration_user_id The integration user id to ignore during duplicate check.
     *
     * @return bool Whether this validation passed or not.
     * @throws Exception
     */
    public function validateUsername(string $username, string $integration_user_id = '0'): bool {
        $validation = Validate::check(['username' => $username], [
            'username' => [
                Validate::REQUIRED => true,
                Validate::REGEX => '/^.{2,32}#[0-9]{4}$/'
            ]
        ])->messages([
            'username' => [
                Validate::REQUIRED => $this->_language->get('admin', 'integration_username_required', ['integration' => $this->getName()]),
                Validate::REGEX => $this->_language->get('admin', 'integration_username_invalid', ['integration' => $this->getName()])
            ]
        ]);

        if (count($validation->errors())) {
            // Validation errors
            foreach ($validation->errors() as $error) {
                $this->addError($error);
            }
        } else {
            // Ensure identifier doesn't already exist
            $exists = DB::getInstance()->query("SELECT * FROM nl2_users_integrations WHERE integration_id = ? AND username = ? AND id <> ?", [$this->data()->id, $username, $integration_user_id]);
            if ($exists->count()) {
                $this->addError($this->_language->get('user', 'integration_username_already_linked', ['integration' => $this->getName()]));
                return false;
            }
        }

        return $validation->passed();
    }

    /**
     * Called when user integration is requested to be synced.
     */
    public function syncIntegrationUser(IntegrationUser $integration_user): bool {
        $this->addError($this->_language->get('admin', 'integration_sync_not_supported'));
        return false;
    }
}
