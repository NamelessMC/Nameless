<?php
declare(strict_types=1);

use DebugBar\DebugBarException;
use GuzzleHttp\Exception\GuzzleException;

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

    /**
     * @param Language $language
     */
    public function __construct(Language $language) {
        $this->_name = 'Minecraft';
        $this->_icon = 'fas fa-cubes';
        $this->_language = $language;
        $this->_settings = ROOT_PATH . '/modules/Core/includes/admin_integrations/minecraft.php';

        parent::__construct();
    }

    /**
     * @param string $verification_code
     * @return void
     */
    private function flashVerifyCommand(string $verification_code): void {
        $verification_command = Output::getClean(Util::getSetting('minecraft_verify_command', '/verify'));
        $message = $this->_language->get('user', 'validate_account_command', ['command' => $verification_command . ' ' . $verification_code]);
        Session::flash('connections_success', $message);
    }

    /**
     * Called when user wants to link their account from user connections page, Does not need to be verified
     *
     * @throws Exception|GuzzleException
     */
    public function onLinkRequest(User $user): void {
        $data = $user->data();
        $username = $data === null ? null : $data->username;

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

    /**
     * Called when user wants to continue to verify their integration user from connections page
     */
    public function onVerifyRequest(User $user): void {
        $integrationUser = new IntegrationUser($this, (string)$user->data()->id, 'user_id');
        $this->flashVerifyCommand($integrationUser->data()->code);
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
     */
    public function onSuccessfulVerification(IntegrationUser $integrationUser): void {
        // Nothing to do here
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
        return $this->validateField('username', $username, [
            'username' => [
                Validate::REQUIRED => true,
                Validate::MIN => 3,
                Validate::MAX => 20
            ]
        ], [
            'username' => [
                Validate::REQUIRED => $this->_language->get('admin', 'integration_username_required', ['integration' => $this->getName()]),
                Validate::MIN => $this->_language->get('user', 'mcname_minimum_3'),
                Validate::MAX => $this->_language->get('user', 'mcname_maximum_20')
            ]
        ], $integration_user_id);
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
        return $this->validateField('identifier', $identifier, [
            'identifier' => [
                Validate::REQUIRED => true,
                Validate::MIN => 32,
                Validate::MAX => 32
            ]
        ], [
            'identifier' => [
                Validate::REQUIRED => $this->_language->get('admin', 'integration_identifier_required', ['integration' => $this->getName()]),
                Validate::MIN => $this->_language->get('admin', 'integration_identifier_invalid', ['integration' => $this->getName()]),
                Validate::MAX => $this->_language->get('admin', 'integration_identifier_invalid', ['integration' => $this->getName()]),
            ]
        ], $integration_user_id);
    }

    /**
     * Validate a value when it being linked or updated.
     *
     * @param string $field The field name to validate.
     * @param string $value The value to validate.
     * @param array $rules The validation rules to apply.
     * @param array $messages The validation error messages to use.
     * @param string $integration_user_id The integration user id to ignore during duplicate check.
     *
     * @return bool Whether this validation passed or not.
     * @throws Exception
     */
    public function validateField(string $field, string $value, array $rules, array $messages, string $integration_user_id = '0'): bool {
        $validation = Validate::check([$field => $value], $rules)->messages($messages);

        if (count($validation->errors())) {
            // Validation errors
            foreach ($validation->errors() as $error) {
                $this->addError($error);
            }
        } else {
            // Ensure value doesn't already exist
            $exists = DB::getInstance()->query("SELECT * FROM nl2_users_integrations WHERE integration_id = ? AND $field = ? AND id <> ?", [$this->data()->id, $value, $integration_user_id]);
            if ($exists->count()) {
                $error_message = $this->_language->get('user', "integration_{$field}_already_linked", ['integration' => $this->getName()]);
                $this->addError($error_message);
                return false;
            }
        }

        return $validation->passed();
    }

    /**
     * Called when register page being loaded
     */
    public function onRegistrationPageLoad(Fields $fields): void {
        if (Util::getSetting('mc_username_registration', '1', 'Minecraft Integration') !== '1') {
            return;
        }

        $username_value = ((isset($_POST['username']) && $_POST['username']) ? Output::getClean(Input::get('username')) : '');

        $fields->add('username', Fields::TEXT, $this->_language->get('user', 'minecraft_username'), true, $username_value, null, null, 1);
    }

    /**
     * Called before registration validation
     */
    public function beforeRegistrationValidation(Validate $validate): void {
        // Nothing to do here
    }

    /**
     * Called after registration validation
     *
     * @throws Exception
     */
    public function afterRegistrationValidation(): void {
        if (Util::getSetting('mc_username_registration', '1', 'Minecraft Integration') !== '1') {
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

    /**
     * Called when user is successfully registered
     *
     * @throws Exception|GuzzleException
     */
    public function successfulRegistration(User $user): void {
        if (Util::getSetting('mc_username_registration', '1', 'Minecraft Integration') !== '1') {
            return;
        }

        $code = SecureRandom::alphanumeric();

        $integrationUser = new IntegrationUser($this);
        $integrationUser->linkIntegration($user, $this->_uuid, Input::get('username'), false, $code);
    }

    /**
     * Called when user integration is requested to be synced.
     *
     * @throws DebugBarException
     */
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
     * @throws DebugBarException
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
     * @throws DebugBarException
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
