<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Admin index page
 */

// Can the user view the AdminCP?
if($user->isLoggedIn()){
	if(!$user->canViewACP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	}
	if($user->isAdmLoggedIn()){
		// Already authenticated
		Redirect::to(URL::build('/admin'));
		die();
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

require(ROOT_PATH . '/core/includes/password.php'); // Require password compat library

// Get login method
$method = $queries->getWhere('settings', array('name', '=', 'login_method'));
$method = $method[0]->value;

// Deal with any input
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		// Validate input
		$validate = new Validate();

        if($method == 'email')
            $to_validate = array(
                'email' => array('required' => true, 'isbanned' => true, 'isactive' => true),
                'password' => array('required' => true)
            );
        else
            $to_validate = array(
                'username' => array('required' => true, 'isbanned' => true, 'isactive' => true),
                'password' => array('required' => true)
            );

		$validation = $validate->check($_POST, $to_validate);
		
		if($validation->passed()) {
            if($method == 'email')
                $username = Input::get('email');
            else
                $username = Input::get('username');

			$user = new User();
			$login = $user->adminLogin($username, Input::get('password'), $method);
			
			if($login){
				// Get IP
				$ip = $user->getIP();
				
				// Create log
				Log::getInstance()->log(Log::Action('admin/login'));
				
				Redirect::to(URL::build('/admin'));
				die();
			} else {
				Session::flash('adm_auth_error', $language->get('user', 'incorrect_details'));
			}
		} else {
			Session::flash('adm_auth_error', $language->get('user', 'incorrect_details'));
		}
	} else {
		// Invalid token
		Session::flash('adm_auth_error', $language->get('general', 'invalid_token'));
	}
}

$page = 'admin';
$admin_page = 'auth';
?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>" <?php if(defined('HTML_RTL') && HTML_RTL === true) echo ' dir="rtl"'; ?>>
  <head>
    <!-- Standard Meta -->
    <meta charset="<?php echo (defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<?php 
	$title = $language->get('admin', 'admin_cp');
	require(ROOT_PATH . '/core/templates/admin_header.php');
	?>
  
  </head>
  <body>
	<div class="container">
      <div style="height:15vh"></div>
      <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 offset-sm-2 offset-md-3">
          <form role="form" action="" method="post">
            <div class="card">
              <div class="card-block">
                <div style="text-align:center"><h2><?php echo $language->get('admin', 're-authenticate'); ?></h2></div>
                <?php
                  if(Session::exists('adm_auth_error')){
                      echo '<div class="alert alert-danger">' . Session::flash('adm_auth_error') . '</div>';
                  }
                ?>
                <div class="form-group">
                  <?php if($method == 'email'){ ?>
                    <input type="email" name="email" id="email" autocomplete="off" value="<?php echo Output::getClean(Input::get('email')); ?>" class="form-control" placeholder="<?php echo $language->get('user', 'email'); ?>" tabindex="3">
                  <?php } else { ?>
                    <input type="text" name="username" id="username" autocomplete="off" value="<?php echo Output::getClean(Input::get('username')); ?>" class="form-control" placeholder="<?php echo $language->get('user', 'username'); ?>" tabindex="3">
                  <?php } ?>
                </div>
                <div class="form-group">
                  <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo $language->get('user', 'password'); ?>" tabindex="4">
                </div>

                <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                <div style="text-align:center">
                  <input type="submit" value="<?php echo $language->get('general', 'sign_in'); ?>" class="btn btn-primary btn-lg text-center mx-auto" tabindex="5">
                  <a href="<?php echo URL::build('/'); ?>" class="btn btn-danger btn-lg text-center mx-auto"><?php echo $language->get('general', 'back'); ?></a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
	</div>
  </body>
</html>