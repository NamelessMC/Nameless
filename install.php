<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Installer
 */

// Definitions
if(!defined('PATH')){
    define('PATH', '/');
    define('ROOT_PATH', dirname(__FILE__));
}
$page = 'install';

// Start initialising the page
require('core/init.php');

// Disable error reporting
error_reporting(0);
ini_set('display_errors', 0);

// Set default timezone to prevent potential issues
date_default_timezone_set('Europe/London');
?>

<html lang="en">
<head>
    <!-- Page Title -->
    <title>Install &bull; NamelessMC</title>

    <!-- Global CSS -->
    <link rel="stylesheet" href="core/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="core/assets/css/custom.css">
    <link rel="stylesheet" href="core/assets/css/font-awesome.min.css">

    <style>
        html {
            overflow-y: scroll;
        }
    </style>
</head>

<body>
<div class="jumbotron jumbotron-fluid" style="height:100vh; margin-bottom:0rem !important;">
    <center>
        <h1>NamelessMC v2 <sup><span style="font-size: small;">pre-release</span></sup></h1>

        <hr />

        <?php
        if(!isset($_GET['do']) && !isset($_GET['step'])){
            ?>
            <p>Welcome to NamelessMC version 2.0 pre-release.</p>

            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="alert alert-danger">
                        Please note that this pre-release is not intended for use on a public site.
                    </div>
                </div>
            </div>

            <p>The installer will guide you through the installation process.</p>

            <p>Firstly, is this a new installation?</p>

            <a href="?do=install" class="btn btn-primary btn-lg">
                New Installation &raquo;
            </a>

            <a href="?do=upgrade" class="btn btn-warning btn-lg">
                Upgrading from v1 &raquo;
            </a>

            <?php
        } else {
        if(isset($_GET['do'])){
            if($_GET['do'] == 'install'){
                // Fresh install
                $_SESSION['action'] = 'install';
            } else {
                // Upgrade
                $_SESSION['action'] = 'upgrade';
            }

            Redirect::to('?step=requirements');
            die();
        } else {
        switch($_GET['step']){
        case 'requirements':
            // Requirements
            $error = '<p style="display: inline;" class="text-danger"><i class="fa fa-times-circle"></i></p><br />';
            $success = '<p style="display: inline;" class="text-success"><i class="fa fa-check-circle"></i></p><br />';

            ?>
            <h4>Requirements:</h4>

            <?php
            if(version_compare(phpversion(), '5.4', '<')){
                echo 'PHP > 5.4 - ' . $error;
                $php_error = true;
            } else {
                echo 'PHP > 5.4 - ' . $success;
            }

            if(!extension_loaded('gd')){
                echo 'PHP GD Extension - ' . $error;
                $php_error = true;
            } else {
                echo 'PHP GD Extension - ' . $success;
            }
            if(!extension_loaded('PDO')){
                echo 'PHP PDO Extension - ' . $error;
                $php_error = true;
            } else {
                echo 'PHP PDO Extension - ' . $success;
            }
            if(!function_exists('mcrypt_encrypt')) {
                echo 'PHP mcrypt Extension - ' . $error;
                $php_error = true;
            } else {
                echo 'PHP mcrypt Extension - ' . $success;
            }
            if(!function_exists('curl_version')){
                echo 'PHP cURL Extension - ' . $error;
                $php_error = true;
            } else {
                echo 'PHP cURL Extension - ' . $success;
            }
            if(!extension_loaded('xml')){
                echo 'PHP XML Extension - ' . $error;
                $php_error = true;
            } else {
                echo 'PHP XML Extension - ' . $success;
            }

            // Permissions
            if(is_writable('core/config.php')){
                echo 'core/config.php Writable - ' . $success;
            } else {
                echo 'core/config.php Writable - ' . $error;
                $php_error = true;
            }

            if(is_writable('cache')){
                echo 'Cache Writable - ' . $success;
            } else {
                echo 'Cache Writable - ' . $error;
                $php_error = true;
            }

            if(is_writable('cache/templates_c')){
                echo 'Template Cache Writable - ' . $success;
            } else {
                echo 'Template Cache Writable - ' . $error;
                $php_error = true;
            }

            if(isset($php_error)){
                ?>
                <br />
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="alert alert-danger">
                            You must have all of the required extensions installed, and have correct permissions set, in order to proceed with installation.
                        </div>
                    </div>
                </div>
                <?php
            } else {
                echo '<br /><a class="btn btn-primary btn-lg" href="?step=database">Proceed &raquo;</a>';
            }
            break;

        case 'database':
        if(Input::exists()){
            // Ensure all fields are filled
            $validate = new Validate();

            $validation = $validate->check($_POST, array(
                'db_address' => array(
                    'required' => true
                ),
                'db_port' => array(
                    'required' => true
                ),
                'db_username' => array(
                    'required' => true
                ),
                'db_name' => array(
                    'required' => true
                )
            ));

            if($validation->passed()){
                // Check database connection
                if(isset($_POST['db_password']) && !empty($_POST['db_password'])){
                    $password = $_POST['db_password'];
                } else {
                    $password = '';
                }
				
				// Get charset
				if($_POST['charset'] == 'latin1'){
					$charset = 'latin1';
				} else $charset = 'utf8';

                // Get installation path
                $path = substr(str_replace('\\', '/', substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']))), 1);

                $mysqli = new mysqli(Input::get('db_address'), Input::get('db_username'), $password, Input::get('db_name'), Input::get('db_port'));
                if($mysqli->connect_errno) {
                    $error = $mysqli->connect_errno . ' - ' . $mysqli->connect_error;
                } else {
                    // Valid, check if config is writable
                    if(is_writable('core/config.php')){
                        // Writable, attempt to write configuration
                        $insert = 	'<?php' . PHP_EOL .
                            '$GLOBALS[\'config\'] = array(' . PHP_EOL .
                            '	"mysql" => array(' . PHP_EOL .
                            '		"host" => "' . Input::get('db_address') . '", // Web server database IP (Likely to be 127.0.0.1)' . PHP_EOL .
                            '		"username" => "' . Input::get('db_username') . '", // Web server database username' . PHP_EOL .
                            '		"password" => \'' . $password . '\', // Web server database password' . PHP_EOL .
                            '		"db" => "' . Input::get('db_name') . '", // Web server database name' . PHP_EOL .
                            '		"port" => "' . Input::get('db_port') . '", // Web server database port' . PHP_EOL .
                            '		"prefix" => "nl2_" // Web server table prefix' . PHP_EOL .
                            '	),' . PHP_EOL .
                            '	"remember" => array(' . PHP_EOL .
                            '		"cookie_name" => "nlmc", // Name for website cookies' . PHP_EOL .
                            '		"cookie_expiry" => 604800' . PHP_EOL .
                            '	),' . PHP_EOL .
                            '	"session" => array(' . PHP_EOL .
                            '		"session_name" => "user",' . PHP_EOL .
                            '		"admin_name" => "admin",' . PHP_EOL .
                            '		"token_name" => "token"' . PHP_EOL .
                            '	),' . PHP_EOL .
                            '	"core" => array(' . PHP_EOL .
                            '		"path" => "' . $path . '",' . PHP_EOL .
                            '		"friendly" => false' . PHP_EOL .
                            '	)' . PHP_EOL .
                            ');';

                        try {
                            $file = fopen('core/config.php','w');
                            fwrite($file, $insert);
                            fclose($file);
							
							$_SESSION['charset'] = $charset;

                            Redirect::to('?step=database_initialise');
                            die();

                        } catch(Exception $e){
                            $error = $e->getMessage();
                        }

                    } else {
                        // Not writable
                        $error = 'Your <strong>core/config.php</strong> is not writable. Please check file permissions.';
                    }
                }

            } else {
                $error = 'Please ensure all fields have been filled out.';
            }
        }
        ?>
    </center>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h3>Database Configuration</h3>

            <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="inputDBAddress">Database Address</label>
                    <input type="text" class="form-control" name="db_address" id="inputDBAddress" placeholder="Database Address">
                </div>

                <div class="form-group">
                    <label for="inputDBPort">Database Port</label>
                    <input type="text" class="form-control" name="db_port" id="inputDBPort" placeholder="Database Port" value="3306">
                </div>

                <div class="form-group">
                    <label for="inputDBUsername">Database Username</label>
                    <input type="text" class="form-control" name="db_username" id="inputDBUsername" placeholder="Database Username">
                </div>

                <div class="form-group">
                    <label for="inputDBPassword">Database Password</label>
                    <input type="password" class="form-control" name="db_password" id="inputDBPassword" placeholder="Database Password">
                </div>

                <div class="form-group">
                    <label for="inputDBName">Database Name</label>
                    <input type="text" class="form-control" name="db_name" id="inputDBName" placeholder="Database Name">
                </div>
				
				<div class="form-group">
				    <label for="inputCharset">Character set</label>
				    <select class="form-control" name="charset" id="inputCharset">
					    <option value="latin1">latin1</option>
						<option value="utf8" selected>Unicode</option>
					</select>
				</div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </form>
        </div>
    </div>
    <center>
        <?php
        break;

        case 'database_initialise':
            // Initialise database tables
            ?>
            <p>The installer is now initialising the database.</p>
            <p>This may take a while...</p>
            <?php
            try {
				if(isset($_SESSION['charset'])) $charset = $_SESSION['charset'];
				else $charset = 'utf8_unicode_ci';
				
                $queries = new Queries();
                $queries->dbInitialise($charset);
            } catch(Exception $e){
                die($e->getMessage());
            }
			
			if($_SESSION['action'] == 'install'){
				Redirect::to('?step=configuration');
				die();
			} else {
				Redirect::to('?step=upgrade');
				die();
			}

            break;

        case 'configuration':
        // Configure site
        if(Input::exists()){
            // Validate input
            $validate = new Validate();

            try {
                $validation = $validate->check($_POST, array(
                    'sitename' => array(
                        'required' => true,
                        'min' => 1,
                        'max' => 32
                    ),
                    'incoming' => array(
                        'required' => true,
                        'min' => 4,
                        'max' => 64
                    ),
                    'outgoing' => array(
                        'required' => true,
                        'min' => 4,
                        'max' => 64
                    )
                ));
            } catch(Exception $e) { }

            if($validation->passed()){
                $queries = new Queries();

                $queries->create('settings', array(
                    'name' => 'sitename',
                    'value' => Output::getClean(Input::get('sitename'))
                ));

                // Cache
                $cache = new Cache();
                $cache->setCache('sitenamecache');
                $cache->store('sitename', Output::getClean(Input::get('sitename')));

                $queries->create('settings', array(
                    'name' => 'incoming_email',
                    'value' => Output::getClean(Input::get('incoming'))
                ));

                $queries->create('settings', array(
                    'name' => 'outgoing_email',
                    'value' => Output::getClean(Input::get('outgoing'))
                ));

                Redirect::to('?step=initialise');
                die();
            } else {
                $error = 'Please input a valid site name between 1 and 32 characters long, and valid email addresses between 4 and 64 characters long.';
            }

        }

        ?>
    </center>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form action="" method="post">
                <h3>Configuration</h3>
                <p>Please input basic information about your site. These values can be changed later on through the admin panel.</p>

                <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>

                <div class="form-group">
                    <label for="inputSitename">Site Name</label>
                    <input type="text" class="form-control" name="sitename" id="inputSitename" placeholder="Site Name">
                </div>

                <div class="form-group">
                    <label for="inputContactEmail">Contact Email</label>
                    <input type="email" class="form-control" name="incoming" id="inputContactEmail" placeholder="Contact Email">
                </div>

                <div class="form-group">
                    <label for="inputOutgoingEmail">Outgoing Email</label>
                    <input type="email" class="form-control" name="outgoing" id="inputOutgoingEmail" placeholder="Outgoing Email">
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </form>
        </div>
    </div>
    <center>
        <?php
        break;

        case 'initialise':
            // Initialise database and cache
            echo 'Initialising database and cache, please wait...';

            $queries = new Queries();
            $cache = new Cache();

            // Create first category + forum
            $queries->create('forums', array(
                'forum_title' => 'Category',
                'forum_description' => 'The first forum category!',
                'forum_order' => 1,
                'forum_type' => 'category'
            ));

            $queries->create('forums', array(
                'forum_title' => 'Forum',
                'forum_description' => 'The first discussion forum!',
                'forum_order' => 2,
                'parent' => 1,
                'forum_type' => 'forum'
            ));

            // Permissions
            for($i = 0; $i < 4; $i++){
                for($n = 1; $n < 3; $n++){
                    $queries->create('forums_permissions', array(
                        'group_id' => $i,
                        'forum_id' => $n,
                        'view' => 1,
                        'create_topic' => (($i == 0) ? 0 : 1),
                        'create_post' => (($i == 0) ? 0 : 1),
                        'view_other_topics' => 1,
                        'moderate' => (($i == 2 || $i == 3) ? 1 : 0),
                    ));
                }
            }

            // Groups
            $queries->create('groups', array(
                'name' => 'Member',
                'group_html' => '<span class="tag tag-success">Member</span>',
                'group_html_lg' => '<span class="tag tag-success">Member</span>'
            ));

            $queries->create('groups', array(
                'name' => 'Admin',
                'group_html' => '<span class="tag tag-danger">Admin</span>',
                'group_html_lg' => '<span class="tag tag-danger">Admin</span>',
                'group_username_css' => '#ff0000',
                'mod_cp' => 1,
                'admin_cp' => 1
            ));

            $queries->create('groups', array(
                'name' => 'Moderator',
                'group_html' => '<span class="tag tag-primary">Moderator</span>',
                'group_html_lg' => '<span class="tag tag-primary">Moderator</span>',
                'mod_cp' => 1
            ));

            // Languages
            $queries->create('languages', array(
                'name' => 'EnglishUK',
                'is_default' => 1
            ));

            $queries->create('languages', array(
                'name' => 'German',
                'is_default' => 0
            ));

            $queries->create('languages', array(
                'name' => 'EnglishUS',
                'is_default' => 0
            ));

            $cache->setCache('languagecache');
            $cache->store('language', 'EnglishUK');

            // Modules
            $queries->create('modules', array(
                'name' => 'Core',
                'enabled' => 1
            ));

            $queries->create('modules', array(
                'name' => 'Forum',
                'enabled' => 1
            ));

            $cache->setCache('modulescache');
            $cache->store('enabled_modules', array(
                array('name' => 'Core', 'priority' => 1),
                array('name' => 'Forum', 'priority' => 4)
            ));
            $cache->store('module_core', true);
            $cache->store('module_forum', true);

            // Reactions
            $queries->create('reactions', array(
                'name' => 'Like',
                'html' => '<i class="fa fa-thumbs-up text-success"></i>',
                'enabled' => 1,
                'type' => 2
            ));

            $queries->create('reactions', array(
                'name' => 'Dislike',
                'html' => '<i class="fa fa-thumbs-down text-danger"></i>',
                'enabled' => 1,
                'type' => 0
            ));

            $queries->create('reactions', array(
                'name' => 'Meh',
                'html' => '<i class="fa fa-meh-o text-warning"></i>',
                'enabled' => 1,
                'type' => 1
            ));

            // Settings
            $queries->create('settings', array(
                'name' => 'registration_enabled',
                'value' => 1
            ));

            $queries->create('settings', array(
                'name' => 'displaynames',
                'value' => 'false'
            ));

            $queries->create('settings', array(
                'name' => 'uuid_linking',
                'value' => 1
            ));

            $queries->create('settings', array(
                'name' => 'recaptcha',
                'value' => 'false'
            ));

            $queries->create('settings', array(
                'name' => 'recaptcha_key',
                'value' => null
            ));

            $queries->create('settings', array(
                'name' => 'recaptcha_secret',
                'value' => null
            ));

            $queries->create('settings', array(
                'name' => 'email_verification',
                'value' => 1
            ));

            $queries->create('settings', array(
                'name' => 't_and_c',
                'value' => 'By registering on our website, you agree to the following:<p>This website uses "Nameless" website software. The "Nameless" software creators will not be held responsible for any content that may be experienced whilst browsing this site, nor are they responsible for any loss of data which may come about, for example a hacking attempt. The website is run independently from the software creators, and any content is the responsibility of the website administration.</p>'
            ));

            $queries->create('settings', array(
                'name' => 't_and_c_site',
                'value' => '<p>You agree to be bound by our website rules and any laws which may apply to this website and your participation.</p><p>The website administration have the right to terminate your account at any time, delete any content you may have posted, and your IP address and any data you input to the website is recorded to assist the site staff with their moderation duties.</p><p>The site administration have the right to change these terms and conditions, and any site rules, at any point without warning. Whilst you may be informed of any changes, it is your responsibility to check these terms and the rules at any point.</p>'
            ));

            $queries->create('settings', array(
                'name' => 'nameless_version',
                'value' => '2.0.0-pr1'
            ));

            $queries->create('settings', array(
                'name' => 'version_checked',
                'value' => date('U')
            ));

            $queries->create('settings', array(
                'name' => 'version_update',
                'value' => 'false'
            ));

            $queries->create('settings', array(
                'name' => 'phpmailer',
                'value' => 0
            ));

            $queries->create('settings', array(
                'name' => 'phpmailer_type',
                'value' => 'smtp'
            ));

            $queries->create('settings', array(
                'name' => 'verify_accounts',
                'value' => 0
            ));

            $queries->create('settings', array(
                'name' => 'mcassoc_key',
                'value' => null
            ));

            $queries->create('settings', array(
                'name' => 'mcassoc_instance',
                'value' => null
            ));

            $queries->create('settings', array(
                'name' => 'user_avatars',
                'value' => 0
            ));

            $queries->create('settings', array(
                'name' => 'forum_layout',
                'value' => 1
            ));

            $queries->create('settings', array(
                'name' => 'maintenance',
                'value' => 'false'
            ));

            $queries->create('settings', array(
                'name' => 'avatar_site',
                'value' => 'cravatar'
            ));

            $queries->create('settings', array(
                'name' => 'mc_integration',
                'value' => 1
            ));

            $queries->create('settings', array(
                'name' => 'avatar_type',
                'value' => 'helmavatar'
            ));

            $queries->create('settings', array(
                'name' => 'portal',
                'value' => 0
            ));
            $cache->setCache('portal_cache');
            $cache->store('portal', 0);

            $queries->create('settings', array(
                'name' => 'forum_reactions',
                'value' => 1
            ));

            $queries->create('settings', array(
                'name' => 'formatting_type',
                'value' => 'html'
            ));
            $cache->setCache('post_formatting');
            $cache->store('formatting', 'html');

            $queries->create('settings', array(
                'name' => 'youtube_url',
                'value' => null
            ));

            $queries->create('settings', array(
                'name' => 'twitter_url',
                'value' => null
            ));

            $queries->create('settings', array(
                'name' => 'twitter_style',
                'value' => null
            ));

            $queries->create('settings', array(
                'name' => 'gplus_url',
                'value' => null
            ));

            $queries->create('settings', array(
                'name' => 'fb_url',
                'value' => null
            ));

            $queries->create('settings', array(
                'name' => 'ga_script',
                'value' => null
            ));

            $queries->create('settings', array(
                'name' => 'error_reporting',
                'value' => 0
            ));
            $cache->setCache('error_cache');
            $cache->store('error_reporting', 0);

            $queries->create('settings', array(
                'name' => 'page_loading',
                'value' => 0
            ));
            $cache->setCache('page_load_cache');
            $cache->store('page_load', 0);

            $queries->create('settings', array(
                'name' => 'unique_id',
                'value' => substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 62)
            ));

            $queries->create('settings', array(
                'name' => 'use_api',
                'value' => 0
            ));

            $queries->create('settings', array(
                'name' => 'mc_api_key',
                'value' => substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32)
            ));

            $queries->create('settings', array(
                'name' => 'external_query',
                'value' => 0
            ));

            $queries->create('settings', array(
                'name' => 'followers',
                'value' => 0
            ));

            $queries->create('settings', array(
                'name' => 'discord',
                'value' => null
            ));

            $queries->create('settings', array(
                'name' => 'language',
                'value' => 1
            ));
			
            $queries->create('settings', array(
                'name' => 'timezone',
                'value' => 'Europe/London'
            ));
            $cache->setCache('timezone_cache');
            $cache->store('timezone', 'Europe/London');

            // Templates
            $queries->create('templates', array(
                'name' => 'Default',
                'enabled' => 1,
                'is_default' => 1
            ));
            $cache->setCache('templatecache');
            $cache->store('default', 'Default');

            // Success
            Redirect::to('?step=user');
            die();

            break;

        case 'user':
        // Admin user creation
        // Require password hashing methods
        require('core/includes/password.php');

        if(Input::exists()){
            // Validate input
            $validate = new Validate();

            $validation = $validate->check($_POST, array(
                'username' => array(
                    'required' => true,
                    'min' => 3,
                    'max' => 20
                ),
                'email' => array(
                    'required' => true,
                    'min' => 4,
                    'max' => 64,
                    'email' => true
                ),
                'password' => array(
                    'required' => true,
                    'min' => 6,
                    'max' => 64
                ),
                'password_again' => array(
                    'required' => true,
                    'matches' => 'password'
                )
            ));

            if($validation->passed()){
                $user = new User();

                // Hash password
                $password = password_hash(Input::get('password'), PASSWORD_BCRYPT, array("cost" => 13));

                try {
                    // Get user's IP
                    $ip = $user->getIP();

                    // Create the user
                    $user->create(array(
                        'username' => Output::getClean(Input::get('username')),
                        'nickname' => Output::getClean(Input::get('username')),
                        'password' => $password,
                        'pass_method' => 'default',
                        'uuid' => 'none',
                        'joined' => date('U'),
                        'group_id' => 2,
                        'email' => Output::getClean(Input::get('email')),
                        'lastip' => $ip,
                        'active' => 1,
                        'last_online' => date('U'),
                        'theme_id' => 1,
                        'language_id' => 1
                    ));

                    // Log the user in
                    $login = $user->login(Input::get('username'), Input::get('password'), true);

                    if($login){
                        Redirect::to('?step=convert');
                        die();
                    } else {
                        $error = 'Unable to log in.';
                        $queries->delete('users', array('id', '=', 1));
                    }


                } catch(Exception $e){
                    $error = 'Unable to create account: ' . $e->getMessage();
                }

            } else {
                // Get errors
                foreach($validation->errors() as $item){
                    if(strpos($item, 'is required') !== false){
                        $error = 'Please input a valid username, email address and password.';
                    } else if(strpos($item, 'minimum') !== false){
                        $error = 'Please ensure your username is a minimum of 3 characters, your email address is a minimum of 4 characters, and your password is a minimum of 6 characters.';
                    } else if(strpos($item, 'maximum') !== false){
                        $error = 'Please ensure your username is a maximum of 20 characters, and your email address and password are a maximum of 64 characters.';
                    } else if(strpos($item, 'must match') !== false){
                        $error = 'Your passwords must match.';
                    }
                }
            }
        }
        ?>
    </center>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h3>Creating Admin Account</h3>

            <p>Please enter the details for the admin account.</p>

            <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="inputUsername">Username</label>
                    <input type="text" class="form-control" name="username" id="inputUsername" placeholder="Username" tabindex="1">
                </div>

                <div class="form-group">
                    <label for="inputEmail">Email Address</label>
                    <input type="email" class="form-control" name="email" id="inputEmail" placeholder="Email Address" tabindex="2">
                </div>

                <div class="form-group">
                    <label for="inputPassword">Password</label>
                    <input type="password" class="form-control" name="password" id="inputPassword" placeholder="Password" tabindex="3">
                </div>

                <div class="form-group">
                    <label for="inputPasswordAgain">Confirm Password</label>
                    <input type="password" class="form-control" name="password_again" id="inputPasswordAgain" placeholder="Confirm Password" tabindex="4">
                </div>

                <div class="form-group">
                    <input type="submit" value="Submit" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
    <center>
        <?php
        break;

        case 'upgrade':
            // Upgrade from v1
			if(Input::exists()){
				// Ensure all fields are filled
				$validate = new Validate();

				$validation = $validate->check($_POST, array(
					'db_address' => array(
						'required' => true
					),
					'db_port' => array(
						'required' => true
					),
					'db_username' => array(
						'required' => true
					),
					'db_name' => array(
						'required' => true
					)
				));

				if($validation->passed()){
					// Check database connection
					if(isset($_POST['db_password']) && !empty($_POST['db_password'])){
						$password = $_POST['db_password'];
					} else {
						$password = '';
					}

					// Get installation path
					$path = substr(str_replace('\\', '/', substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']))), 1);

					$mysqli = new mysqli(Input::get('db_address'), Input::get('db_username'), $password, Input::get('db_name'), Input::get('db_port'));
					if($mysqli->connect_errno) {
						$error = $mysqli->connect_errno . ' - ' . $mysqli->connect_error;
					} else {
						// Valid
						$_SESSION['db_address'] = $_POST['db_address'];
						$_SESSION['db_port'] = $_POST['db_port'];
						$_SESSION['db_username'] = $_POST['db_username'];
						$_SESSION['db_password'] = $password;
						$_SESSION['db_name'] = $_POST['db_name'];
						
						Redirect::to('?step=do_upgrade');
						die();
					}
				}
			}
            ?>
    </center>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h3>Upgrade</h3>
			<p>Please input the database details for your Nameless version 1 installation</p>
            <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="inputDBAddress">Database Address</label>
                    <input type="text" class="form-control" name="db_address" id="inputDBAddress" placeholder="Database Address">
                </div>

                <div class="form-group">
                    <label for="inputDBPort">Database Port</label>
                    <input type="text" class="form-control" name="db_port" id="inputDBPort" placeholder="Database Port" value="3306">
                </div>

                <div class="form-group">
                    <label for="inputDBUsername">Database Username</label>
                    <input type="text" class="form-control" name="db_username" id="inputDBUsername" placeholder="Database Username">
                </div>

                <div class="form-group">
                    <label for="inputDBPassword">Database Password</label>
                    <input type="password" class="form-control" name="db_password" id="inputDBPassword" placeholder="Database Password">
                </div>

                <div class="form-group">
                    <label for="inputDBName">Database Name</label>
                    <input type="text" class="form-control" name="db_name" id="inputDBName" placeholder="Database Name">
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </form>
        </div>
    </div>
    <center>
			<?php
        break;
		
		case 'do_upgrade':
			// Query old v1 database and insert into v2
			if(!isset($_GET['s']) || (isset($_GET['s']) && $_GET['s'] != '9')) $conn = DB_Custom::getInstance($_SESSION['db_address'], $_SESSION['db_name'], $_SESSION['db_username'], $_SESSION['db_password'], $_SESSION['db_port']);
			echo '<div class="alert alert-info">Please wait whilst the installer upgrades your database...</div>';
			
			$queries = new Queries();
			$cache = new Cache();
			
			if(!isset($_GET['s'])){
				// Alerts -> custom page permissions
				// Alerts
				try {
					$old = $conn->get('nl1_alerts', array('id', '<>', 0));
					if($old->count()){
						$old = $old->results();
						
						foreach($old as $item){
							$queries->create('alerts', array(
								'id' => $item->id,
								'user_id' => $item->user_id,
								'type' => $item->type,
								'url' => $item->url,
								'content' => $item->content,
								'content_short' => ((strlen($item->content) > 64) ? substr($item->content, 0, 64) : $item->content),
								'created' => $item->created,
								'read' => $item->read
							));
						}
					}
				} catch(Exception $e){
					echo '<div class="alert alert-danger">Unable to convert alerts: ' . $e->getMessage() . '</div>';
					$error = true;
				}
				
				// Announcements
				try {
					$old = $conn->get('nl1_announcements', array('id', '<>', 0));
					if($old->count()){
						$old = $old->results();
						
						foreach($old as $item){
							$queries->create('announcements', array(
								'id' => $item->id,
								'content' => $item->content,
								'can_close' => $item->can_close,
								'type' => $item->type
							));
						}
					}
				} catch(Exception $e){
					echo '<div class="alert alert-danger">Unable to convert announcements: ' . $e->getMessage() . '</div>';
					$error = true;
				}
				
				// Announcements pages
				try {
					$old = $conn->get('nl1_announcements_pages', array('id', '<>', 0));
					if($old->count()){
						$old = $old->results();
						
						foreach($old as $item){
							$queries->create('announcements_pages', array(
								'id' => $item->id,
								'announcement_id' => $item->announcement_id,
								'page' => $item->page
							));
						}
					}
				} catch(Exception $e){
					echo '<div class="alert alert-danger">Unable to convert announcement pages: ' . $e->getMessage() . '</div>';
					$error = true;
				}
				
				// Announcements permissions
				try {
					$old = $conn->get('nl1_announcements_permissions', array('id', '<>', 0));
					if($old->count()){
						$old = $old->results();
						
						foreach($old as $item){
							$queries->create('announcements_permissions', array(
								'id' => $item->id,
								'announcement_id' => $item->announcement_id,
								'group_id' => $item->group_id,
								'user_id' => $item->user_id,
								'view' => $item->view
							));
						}
					}
				} catch(Exception $e){
					echo '<div class="alert alert-danger">Unable to convert announcement permissions: ' . $e->getMessage() . '</div>';
					$error = true;
				}
				
				// Custom pages
				try {
					$old = $conn->get('nl1_custom_pages', array('id', '<>', 0));
					if($old->count()){
						$old = $old->results();
						
						foreach($old as $item){
							$queries->create('custom_pages', array(
								'id' => $item->id,
								'url' => $item->url,
								'title' => $item->title,
								'content' => $item->content,
								'link_location' => $item->link_location,
								'redirect' => $item->redirect,
								'link' => $item->link
							));
						}
					}
				} catch(Exception $e){
					echo '<div class="alert alert-danger">Unable to convert custom pages: ' . $e->getMessage() . '</div>';
					$error = true;
				}
				
				// Custom page permissions
				try {
					$old = $conn->get('nl1_custom_pages_permissions', array('id', '<>', 0));
					if($old->count()){
						$old = $old->results();
						
						foreach($old as $item){
							$queries->create('custom_pages_permissions', array(
								'id' => $item->id,
								'page_id' => $item->page_id,
								'group_id' => $item->group_id,
								'view' => $item->view
							));
						}
					}
				} catch(Exception $e){
					echo '<div class="alert alert-danger">Unable to convert custom page permissions: ' . $e->getMessage() . '</div>';
					$error = true;
				}
				
				if(isset($error)){
					echo '<div class="alert alert-warning"><p>Errors have been logged. Click Continue to continue with upgrade.</p><a href="?step=do_upgrade&amp;s=1" class="btn btn-secondary">Continue</a></div>';
				} else {
					Redirect::to('?step=do_upgrade&s=1');
					die();
				}
			
			} else {
				switch($_GET['s']){
					case '1':
						// Forums -> groups
						// Forums
						try {
							$old = $conn->get('nl1_forums', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('forums', array(
										'id' => $item->id,
										'forum_title' => $item->forum_title,
										'forum_description' => $item->forum_description,
										'forum_type' => $item->forum_type,
										'last_post_date' => strtotime($item->last_post_date),
										'last_user_posted' => $item->last_user_posted,
										'last_topic_posted' => $item->last_topic_posted,
										'parent' => $item->parent,
										'forum_order' => $item->forum_order,
										'news' => $item->news
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert forums: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Forum permissions
						try {
							$old = $conn->get('nl1_forums_permissions', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('forums_permissions', array(
										'id' => $item->id,
										'group_id' => $item->group_id,
										'forum_id' => $item->forum_id,
										'view' => $item->view,
										'create_topic' => $item->create_topic,
										'create_post' => $item->create_post,
										'view_other_topics' => 1
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert forum permissions: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Forum topic labels
						try {
							$old = $conn->get('nl1_forums_topic_labels', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('forums_topic_labels', array(
										'id' => $item->id,
										'fids' => $item->fids,
										'name' => $item->name,
										'label' => $item->label
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert forum topic labels: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Friends/followers
						try {
							$old = $conn->get('nl1_friends', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('friends', array(
										'id' => $item->id,
										'user_id' => $item->user_id,
										'friend_id' => $item->friend_id
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert friends: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Groups
						try {
							$old = $conn->get('nl1_groups', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('groups', array(
										'id' => $item->id,
										'name' => $item->name,
										'group_html' => $item->group_html,
										'group_html_lg' => $item->group_html_lg,
										'mod_cp' => $item->mod_cp,
										'admin_cp' => $item->staff,
										'staff_apps' => $item->staff_apps,
										'accept_staff_apps' => $item->accept_staff_apps
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert groups: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						if(isset($error)){
							echo '<div class="alert alert-warning"><p>Errors have been logged. Click Continue to continue with upgrade.</p><a href="?step=do_upgrade&amp;s=2" class="btn btn-secondary">Continue</a></div>';
						} else {
							Redirect::to('?step=do_upgrade&s=2');
							die();
						}
						
					break;
					
					case '2':
						// Infractions -> posts
						// Infractions
						try {
							$old = $conn->get('nl1_infractions', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('infractions', array(
										'id' => $item->id,
										'type' => $item->type,
										'punished' => $item->punished,
										'staff' => $item->staff,
										'reason' => $item->reason,
										'infraction_date' => $item->infraction_date,
										'acknowledged' => $item->acknowledged
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert site punishments: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Minecraft servers
						try {
							$old = $conn->get('nl1_mc_servers', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('mc_servers', array(
										'id' => $item->id,
										'ip' => $item->ip,
										'query_ip' => $item->query_ip,
										'name' => $item->name,
										'is_default' => $item->is_default,
										'display' => $item->display,
										'pre' => $item->pre,
										'player_list' => $item->player_list
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert Minecraft servers: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Posts
						try {
							$old = $conn->get('nl1_posts', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('posts', array(
										'id' => $item->id,
										'forum_id' => $item->forum_id,
										'topic_id' => $item->topic_id,
										'post_creator' => $item->post_creator,
										'post_content' => $item->post_content,
										'post_date' => $item->post_date,
										'deleted' => $item->deleted
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert posts: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						if(isset($error)){
							echo '<div class="alert alert-warning"><p>Errors have been logged. Click Continue to continue with upgrade.</p><a href="?step=do_upgrade&amp;s=3" class="btn btn-secondary">Continue</a></div>';
						} else {
							Redirect::to('?step=do_upgrade&s=3');
							die();
						}
						
					break;
					
					case '3':
						// Private messages -> private message users
						// Private messages
						try {
							$old = $conn->get('nl1_private_messages', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('private_messages', array(
										'id' => $item->id,
										'author_id' => $item->author_id,
										'title' => $item->title,
										'created' => 0, // will update later
										'last_reply_user' => $item->author_id, // will update later
										'last_reply_date' => $item->updated
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert private messages: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Private message replies
						$private_messages = array();
						try {
							$old = $conn->get('nl1_private_messages_replies', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									if(!isset($private_messages[$item->pm_id])){
										$private_messages[$item->pm_id] = array(
											'created' => $item->created,
											'updated' => $item->created,
											'last_reply_user' => $item->user_id
										);
									} else {
										if($private_messages[$item->pm_id]['created'] > $item->created)
											$private_messages[$item->pm_id]['created'] = $item->created;
										
										else if($private_messages[$item->pm_id]['updated'] < $item->created){
											$private_messages[$item->pm_id]['updated'] = $item->created;
											$private_messages[$item->pm_id]['last_reply_user'] = $item->user_id;
										}
									}
									
									$queries->create('private_messages_replies', array(
										'id' => $item->id,
										'pm_id' => $item->pm_id,
										'author_id' => $item->user_id,
										'created' => $item->created,
										'content' => $item->content
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert private message replies: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Private message users
						try {
							$old = $conn->get('nl1_private_messages_users', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('private_messages_users', array(
										'id' => $item->id,
										'pm_id' => $item->pm_id,
										'user_id' => $item->user_id,
										'read' => $item->read
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert private message users: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Update private message columns
						foreach($private_messages as $key => $message){
							try {
								$queries->update('private_messages', $key, array(
									'created' => $message['created'],
									'last_reply_user' => $message['last_reply_user']
								));
							} catch(Exception $e){
								echo '<div class="alert alert-danger">Unable to update private message columns: ' . $e->getMessage() . '</div>';
								$error = true;
							}
						}
						
						if(isset($error)){
							echo '<div class="alert alert-warning"><p>Errors have been logged. Click Continue to continue with upgrade.</p><a href="?step=do_upgrade&amp;s=4" class="btn btn-secondary">Continue</a></div>';
						} else {
							Redirect::to('?step=do_upgrade&s=4');
							die();
						}
						
					break;
					
					case '4':
						// Query errors -> settings
						// Query errors
						try {
							$old = $conn->get('nl1_query_errors', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('query_errors', array(
										'id' => $item->id,
										'date' => $item->date,
										'error' => $item->error,
										'ip' => $item->ip,
										'port' => $item->port
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert query errors: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Reports
						try {
							$old = $conn->get('nl1_reports', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('reports', array(
										'id' => $item->id,
										'type' => $item->type,
										'reporter_id' => $item->reporter_id,
										'reported_id' => $item->reported_id,
										'status' => $item->status,
										'date_reported' => $item->date_reported,
										'date_updated' => $item->date_updated,
										'report_reason' => $item->report_reason,
										'updated_by' => $item->updated_by,
										'reported_post' => $item->reported_post,
										'reported_mcname' => $item->reported_mcname,
										'reported_uuid' => $item->reported_uuid
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert reports: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Report comments
						try {
							$old = $conn->get('nl1_reports_comments', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('reports_comments', array(
										'id' => $item->id,
										'report_id' => $item->report_id,
										'commenter_id' => $item->commenter_id,
										'comment_date' => $item->comment_date,
										'comment_content' => $item->comment_content
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert report comments: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Reputation
						try {
							$old = $conn->get('nl1_reputation', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('forums_reactions', array(
										'id' => $item->id,
										'post_id' => $item->post_id,
										'user_received' => $item->user_received,
										'user_given' => $item->user_given,
										'reaction_id' => 1,
										'time' => strtotime($item->time_given)
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert reputation: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Settings
						try {
							$old = $conn->get('nl1_settings', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('settings', array(
										'id' => $item->id,
										'name' => $item->name,
										'value' => $item->value
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert settings: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						if(isset($error)){
							echo '<div class="alert alert-warning"><p>Errors have been logged. Click Continue to continue with upgrade.</p><a href="?step=do_upgrade&amp;s=5" class="btn btn-secondary">Continue</a></div>';
						} else {
							Redirect::to('?step=do_upgrade&s=5');
							die();
						}
						
					break;
					
					case '5':
						// Topics -> users
						try {
							$old = $conn->get('nl1_topics', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('topics', array(
										'id' => $item->id,
										'forum_id' => $item->forum_id,
										'topic_title' => $item->topic_title,
										'topic_creator' => $item->topic_creator,
										'topic_last_user' => $item->topic_last_user,
										'topic_date' => $item->topic_date,
										'topic_reply_date' => $item->topic_reply_date,
										'topic_views' => $item->topic_views,
										'locked' => $item->locked,
										'sticky' => $item->sticky,
										'label' => $item->label
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert topics: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Users
						try {
							$old = $conn->get('nl1_users', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('users', array(
										'id' => $item->id,
										'username' => $item->mcname,
										'nickname' => $item->username,
										'password' => $item->password,
										'pass_method' => $item->pass_method,
										'uuid' => $item->uuid,
										'joined' => $item->joined,
										'group_id' => $item->group_id,
										'email' => $item->email,
										'isbanned' => $item->isbanned,
										'lastip' => (is_null($item->lastip) ? 'none' : $item->lastip),
										'active' => $item->active,
										'signature' => $item->signature,
										'reputation' => $item->reputation,
										'reset_code' => $item->reset_code,
										'has_avatar' => $item->has_avatar,
										'gravatar' => $item->gravatar,
										'last_online' => $item->last_online,
										'last_username_update' => $item->last_username_update,
										'user_title' => $item->user_title,
										'tfa_enabled' => $item->tfa_enabled,
										'tfa_type' => $item->tfa_type,
										'tfa_secret' => $item->tfa_secret,
										'tfa_complete' => $item->tfa_complete
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert users: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						if(isset($error)){
							echo '<div class="alert alert-warning"><p>Errors have been logged. Click Continue to continue with upgrade.</p><a href="?step=do_upgrade&amp;s=6" class="btn btn-secondary">Continue</a></div>';
						} else {
							Redirect::to('?step=do_upgrade&s=6');
							die();
						}
						
					break;
					
					case '6':
						// User admin session -> user profile wall replies
						// User admin sessions
						try {
							$old = $conn->get('nl1_users_admin_session', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('users_admin_session', array(
										'id' => $item->id,
										'user_id' => $item->user_id,
										'hash' => $item->hash
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert user admin sessions: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// User sessions
						try {
							$old = $conn->get('nl1_users_session', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('users_session', array(
										'id' => $item->id,
										'user_id' => $item->user_id,
										'hash' => $item->hash
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert user sessions: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Username history
						try {
							$old = $conn->get('nl1_users_username_history', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('users_username_history', array(
										'id' => $item->id,
										'user_id' => $item->user_id,
										'changed_to' => $item->changed_to,
										'changed_at' => $item->changed_at,
										'original' => $item->original
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert username history: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Profile wall posts
						try {
							$old = $conn->get('nl1_user_profile_wall_posts', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('user_profile_wall_posts', array(
										'id' => $item->id,
										'user_id' => $item->user_id,
										'author_id' => $item->author_id,
										'time' => $item->time,
										'content' => $item->content
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert user profile wall posts: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Profile wall likes
						try {
							$old = $conn->get('nl1_user_profile_wall_posts_likes', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('user_profile_wall_posts_reactions', array(
										'id' => $item->id,
										'user_id' => $item->user_id,
										'post_id' => $item->post_id,
										'reaction_id' => 1,
										'time' => 0
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert user profile wall likes: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						// Profile wall replies
						try {
							$old = $conn->get('nl1_user_profile_wall_posts_replies', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('user_profile_wall_posts_replies', array(
										'id' => $item->id,
										'post_id' => $item->post_id,
										'author_id' => $item->author_id,
										'time' => $item->time,
										'content' => $item->content
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert user profile wall replies: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						if(isset($error)){
							echo '<div class="alert alert-warning"><p>Errors have been logged. Click Continue to continue with upgrade.</p><a href="?step=do_upgrade&amp;s=7" class="btn btn-secondary">Continue</a></div>';
						} else {
							Redirect::to('?step=do_upgrade&s=7');
							die();
						}
						
					break;
					
					case '7':
						// UUID cache
						try {
							$old = $conn->get('nl1_uuid_cache', array('id', '<>', 0));
							if($old->count()){
								$old = $old->results();
								
								foreach($old as $item){
									$queries->create('uuid_cache', array(
										'id' => $item->id,
										'mcname' => $item->mcname,
										'uuid' => $item->uuid
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert UUID cache: ' . $e->getMessage() . '</div>';
							$error = true;
						}
						
						if(isset($error)){
							echo '<div class="alert alert-warning"><p>Errors have been logged. Click Continue to continue with upgrade.</p><a href="?step=do_upgrade&amp;s=8" class="btn btn-secondary">Continue</a></div>';
						} else {
							Redirect::to('?step=do_upgrade&s=8');
							die();
						}
						
					break;
					
					case '8':
						// New settings/initialise cache
						// Site name
						$sitename = $queries->getWhere('settings', array('name', '=', 'sitename'));
						if(!count($sitename)){
							$cache->setCache('sitenamecache');
							$cache->store('sitename', 'NamelessMC');
						} else {
							$cache->setCache('sitenamecache');
							$cache->store('sitename', Output::getClean($sitename[0]->value));
						}
						
						// Languages
						$queries->create('languages', array(
							'name' => 'EnglishUK',
							'is_default' => 1
						));
						$queries->create('languages', array(
							'name' => 'German',
							'is_default' => 0
						));
						$queries->create('languages', array(
							'name' => 'EnglishUS',
							'is_default' => 0
						));
						$cache->setCache('languagecache');
						$cache->store('language', 'EnglishUK');

						// Modules
						$queries->create('modules', array(
							'name' => 'Core',
							'enabled' => 1
						));
						$queries->create('modules', array(
							'name' => 'Forum',
							'enabled' => 1
						));
						$cache->setCache('modulescache');
						$cache->store('enabled_modules', array(
							array('name' => 'Core', 'priority' => 1),
							array('name' => 'Forum', 'priority' => 4)
						));
						$cache->store('module_core', true);
						$cache->store('module_forum', true);

						// Reactions
						$queries->create('reactions', array(
							'name' => 'Like',
							'html' => '<i class="fa fa-thumbs-up text-success"></i>',
							'enabled' => 1,
							'type' => 2
						));
						$queries->create('reactions', array(
							'name' => 'Dislike',
							'html' => '<i class="fa fa-thumbs-down text-danger"></i>',
							'enabled' => 1,
							'type' => 0
						));
						$queries->create('reactions', array(
							'name' => 'Meh',
							'html' => '<i class="fa fa-meh-o text-warning"></i>',
							'enabled' => 1,
							'type' => 1
						));

						// Settings
						$queries->create('settings', array(
							'name' => 'registration_enabled',
							'value' => 1
						));
						
						$version = $queries->getWhere('settings', array('name', '=', 'version'));
						if(count($version)){
							$queries->update('settings', $version[0]->id, array(
								'name' => 'nameless_version',
								'value' => '2.0.0-pr1'
							));
						} else {
							$queries->create('settings', array(
								'name' => 'nameless_version',
								'value' => '2.0.0-pr1'
							));
						}
						
						$version_update = $queries->getWhere('settings', array('name', '=', 'version_update'));
						if(count($version_update)){
							$queries->update('settings', $version_update[0]->id, array(
								'value' => 'false'
							));
						} else {
							$queries->create('settings', array(
								'name' => 'version_update',
								'value' => 'false'
							));
						}
						
						$mcassoc = $queries->getWhere('settings', array('name', '=', 'use_mcassoc'));
						if(count($mcassoc)){
							$queries->update('settings', $mcassoc[0]->id, array(
								'name' => 'verify_accounts'
							));
						} else {
							$queries->create('settings', array(
								'name' => 'verify_accounts',
								'value' => 0
							));
						}
						
						$avatar_site = $queries->getWhere('settings', array('name', '=', 'avatar_api'));
						if(count($avatar_site)){
							$queries->update('settings', $avatar_site[0]->id, array(
								'name' => 'avatar_site'
							));
						} else {
							$queries->create('settings', array(
								'name' => 'avatar_site',
								'value' => 'cravatar'
							));
						}
						
						$queries->create('settings', array(
							'name' => 'mc_integration',
							'value' => 1
						));
						
						$queries->create('settings', array(
							'name' => 'portal',
							'value' => 0
						));
						$cache->setCache('portal_cache');
						$cache->store('portal', 0);
						
						$queries->create('settings', array(
							'name' => 'forum_reactions',
							'value' => 1
						));
						
						$queries->create('settings', array(
							'name' => 'formatting_type',
							'value' => 'html'
						));
						$cache->setCache('post_formatting');
						$cache->store('formatting', 'html');

						$error_reporting = $queries->getWhere('settings', array('name', '=', 'error_reporting'));
						if(count($error_reporting)){
							$cache->setCache('error_cache');
							$cache->store('error_reporting', $error_reporting[0]->value);
						} else {
							$queries->create('settings', array(
								'name' => 'error_reporting',
								'value' => 0
							));
							$cache->setCache('error_cache');
							$cache->store('error_reporting', 0);
						}
						
						$queries->create('settings', array(
							'name' => 'page_loading',
							'value' => 0
						));
						$cache->setCache('page_load_cache');
						$cache->store('page_load', 0);
						
						$use_plugin = $queries->getWhere('settings', array('name', '=', 'use_plugin'));
						if(count($use_plugin)){
							$queries->update('settings', $use_plugin[0]->id, array(
								'name' => 'use_api'
							));
						} else {
							$queries->create('settings', array(
								'name' => 'use_api',
								'value' => 0
							));
						}
						
						$queries->create('settings', array(
							'name' => 'timezone',
							'value' => 'Europe/London'
						));
						$cache->setCache('timezone_cache');
						$cache->store('timezone', 'Europe/London');
						
						// Templates
						$queries->create('templates', array(
							'name' => 'Default',
							'enabled' => 1,
							'is_default' => 1
						));
						$cache->setCache('templatecache');
						$cache->store('default', 'Default');
						
						unset($_SESSION['db_address']);
						unset($_SESSION['db_port']);
						unset($_SESSION['db_username']);
						unset($_SESSION['db_password']);
						unset($_SESSION['db_name']);
						
						Redirect::to('?step=do_upgrade&s=9');
						die();
						
					break;
					
					case '9':
						// Complete
						?>
    </center>
    <div class="row">
        <div class="col-md-6 offset-md-3">
		  <div class="alert alert-success"><p>Upgrade complete!</p><center><a href="?step=finish" class="btn btn-primary">Continue</a></center></div>
		</div>
	</div>
	<center>
						<?php
					break;
				}
			}
		break;

        case 'convert':
            // Convert from a different forum software
            if(!isset($_GET['convert'])){
                ?>

                <h3>Convert</h3>
                <p>Finally, do you want to convert from a different forum software?</p>
                <a class="btn btn-success btn-lg disabled" href="?step=convert&amp;convert=yes">Yes</a>
                <a class="btn btn-primary btn-lg" href="?step=finish">No</a>

                <?php
            } else {
				// Display list of converters
				
				
            }
            break;

        case 'credits':
            // Credits - TODO
            ?>
			<h3>Credits</h3>
            <p>A huge thanks to all <a href="https://github.com/NamelessMC/Nameless#full-contributor-list" target="_blank">NamelessMC contributors</a> since 2014</p>
            <a class="btn btn-primary btn-lg" href="?step=finish">Continue</a>
            <?php

            break;
        case 'finish':
            // Finished
            ?>
            <h3>Finish</h3>
            <p>Thanks for installing NamelessMC! You can now proceed to the AdminCP, where you can further configure your website.</p>
            <p>If you need any support, check out our website <a href="https://namelessmc.com" target="_blank">here</a>, or you can also visit our <a href="https://discord.gg/r7Eq4jw" target="_blank">Discord server</a> or our <a href="https://github.com/NamelessMC/Nameless/" target="_blank">GitHub repository</a>.
            <p><a href="index.php?route=/admin&amp;from=install" class="btn btn-success btn-lg">Finish</a></p>
			<hr />
			<h3>Credits</h3>
			<p>A huge thanks to all <a href="https://github.com/NamelessMC/Nameless#full-contributor-list" target="_blank">NamelessMC contributors</a> since 2014</p>
            <?php
            break;
        default:
            die('Unknown step');
            break;
        }
        }
        }
        ?>
        <hr />

        <div class="container">
		  <span class="pull-right">
		    <a class="btn btn-primary" href="https://github.com/NamelessMC/Nameless" target="_blank"><i class="fa fa-github" aria-hidden="true"></i></a>
		  </span>
        </div>
    </center>
</div>
</body>
</html>
