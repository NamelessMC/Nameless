<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  API signup completion
 */

$page = 'complete_signup';
define('PAGE', 'complete_signup');

// Validate code
if(!isset($_GET['c'])){
    Redirect::to(URL::build('/'));
    die();
} else {
    require(ROOT_PATH . '/core/includes/password.php');

    // Ensure API is enabled
    $is_legacy_enabled = $queries->getWhere('settings', array('name', '=', 'use_legacy_api'));
    if($is_legacy_enabled[0]->value != '1'){
        die('Legacy API is disabled');
    }

    if(!$user->isLoggedIn()){
        $check = $queries->getWhere('users', array('reset_code', '=', $_GET['c']));
        if(count($check)){
            if(Input::exists()){
                if(Token::check(Input::get('token'))){
                    // Validate input
                    $to_validation = array(
                        'password' => array(
                            'required' => true,
                            'min' => 6,
                            'max' => 30
                        ),
                        'password_again' => array(
                            'matches' => 'password'
                        ),
                        't_and_c' => array(
                            'required' => true,
                            'agree' => true
                        )
                    );

                    $validate = new Validate();
                    $validation = $validate->check($_POST, $to_validation);

                    if($validation->passed()){
                        // Complete registration
                        $check = $check[0];

                        // Hash password
                        $password = password_hash(Input::get('password'), PASSWORD_BCRYPT, array("cost" => 13));

                        try {
                            $queries->update('users', $check->id, array(
                                'password' => $password,
                                'reset_code' => null,
                                'last_online' => date('U'),
                                'active' => 1
                            ));
                        } catch(Exception $e){
                            die($e->getMessage());
                        }

                        Session::flash('home', $language->get('user', 'validation_complete'));
                        Redirect::to(URL::build('/'));
                        die();

                    } else {
                        // Errors
                        $errors = array();

                        foreach($validation->errors() as $validation_error){
                            if(strpos($validation_error, 'is required') !== false){
                                // x is required
                                switch($validation_error){
                                    case (strpos($validation_error, 'password') !== false):
                                        $errors[] = $language->get('user', 'password_required');
                                        break;
                                    case (strpos($validation_error, 't_and_c') !== false):
                                        $errors[] = $language->get('user', 'accept_terms');
                                        break;
                                }

                            } else if(strpos($validation_error, 'minimum') !== false){
                                $errors[] = $language->get('user', 'password_minimum_6');

                            } else if(strpos($validation_error, 'maximum') !== false){
                                $errors[] = $language->get('user', 'password_maximum_30');


                            } else if(strpos($validation_error, 'must match') !== false){
                                // password must match password again
                                $errors[] = $language->get('user', 'passwords_dont_match');
                            }
                        }
                    }

                } else {
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }
        } else {
            Session::flash('home', $language->get('user', 'validation_error'));
            Redirect::to(URL::build('/'));
            die();
        }
    } else {
        Redirect::to(URL::build('/'));
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?php echo SITE_NAME; ?> - complete account registration">

        <!-- Site Properties -->
        <?php
        $title = $language->get('general', 'register');
        require(ROOT_PATH . '/core/templates/header.php');
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
    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Smarty variables
    if(isset($errors) && count($errors)){
        $smarty->assign('ERRORS', $errors);
    }

    $smarty->assign(array(
        'REGISTER' => $language->get('general', 'register'),
        'PASSWORD' => $language->get('user', 'password'),
        'CONFIRM_PASSWORD' => $language->get('user', 'confirm_password'),
        'SUBMIT' => $language->get('general', 'submit'),
        'I_AGREE' => $language->get('user', 'i_agree'),
        'AGREE_TO_TERMS' => str_replace('{x}', URL::build('/terms'), $language->get('user', 'agree_t_and_c')),
        'TOKEN' => Token::get()
    ));

    $smarty->display(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/complete_signup.tpl');

    require(ROOT_PATH . '/core/templates/scripts.php');
    ?>
    </body>
</html>
