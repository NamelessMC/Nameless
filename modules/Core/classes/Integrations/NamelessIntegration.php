<?php
/**
 * NamelessIntegration class
 *
 * @package Modules\Core\Integrations
 * @author Partydragen
 * @version 2.0.2
 * @license MIT
 */
class NamelessIntegration extends IntegrationBase {

    protected Language $_language;

    public function __construct(Language $language) {
        $this->_name = 'NamelessMC';
        $this->_icon = 'fas fa-browser';
        $this->_language = $language;

        parent::__construct();
    }

    public function onLinkRequest(User $user) {
        Session::put('oauth_method', 'link_integration');

        $providers = NamelessOAuth::getInstance()->getProvidersAvailable();
        $provider = $providers['namelessmc'];
        if ($provider == null) {
            Session::flash('connections_error', $this->_language->get('general', 'oauth_failed_setup'));
            return;
        }

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
            ]
        ])->messages([
            'username' => [
                Validate::REQUIRED => $this->_language->get('admin', 'integration_username_required', ['integration' => $this->getName()])
            ]
        ]);

        return $validation->passed();
    }

    public function validateIdentifier(string $identifier, int $integration_user_id = 0): bool {
        $validation = Validate::check(['identifier' => $identifier], [
            'identifier' => [
                Validate::REQUIRED => true,
                Validate::NUMERIC => true
            ]
        ])->messages([
            'identifier' => [
                Validate::REQUIRED => $this->_language->get('admin', 'integration_identifier_required', ['integration' => $this->getName()]),
                Validate::NUMERIC => $this->_language->get('admin', 'integration_identifier_invalid', ['integration' => $this->getName()])
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
        return NamelessOAuth::getInstance()->isEnabled('namelessmc');
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
        // Link integration from oauth
        if (Session::exists('oauth_register_data')) {
            $data = json_decode(Session::get('oauth_register_data'), true);
            if ($data['provider'] == 'namelessmc' && isset($data['data']['id']) && isset($data['data']['username'])) {

                $id = $data['data']['id'];
                $username = $data['data']['username'];
                if ($this->validateIdentifier($id) && $this->validateUsername($username)) {
                    $integrationUser = new IntegrationUser($this);
                    $integrationUser->linkIntegration($user, $id, $username, true);
                    $integrationUser->verifyIntegration();
                }
            }

            Session::flash('connections_success', $this->_language->get('user', 'integration_linked', ['integration' => Output::getClean($this->_name)]));
        }
    }

    public function syncIntegrationUser(IntegrationUser $integration_user): bool {
        return false;
    }
}