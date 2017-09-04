<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Login page
 */

// Set page name variable
define('PAGE', 'login');

// Requirements
require('core/includes/password.php'); // For password hashing
require('core/includes/phpass.php'); // phpass for Wordpress auth
require('core/includes/tfa/autoload.php'); // Two Factor Auth

// Ensure user isn't already logged in
if ($user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
    die();
}

// Are custom usernames enabled?
$custom_usernames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
$custom_usernames = $custom_usernames[0]->value;

// Deal with input
if (Input::exists()) {
    // Check form token
    if (Token::check(Input::get('token'))) {
        // Valid token
        if (isset($_SESSION['remember'])) {
            $_POST['remember'] = $_SESSION['remember'];
            $_POST['username'] = $_SESSION['username'];
            $_POST['password'] = $_SESSION['password'];

            unset($_SESSION['remember']);
            unset($_SESSION['username']);
            unset($_SESSION['password']);
        }

        // Initialise validation
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true, 'isbanned' => true, 'isactive' => true),
            'password' => array('required' => true)
        ));

        // Check if validation passed
        if ($validation->passed()) {
            $user_query = $queries->getWhere('users', array('username', '=', Input::get('username')));
            if (count($user_query)) {
                if ($user_query[0]->tfa_enabled == 1 && $user_query[0]->tfa_complete == 1) {
                    if (!isset($_POST['tfa_code'])) {
                        if ($user_query[0]->tfa_type == 0) {
                            // Emails
                            // TODO

                        } else {
                            // App
                            require('core/includes/tfa_signin.php');
                            die();
                        }
                    } else {
                        // Validate code
                        if ($user_query[0]->tfa_type == 1) {
                            // App
                            $tfa = new \RobThree\Auth\TwoFactorAuth('NamelessMC');

                            if ($tfa->verifyCode($user_query[0]->tfa_secret, $_POST['tfa_code']) !== true) {
                                Session::flash('tfa_signin', $language->get('user', 'invalid_tfa'));
                                require('core/includes/tfa_signin.php');
                                die();
                            }

                        } else {
                            // Email
                            // TODO
                        }
                    }
                }

                // Validation passed
                // Initialise user class
                $user = new User();

                // Did the user check 'remember me'?
                $remember = (Input::get('remember') == 1) ? true : false;

                // Is Minecraft and AuthMe integration enabled?
                $minecraft = $queries->getWhere('settings', array('name', '=', 'mc_integration'));
                $minecraft = $minecraft[0]->value;

                $authme_enabled = $queries->getWhere('settings', array('name', '=', 'authme'));
                $authme_enabled = $authme_enabled[0]->value;

                $cache->setCache('authme_cache');
                $authme_db = $cache->retrieve('authme');

                if ($minecraft == '1' && $authme_enabled == '1' && $authme_db['sync'] == '1') {

                    // Sync AuthMe password
                    try {
                        $authme_conn = new mysqli($authme_db['address'], $authme_db['user'], $authme_db['pass'], $authme_db['db'], $authme_db['port']);

                        if ($authme_conn->connect_errno) {
                            // Connection error
                            // Continue anyway, and use already stored password
                        } else {
                            // Success, check user exists in database and validate password
                            $stmt = $authme_conn->prepare("SELECT password FROM " . $authme_db['table'] . " WHERE realname = ?");
                            if ($stmt) {
                                $stmt->bind_param('s', Input::get('username'));
                                $stmt->execute();
                                $stmt->bind_result($password);

                                while ($stmt->fetch()) {
                                    // Retrieve result
                                }

                                $stmt->free_result();
                                $stmt->close();

                                switch ($authme_db['hash']) {
                                    case 'sha256':
                                        $exploded = explode('$', $password);
                                        $salt = $exploded[2];

                                        $password = $salt . '$' . $exploded[3];

                                        break;

                                    case 'pbkdf2':
                                        $exploded = explode('$', $password);

                                        $iterations = $exploded[1];
                                        $salt = $exploded[2];
                                        $pass = $exploded[3];

                                        $password = $iterations . '$' . $salt . '$' . $pass;

                                        break;
                                }

                                // Update password
                                if (!is_null($password)) {
                                    $queries->update('users', $user->NameToId(Input::get('username')), array(
                                        'password' => $password,
                                        'pass_method' => $authme_db['hash']
                                    ));
                                }
                            }
                        }

                    } catch (Exception $e) {
                        // Error, continue as we can use the already stored password
                    }
                }

                $login = $user->login(Input::get('username'), Input::get('password'), $remember);

                // Successful login?
                if ($login) {
                    // Yes

                    // Redirect to a certain page?
                    if (isset($_SESSION['last_page']) && substr($_SESSION['last_page'], -1) != '=') {
                        Redirect::to($_SESSION['last_page']);
                        die();

                    } else {
                        Session::flash('home', $language->get('user', 'successful_login'));
                        Redirect::to(URL::build('/'));
                        die();
                    }
                } else {
                    // No, output error
                    $return_error = array($language->get('user', 'incorrect_details'));
                }

            } else $return_error = array($language->get('user', 'incorrect_details'));


        } else {
            // Validation failed
            $return_error = array();
            foreach ($validation->errors() as $error) {
                if (strpos($error, 'is required') !== false) {
                    if (strpos($error, 'username') !== false) {
                        // Empty username field
                        $return_error[] = $language->get('user', 'must_input_username');
                    } else if (strpos($error, 'password') !== false) {
                        // Empty password field
                        $return_error[] = $language->get('user', 'must_input_password');
                    }
                }
                if (strpos($error, 'active') !== false) {
                    // Account hasn't been activated
                    $return_error[] = $language->get('user', 'inactive_account');
                }
                if (strpos($error, 'banned') !== false) {
                    // Account is banned
                    $return_error[] = $language->get('user', 'account_banned');
                }
            }
        }
    } else {
        // Invalid token
        $return_error = array($language->get('general', 'invalid_token'));
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo(defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo SITE_NAME; ?> - community login form">
    <meta name="author" content="<?php echo SITE_NAME; ?>">
    <?php if (isset($custom_meta)) {
        echo $custom_meta;
    } ?>

    <?php
    $title = $language->get('general', 'sign_in');
    require('core/templates/header.php');
    ?>

    <!-- Custom style -->
    <style>
        html {
            overflow-y: scroll;
        }
    </style>

</head>
<body>
<?php
// Generate navbar and footer
require('core/templates/navbar.php');
require('core/templates/footer.php');

// Sign in template
// Generate content
$smarty->assign(array(
    'USERNAME' => (($custom_usernames == 'false') ? $language->get('user', 'minecraft_username') : $language->get('user', 'username')),
    'USERNAME_INPUT' => Output::getClean(Input::get('username')),
    'PASSWORD' => $language->get('user', 'password'),
    'REMEMBER_ME' => $language->get('user', 'remember_me'),
    'FORGOT_PASSWORD_URL' => URL::build('/forgot_password'),
    'FORGOT_PASSWORD' => $language->get('user', 'forgot_password'),
    'FORM_TOKEN' => Token::generate(),
    'SIGN_IN' => $language->get('general', 'sign_in'),
    'REGISTER_URL' => URL::build('/register'),
    'REGISTER' => $language->get('general', 'register'),
    'ERROR' => (isset($return_error) ? $return_error : array()),
    'SUBMIT' => $language->get('general', 'submit')
));

$register_url = URL::build('/register');

// Smarty variables
$smarty->assign('SIGNIN', $language->get('general', 'sign_in'));
$smarty->assign('REGISTER_URL', $register_url);
$smarty->assign('REGISTER', $language->get('general', 'register'));

if (isset($return_error)) {
    $smarty->assign('SESSION_FLASH', $return_error);
} else {
    $smarty->assign('SESSION_FLASH', '');
}

if (Session::exists('login_success'))
    $smarty->assign('SUCCESS', Session::flash('login_success'));

// Display template
$smarty->display('custom/templates/' . TEMPLATE . '/login.tpl');

// Scripts
require('core/templates/scripts.php');
?>
</body>
</html>
