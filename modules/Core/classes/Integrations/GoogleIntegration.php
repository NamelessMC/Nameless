<?php
/**
 * GoogleIntegration class
 *
 * @package Modules\Core\Integrations
 * @author Partydragen
 * @version 2.1.2
 * @license MIT
 */
class GoogleIntegration extends IntegrationBase {

    protected Language $_language;

    public function __construct(Language $language) {
        $this->_name = 'Google';
        $this->_icon = 'fab fa-google';
        $this->_language = $language;

        parent::__construct();
    }

    public function onLinkRequest(User $user) {
        Session::put('oauth_method', 'link_integration');

        $providers = NamelessOAuth::getInstance()->getProvidersAvailable();
        $provider = $providers['google'];

        Redirect::to($provider['url']);
    }

    public function onVerifyRequest(User $user) {
        // Nothing to do here
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
                Validate::MIN => 1,
                Validate::MAX => 64
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
                Validate::MIN => 1,
                Validate::MAX => 64
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

    public function allowLinking(): bool {
        return NamelessOAuth::getInstance()->isSetup('google');
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
        // Link integration if user registered using google oauth
        if (Session::exists('oauth_register_data')) {
            $data = json_decode(Session::get('oauth_register_data'), true);
            if ($data['provider'] == 'google' && isset($data['data']['name'])) {

                $username = $data['data']['name'];
                $google_id = $data['id'];
                if ($this->validateIdentifier($google_id) && $this->validateUsername($username)) {
                    $integrationUser = new IntegrationUser($this);
                    $integrationUser->linkIntegration($user, $google_id, $username, true);
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