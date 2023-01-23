<?php
if (isset($_SESSION['admin_setup']) && $_SESSION['admin_setup'] == true) {
    Redirect::to('?step=conversion');
}

if (!isset($_SESSION['site_initialized']) || $_SESSION['site_initialized'] != true) {
    Redirect::to('?step=site_configuration');
}

function display_error(string $message) {
    echo "<div class=\"ui error message\">$message</div>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_min = 3;
    $username_max = 20;
    $email_min = 4;
    $email_max = 64;
    $password_min = 6;
    $password_max = 30;

    $validation = Validate::check($_POST, [
        'username' => [
            Validate::REQUIRED => true,
            Validate::MIN => $username_min,
            Validate::MAX => $username_max,
        ],
        'email' => [
            Validate::REQUIRED => true,
            Validate::MIN => $email_min,
            Validate::MAX => $email_max,
            Validate::EMAIL => true,
        ],
        'password' => [
            Validate::REQUIRED => true,
            Validate::MIN => $password_min,
            Validate::MAX => $password_max,
        ],
        'password_again' => [
            Validate::REQUIRED => true,
            Validate::MATCHES => 'password',
        ],
    ]);

    if (!$validation->passed()) {
        foreach ($validation->errors() as $item) {
            if (str_contains($item, 'is required')) {
                display_error($language->get('installer', 'input_required'));
            } else if (str_contains($item, 'minimum')) {
                display_error($language->get('installer', 'input_minimum', [
                    'minUsername' => $username_min,
                    'minEmail' => $email_min,
                    'minPassword' => $password_min,
                ]));
            } else if (str_contains($item, 'maximum')) {
                display_error($language->get('installer', 'input_maximum', [
                    'maxUsername' => $username_max,
                    'maxEmail' => $email_max,
                    'maxPassword' => $password_max,
                ]));
            } else if (str_contains($item, 'must match')) {
                display_error($language->get('installer', 'passwords_must_match'));
            } else if (str_contains($item, 'not a valid email')) {
                display_error($language->get('installer', 'email_invalid'));
            }
        }

    } else {
        $user = new User();
        $password = password_hash(Input::get('password'), PASSWORD_BCRYPT, ['cost' => 13]);

        try {
            $default_language = DB::getInstance()->get('languages', ['is_default', true])->results();

            $ip = HttpUtils::getRemoteAddress();

            $user->create([
                'username' => Input::get('username'),
                'nickname' => Input::get('username'),
                'password' => $password,
                'pass_method' => 'default',
                'joined' => date('U'),
                'email' => Input::get('email'),
                'lastip' => $ip,
                'active' => true,
                'last_online' => date('U'),
                'language_id' => $default_language[0]->id,
                'timezone' => $_SESSION['install_timezone'],
            ]);

            $profile = ProfileUtils::getProfile(Output::getClean(Input::get('username')));
            if ($profile !== null) {
                $result = $profile->getProfileAsArray();
                if (isset($result['uuid']) && !empty($result['uuid'])) {
                    $uuid = $result['uuid'];

                    DB::getInstance()->insert('users_integrations', [
                        'integration_id' => 1,
                        'user_id' => 1,
                        'identifier' => $uuid,
                        'username' => Input::get('username'),
                        'verified' => true,
                        'date' => date('U'),
                    ]);
                }
            }

            DatabaseInitialiser::runPostUser();

            $login = $user->login(Input::get('email'), Input::get('password'), true);
            if ($login) {
                $_SESSION['admin_setup'] = true;
                $user->addGroup(2);

                Redirect::to('?step=conversion');
            }

            DB::getInstance()->delete('users', ['id', 1]);
            display_error($language->get('installer', 'unable_to_login'));
        } catch (Exception $e) {
            display_error($language->get('installer', 'unable_to_create_account') . ': ' . $e->getMessage());
        }
    }
}
?>

<form action="" method="post" id="form-user">
    <div class="ui segments">
        <div class="ui secondary segment">
            <h4 class="ui header">
                <?php echo $language->get('installer', 'creating_admin_account'); ?>
            </h4>
        </div>
        <div class="ui segment">
            <p><?php echo $language->get('installer', 'enter_admin_details'); ?></p>
            <div class="ui centered grid">
                <div class="sixteen wide mobile twelve wide tablet ten wide computer column">
                    <div class="ui form">
                        <?php
                        create_field('text', $language->get('installer', 'username'), 'username', 'inputUsername', getenv('NAMELESS_ADMIN_USERNAME') ?: '');
                        create_field('email', $language->get('installer', 'email_address'), 'email', 'inputEmail', getenv('NAMELESS_ADMIN_EMAIL') ?: '');
                        create_field('password', $language->get('installer', 'password'), 'password', 'inputPassword');
                        create_field('password', $language->get('installer', 'confirm_password'), 'password_again', 'inputPasswordAgain');
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui right aligned secondary segment">
            <button type="submit" class="ui small primary button">
                <?php echo $language->get('installer', 'proceed'); ?>
            </button>
        </div>
    </div>
</form>
