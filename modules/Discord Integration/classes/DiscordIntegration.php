<?php
/**
 * DiscordIntegration class
 *
 * @package Modules\Core\Integrations
 * @author Partydragen
 * @version 2.1.2
 * @license MIT
 */
class DiscordIntegration extends IntegrationBase {

    protected Language $_language;

    public function __construct(Language $language) {
        $this->_name = 'Discord';
        $this->_icon = 'fab fa-discord';
        $this->_language = $language;
        $this->_settings = ROOT_PATH . '/modules/Discord Integration/includes/admin_integrations/discord.php';

        parent::__construct();
    }

    public function onLinkRequest(User $user) {
        $link_method = Util::getSetting('integration_link_method', 'bot', 'Discord Integration');
        if ($link_method == 'oauth') {
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

    public function onVerifyRequest(User $user) {
        $token = uniqid('', true);

        $integrationUser = new IntegrationUser($this, $user->data()->id, 'user_id');
        $integrationUser->update([
            'code' => $token
        ]);

        Session::flash('connections_success', Discord::getLanguageTerm('discord_id_confirm', ['token' => $token]));
    }

    public function onUnlinkRequest(User $user) {
        $integrationUser = new IntegrationUser($this, $user->data()->id, 'user_id');
        $integrationUser->unlinkIntegration();

        // Remove any linked Discord roles
        $roles = array_unique(array_map(static function ($group_id) {
            return Discord::getDiscordRoleId(DB::getInstance(), $group_id);
        }, $user->getAllGroupIds()));

        Discord::updateDiscordRoles($user, [], $roles);

        Session::flash('connections_success', $this->_language->get('user', 'integration_unlinked', ['integration' => Output::getClean($this->_name)]));
    }

    public function onSuccessfulVerification(IntegrationUser $integrationUser) {
        // attempt to update their Discord roles
        $user = $integrationUser->getUser();

        $roles = array_unique(array_map(static function ($group_id) {
            return Discord::getDiscordRoleId(DB::getInstance(), $group_id);
        }, $user->getAllGroupIds()));

        Discord::updateDiscordRoles($user, $roles, []);
        Session::flash('connections_success', $this->_language->get('user', 'integration_linked', ['integration' => Output::getClean($this->_name)]));
    }

    public function validateUsername(string $username, int $integration_user_id = 0): bool {
        $validation = Validate::check(['username' => $username], [
            'username' => [
                Validate::REQUIRED => true,
                Validate::MIN => 2,
                Validate::MAX => 32
            ]
        ])->messages([
            'username' => [
                Validate::REQUIRED => $this->_language->get('admin', 'integration_username_required', ['integration' => $this->getName()]),
                Validate::MIN => $this->_language->get('admin', 'integration_username_invalid', ['integration' => $this->getName()]),
                Validate::MAX => $this->_language->get('admin', 'integration_username_invalid', ['integration' => $this->getName()])
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
                $this->addError($this->_language->get('user', 'integration_username_already_linked', ['integration' =>  $this->getName()]));
                return false;
            }
        }

        return $validation->passed();
    }

    public function validateIdentifier(string $identifier, int $integration_user_id = 0): bool {
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

    public function allowLinking(): bool {
        $link_method = Util::getSetting('integration_link_method', 'bot', 'Discord Integration');
        if ($link_method == 'oauth') {
            return NamelessOAuth::getInstance()->isEnabled('discord');
        } else {
            return Discord::isBotSetup();
        }
    }

    public function onRegistrationPageLoad(Fields $fields) {
        // Nothing to do here
    }

    public function beforeRegistrationValidation(Validate $validate) {
        // Nothing to do here
    }

    public function afterRegistrationValidation() {
        // Nothing to do here
    }

    public function successfulRegistration(User $user) {
        // Link integration if user registered using discord oauth
        if (Session::exists('oauth_register_data')) {
            $data = json_decode(Session::get('oauth_register_data'), true);
            if ($data['provider'] == 'discord' && isset($data['data']['username'])) {

                $username = $data['data']['username'];
                $discord_id = $data['data']['id'];
                if ($this->validateIdentifier($discord_id) && $this->validateUsername($username)) {
                    $integrationUser = new IntegrationUser($this);
                    $integrationUser->linkIntegration($user, $discord_id, $username, true);
                    $integrationUser->verifyIntegration();
                }
            }
        }
    }

    public function syncIntegrationUser(IntegrationUser $integration_user): bool {
        $this->addError($this->_language->get('admin', 'integration_sync_not_supported'));

        return false;
    }
}
