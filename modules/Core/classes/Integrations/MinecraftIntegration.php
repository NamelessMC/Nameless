<?php
/**
 * MinecraftIntegration class
 *
 * @package Modules\Core\Integrations
 * @author Partydragen
 * @version 2.1.0
 * @license MIT
 */
class MinecraftIntegration extends IntegrationBase {

    protected Language $_language;
    private string $_uuid;

    public function __construct(Language $language) {
        $this->_name = 'Minecraft';
        $this->_icon = 'fas fa-cubes';
        $this->_language = $language;
        $this->_settings = ROOT_PATH . '/modules/Core/includes/admin_integrations/minecraft.php';

        parent::__construct();
    }

    private function flashVerifyCommand(string $verification_code): void {
        $verification_command = Output::getClean(Util::getSetting('minecraft_verify_command', '/verify'));
        $message = $this->_language->get('user', 'validate_account_command', ['command' => $verification_command . ' ' . $verification_code]);
        Session::flash('connections_success', $message);
    }

    public function onLinkRequest(User $user) {
        $username = $user->data()->username;

        // Validate username
        if (!$this->validateUsername($username)) {
            return;
        }

        $result = $this->getUuidByUsername($username);
        if (count($this->getErrors())) {
            return;
        }
        $this->_uuid = $result['uuid'];

        // Validate identifier
        if (!$this->validateIdentifier($this->_uuid)) {
            return;
        }

        $code = SecureRandom::alphanumeric();

        $integrationUser = new IntegrationUser($this);
        $integrationUser->linkIntegration($user, $this->_uuid, $username, false, $code);

        $this->flashVerifyCommand($code);
    }

    public function onVerifyRequest(User $user) {
        $integrationUser = new IntegrationUser($this, $user->data()->id, 'user_id');

        $code = $integrationUser->data()->code;
        if ($code === null) {
            $code = SecureRandom::alphanumeric();

            $integrationUser->update([
                'code' => $code
            ]);
        }

        $this->flashVerifyCommand($code);
    }

    public function onUnlinkRequest(User $user) {
        $integrationUser = new IntegrationUser($this, $user->data()->id, 'user_id');
        $integrationUser->unlinkIntegration();

        Session::flash('connections_success', $this->_language->get('user', 'integration_unlinked', ['integration' => Output::getClean($this->_name)]));
    }

    public function onSuccessfulVerification(IntegrationUser $integrationUser) {
        // Nothing to do here
    }

    public function validateUsername(string $username, int $integration_user_id = 0): bool {
        $validation = Validate::check(['username' => $username], [
            'username' => [
                Validate::REQUIRED => true,
                Validate::MIN => 3,
                Validate::MAX => 20
            ]
        ])->messages([
            'username' => [
                Validate::REQUIRED => $this->_language->get('admin', 'integration_username_required', ['integration' => $this->getName()]),
                Validate::MIN => $this->_language->get('user', 'mcname_minimum_3'),
                Validate::MAX => $this->_language->get('user', 'mcname_maximum_20')
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

    public function validateIdentifier(string $identifier, int $integration_user_id = 0): bool {
        $validation = Validate::check(['identifier' => $identifier], [
            'identifier' => [
                Validate::REQUIRED => true,
                Validate::MIN => 32,
                Validate::MAX => 32
            ]
        ])->messages([
            'identifier' => [
                Validate::REQUIRED => $this->_language->get('admin', 'integration_identifier_required', ['integration' => $this->getName()]),
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

    public function onRegistrationPageLoad(Fields $fields) {
        if (Util::getSetting('mc_username_registration', '1', 'Minecraft Integration') != '1') {
            return;
        }

        $username_value = ((isset($_POST['username']) && $_POST['username']) ? Output::getClean(Input::get('username')) : '');

        $fields->add('username', Fields::TEXT, $this->_language->get('user', 'minecraft_username'), true, $username_value, null, null, 1);
    }

    public function beforeRegistrationValidation(Validate $validate) {
        // Nothing to do here
    }

    public function afterRegistrationValidation() {
        if (Util::getSetting('mc_username_registration', '1', 'Minecraft Integration') != '1') {
            return;
        }

        $username = Input::get('username');

        // Validate username
        if (!$this->validateUsername($username)) {
            return;
        }

        // Get minecraft UUID
        $result = $this->getUuidByUsername($username);
        if (count($this->getErrors())) {
            return;
        }
        $this->_uuid = $result['uuid'];

        // Validate identifier
        if (!$this->validateIdentifier($this->_uuid)) {
            return;
        }
    }

    public function successfulRegistration(User $user) {
        if (Util::getSetting('mc_username_registration', '1', 'Minecraft Integration') != '1') {
            return;
        }

        $code = SecureRandom::alphanumeric();

        $integrationUser = new IntegrationUser($this);
        $integrationUser->linkIntegration($user, $this->_uuid, Input::get('username'), false, $code);
    }

    public function syncIntegrationUser(IntegrationUser $integration_user): bool {
        $profile = ProfileUtils::getProfile($integration_user->data()->identifier);

        if ($profile) {
            $result = $profile->getUsername();

            if (!empty($result)) {
                $integration_user->update([
                    'username' => $result,
                    'last_sync' => date('U')
                ]);

                return true;
            }
        }

        return false;
    }

    /**
     * Get minecraft UUID by username
     *
     * @param string $username
     * @return array
     */
    public function getUuidByUsername(string $username): array {
        if (Util::getSetting('uuid_linking')) {
            return $this->getOnlineModeUuid($username);
        }

        return ProfileUtils::getOfflineModeUuid($username);
    }

    /**
     * Query mojang api to get online-mode UUID.
     *
     * @param string $username
     * @return array
     */
    public function getOnlineModeUuid(string $username): array {
        $profile = ProfileUtils::getProfile(str_replace(' ', '%20', $username));

        $mcname_result = $profile ? $profile->getProfileAsArray() : [];
        if (isset($mcname_result['username'], $mcname_result['uuid']) && !empty($mcname_result['username']) && !empty($mcname_result['uuid'])) {
            // Valid
            return [
                'uuid' => $mcname_result['uuid'],
                'username' => $mcname_result['username']
            ];
        }

        // Invalid
        $this->addError($this->_language->get('user', 'invalid_mcname'));

        return [];
    }
}
