<?php
/**
 * MinecraftIntegration class
 *
 * @package Modules\Core\Integrations
 * @author Partydragen
 * @version 2.0.0-pr13
 * @license MIT
 */
class MinecraftIntegration extends IntegrationBase {

    protected Language $_language;
    private string $_uuid;

    public function __construct(Language $language) {
        $this->_name = 'Minecraft';
        $this->_icon = 'fas fa-cubes';
        $this->_language = $language;

        parent::__construct();
    }

    public function onLinkRequest(User $user) {
        $username = $user->data()->username;

        // Ensure username doesn't already exist
        $integrationUser = new IntegrationUser($this, $username, 'username');
        if ($integrationUser->exists()) {
            $this->addError(str_replace('{x}', $this->getName(), $this->_language->get('user', 'integration_username_already_linked')));
            return;
        }

        $result = $this->getUuidByUsername($username);
        if (count($this->getErrors())) {
            return;
        }
        $this->_uuid = $result['uuid'];

        // Ensure identifier doesn't already exist
        $integrationUser = new IntegrationUser($this, $this->_uuid, 'identifier');
        if ($integrationUser->exists()) {
            $this->addError(str_replace('{x}', $this->getName(), $this->_language->get('user', 'integration_identifier_already_linked')));
            return;
        }

        $code = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10);

        $integrationUser = new IntegrationUser($this);
        $integrationUser->linkIntegration($user, $this->_uuid, $username, false, $code);

        Session::flash('connections_success', str_replace('{x}', Output::getClean($code), $this->_language->get('user', 'validate_account_command')));
    }

    public function onVerifyRequest(User $user) {
        $integrationUser = new IntegrationUser($this, $user->data()->id, 'user_id');

        Session::flash('connections_success', str_replace('{x}', Output::getClean($integrationUser->data()->code), $this->_language->get('user', 'validate_account_command')));
    }

    public function onUnlinkRequest(User $user) {
        $integrationUser = new IntegrationUser($this, $user->data()->id, 'user_id');
        $integrationUser->unlinkIntegration();

        Session::flash('connections_success', str_replace('{x}', Output::getClean($this->_name), $this->_language->get('user', 'integration_unlinked')));
    }

    public function onSuccessfulVerification(IntegrationUser $integrationUser) {
    }

    public function onRegistrationPageLoad(Fields $fields) {
        $fields->add('username', Fields::TEXT, $this->_language->get('user', 'minecraft_username'), true, $_POST['username'] ?? '', null, null, 1);
    }

    public function beforeRegistrationValidation(Validate $validate) {
    }

    public function afterRegistrationValidation() {
        $username = Input::get('username');

        // Ensure username doesn't already exist
        $integrationUser = new IntegrationUser($this, $username, 'username');
        if ($integrationUser->exists()) {
            $this->addError(str_replace('{x}', $this->getName(), $this->_language->get('user', 'integration_username_already_linked')));
            return;
        }

        $result = $this->getUuidByUsername($username);
        if (count($this->getErrors())) {
            return;
        }
        $this->_uuid = $result['uuid'];

        // Ensure identifier doesn't already exist
        $integrationUser = new IntegrationUser($this, $this->_uuid, 'identifier');
        if ($integrationUser->exists()) {
            $this->addError(str_replace('{x}', $this->getName(), $this->_language->get('user', 'integration_identifier_already_linked')));
            return;
        }
    }

    public function successfulRegistration(User $user) {
        $code = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10);

        $integrationUser = new IntegrationUser($this);
        $integrationUser->linkIntegration($user, $this->_uuid, Input::get('username'), false, $code);
    }

    /**
     * Get minecraft UUID by username
     *
     * @param string $username
     * @return array
     */
    public function getUuidByUsername(string $username): array {
        $queries = new Queries();

        $uuid_linking = $queries->getWhere('settings', ['name', '=', 'uuid_linking']);
        $uuid_linking = $uuid_linking[0]->value;

        if ($uuid_linking == 1) {
            return $this->getOnlineModeUuid($username);
        } else {
            return $this->getOfflineModeUuid($username);
        }
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
            $result = [
                'uuid' => $mcname_result['uuid'],
                'username' => $mcname_result['username']
            ];

            return $result;
        } else {
            // Invalid
            $this->addError($this->_language->get('user', 'invalid_mcname'));
        }

        return [];
    }

    /**
     * Generate an offline minecraft UUID v3 based on the case sensitive player name.
     *
     * @param string $username
     * @return array
     */
    public function getOfflineModeUuid(string $username): array {
        $data = hex2bin(md5("OfflinePlayer:" . $username));
        $data[6] = chr(ord($data[6]) & 0x0f | 0x30);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return [
            'uuid' => bin2hex($data),
            'username' => $username
        ];
    }
}
