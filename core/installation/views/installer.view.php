<html lang="<?php echo $language_html; ?>">
<head>
    <!-- Page Title -->
    <title><?php echo $language['install']; ?> &bull; NamelessMC</title>

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

<body style="background-color:#eceeef;">
    <div style="text-align:center">
        <br /><br /><br />
        <h1>NamelessMC v2 <sup><span style="font-size: small;"><?php echo $language['pre-release']; ?></span></sup></h1>

        <hr />

        <?php
        if(!isset($_GET['do']) && !isset($_GET['step'])){
            ?>
            <p><?php echo $language['installer_welcome']; ?></p>

            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="alert alert-danger">
                        <?php echo $language['pre-release_warning']; ?>
                    </div>
                </div>
            </div>

            <p><?php echo $language['installer_information']; ?></p>

            <p><?php echo $language['new_installation_question']; ?></p>

            <a href="?do=install" class="btn btn-primary btn-lg">
                <?php echo $language['new_installation']; ?>
            </a>

            <a href="?do=upgrade" class="btn btn-warning btn-lg">
                <?php echo $language['upgrading_from_v1']; ?>
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
            <h4><?php echo $language['requirements']; ?></h4>

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
	        if(!extension_loaded('mbstring')){
		        echo 'PHP mbstring Extension - ' . $error;
		        $php_error = true;
	        } else {
		        echo 'PHP mbstring Extension - ' . $success;
	        }
            if(!extension_loaded('PDO')){
                echo 'PHP PDO Extension - ' . $error;
                $php_error = true;
            } else {
                echo 'PHP PDO Extension - ' . $success;
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
            if(!function_exists('exif_imagetype')){
                echo 'PHP exif_imagetype Function - ' . $error;
                $php_error = true;
            } else {
                echo 'PHP exif_imagetype Function - ' . $success;
            }
            if(!extension_loaded('mysql') && !extension_loaded('mysqlnd')){
                echo 'PHP MySQL Extension - ' . $error;
                $php_error = true;
            } else {
                echo 'PHP MySQL Extension - ' . $success;
            }

            // Permissions
            if(is_writable('core/config.php')){
                echo $language['config_writable'] . ' - ' . $success;
            } else {
                echo $language['config_writable'] . ' - ' . $error;
                $php_error = true;
            }

            if(is_writable('cache')){
                echo $language['cache_writable'] . ' - ' . $success;
            } else {
                echo $language['cache_writable'] . ' - ' . $error;
                $php_error = true;
            }

            if(is_writable('cache/templates_c')){
                echo $language['template_cache_writable'] . ' - ' . $success;
            } else {
                echo $language['template_cache_writable'] . ' - ' . $error;
                $php_error = true;
            }

            if(isset($php_error)){
                ?>
                <br />
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="alert alert-danger">
                            <?php echo $language['requirements_error']; ?>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                echo '<br />';
                if(isset($exif_error))
                  echo '<div class="alert alert-warning" style="display: inline-block;">' . $language['exif_imagetype_banners_disabled'] . '</div><br /><br />';
                echo '<a class="btn btn-primary btn-lg" href="?step=database">' . $language['proceed'] . ' &raquo;</a>';
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
                    $password = str_replace('\'', '\\\'', $_POST['db_password']);
                } else {
                    $password = '';
                }

				// Get charset
				if($_POST['charset'] == 'latin1'){
					$charset = 'latin1';
				} else $charset = 'utf8mb4';
				
				// Get DB engine
				if($_POST['engine'] == 'MyISAM'){
					$engine = 'MyISAM';
				} else $engine = 'InnoDB';

                $mysqli = new mysqli(Input::get('db_address'), Input::get('db_username'), $password, Input::get('db_name'), Input::get('db_port'));
                if($mysqli->connect_errno) {
                    $error = $mysqli->connect_errno . ' - ' . $mysqli->connect_error;
                } else {

                    if(isset($_POST['install_path']))
                        $install_path = $_POST['install_path'];

                    if(isset($_POST['hostname']))
                        $server_name = $_POST['hostname'];
                    else
                        $server_name = $_SERVER['SERVER_NAME'];

                    try {
						$insert = 	'<?php' . PHP_EOL . 
									'$conf = array(' . PHP_EOL . 
									'	"mysql" => array(' . PHP_EOL . 
									'		"host" => "' . Input::get('db_address') . '", // Web server database IP (Likely to be 127.0.0.1)' . PHP_EOL . 
									'		"username" => "' . Input::get('db_username') . '", // Web server database username' . PHP_EOL . 
									'		"password" => \'' . $password . '\', // Web server database password' . PHP_EOL . 
									'		"db" => "' . Input::get('db_name') . '", // Web server database name' . PHP_EOL .
									'		"port" => "' . Input::get('db_port') . '", // Web server database port' . PHP_EOL .
									'		"prefix" => "nl2_", // Web server table prefix' . PHP_EOL .
									'		"charset" => "' . $charset . '", // MySQL charset for new tables' . PHP_EOL .
									'		"engine" => "' . $engine . '" // MySQL engine for new tables' . PHP_EOL .
									'	),' . PHP_EOL . 
									'	"remember" => array(' . PHP_EOL . 
									'		"cookie_name" => "nl2", // Name for website cookies' . PHP_EOL . 
									'		"cookie_expiry" => 604800' . PHP_EOL . 
									'	),' . PHP_EOL . 
									'	"session" => array(' . PHP_EOL . 
									'		"session_name" => "2user",' . PHP_EOL . 
									'		"admin_name" => "2admin",' . PHP_EOL .
									'		"token_name" => "2token"' . PHP_EOL . 
									'	),' . PHP_EOL . 
									'	"core" => array(' . PHP_EOL . 
									'		"path" => "' . $install_path . '",' . PHP_EOL .
									'		"hostname" => "' . $server_name . '",' . PHP_EOL .
									'		"friendly" => ' . ((isset($_POST['friendly']) && $_POST['friendly'] == 'true') ? 'true' : 'false') . PHP_EOL .
									'	),' . PHP_EOL .
									'	"allowedProxies" => ""' . PHP_EOL .
									');' . PHP_EOL;
									

						if(is_writable('core/config.php')){
							$file = fopen('core/config.php','w');
							fwrite($file, $insert);
							fclose($file);
						} else {
							die('Config not writable');
						}

						$_SESSION['charset'] = $charset;
						$_SESSION['engine'] = $engine;

                        Redirect::to('?step=database_initialise');
                        die();

                    } catch(Exception $e){
                        $error = $e->getMessage();
                    }
            }

            } else {
                $error = $language['database_error'];
            }
        }
        ?>
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h3><?php echo $language['database_configuration']; ?></h3>

            <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="inputDBAddress"><?php echo $language['database_address']; ?></label>
                    <input type="text" class="form-control" name="db_address" id="inputDBAddress" value="127.0.0.1" placeholder="<?php echo $language['database_address']; ?>">
                </div>

                <div class="form-group">
                    <label for="inputDBPort"><?php echo $language['database_port']; ?></label>
                    <input type="text" class="form-control" name="db_port" id="inputDBPort" placeholder="<?php echo $language['database_port']; ?>" value="3306">
                </div>

                <div class="form-group">
                    <label for="inputDBUsername"><?php echo $language['database_username']; ?></label>
                    <input type="text" class="form-control" name="db_username" id="inputDBUsername" placeholder="<?php echo $language['database_username']; ?>">
                </div>

                <div class="form-group">
                    <label for="inputDBPassword"><?php echo $language['database_password']; ?></label>
                    <input type="password" class="form-control" name="db_password" id="inputDBPassword" placeholder="<?php echo $language['database_password']; ?>">
                </div>

                <div class="form-group">
                    <label for="inputDBName"><?php echo $language['database_name']; ?></label>
                    <input type="text" class="form-control" name="db_name" id="inputDBName" placeholder="<?php echo $language['database_name']; ?>">
                </div>

                <div class="form-group">
                    <label for="inputHostname"><?php echo $language['host']; ?></label> <span class="badge badge-info" data-toggle="popover" data-placement="top" data-content="<?php echo $language['host_help']; ?>"><i class="fa fa-question"></i></span>
                    <input type="text" class="form-control" name="hostname" id="inputHostname" value="<?php echo Output::getClean($_SERVER['SERVER_NAME']); ?>" placeholder="<?php echo $language['host']; ?>">
                </div>

                <div class="form-group">
                    <label for="inputPath"><?php echo $language['nameless_path']; ?></label> <span class="badge badge-info" data-toggle="popover" data-placement="top" data-content="<?php echo $language['nameless_path_info']; ?>"><i class="fa fa-question"></i></span>
                    <input type="text" class="form-control" name="install_path" id="inputPath" value="<?php echo Output::getClean($install_path); ?>" placeholder="<?php echo $language['nameless_path']; ?>">
                </div>
                
                <div class="form-group">
                    <label for="inputFriendly"><?php echo $language['friendly_urls']; ?></label> <span class="badge badge-info" data-toggle="popover" data-placement="top" data-content="<?php echo $language['friendly_urls_info']; ?>"><i class="fa fa-question"></i></span>
				    <select class="form-control" name="friendly" id="inputFriendly">
					    <option value="true"><?php echo $language['enabled']; ?></option>
					    <option value="false" selected><?php echo $language['disabled']; ?></option>
					</select>
                </div>

				<div class="form-group">
				    <label for="inputCharset"><?php echo $language['character_set']; ?></label>
				    <select class="form-control" name="charset" id="inputCharset">
					    <option value="latin1">latin1</option>
					    <option value="utf8mb4" selected>Unicode</option>
					</select>
				</div>
				
				<div class="form-group">
				    <label for="inputEngine"><?php echo $language['database_engine']; ?></label>
				    <select class="form-control" name="engine" id="inputEngine">
					    <option value="InnoDB" selected>InnoDB</option>
						<option value="MyISAM">MyISAM</option>
					</select>
				</div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="<?php echo $language['submit']; ?>">
                </div>
            </form>
        </div>
    </div>
    <div style="text-align:center">
        <?php
        break;

        case 'database_initialise':
            // Initialise database tables
            ?>
            <p><?php echo $language['installer_now_initialising_database']; ?></p>
            <?php
            try {
				if(isset($_SESSION['charset'])) $charset = $_SESSION['charset'];
				else $charset = 'utf8mb4';
				
				if(isset($_SESSION['engine'])) $engine = $_SESSION['engine'];
				else $engine = 'InnoDB';

                $queries = new Queries();
                $queries->dbInitialise($charset, $engine);
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
                $error = $language['configuration_error'];
            }

        }

        ?>
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form action="" method="post">
                <h3><?php echo $language['configuration']; ?></h3>
                <p><?php echo $language['configuration_info']; ?></p>

                <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>

                <div class="form-group">
                    <label for="inputSitename"><?php echo $language['site_name']; ?></label>
                    <input type="text" class="form-control" name="sitename" id="inputSitename" placeholder="<?php echo $language['site_name']; ?>">
                </div>

                <div class="form-group">
                    <label for="inputContactEmail"><?php echo $language['contact_email']; ?></label>
                    <input type="email" class="form-control" name="incoming" id="inputContactEmail" placeholder="<?php echo $language['contact_email']; ?>">
                </div>

                <div class="form-group">
                    <label for="inputOutgoingEmail"><?php echo $language['outgoing_email']; ?></label>
                    <input type="email" class="form-control" name="outgoing" id="inputOutgoingEmail" placeholder="<?php echo $language['outgoing_email']; ?>">
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="<?php echo $language['submit']; ?>">
                </div>
            </form>
        </div>
    </div>
    <div style="text-align:center">
        <?php
        break;

        case 'initialise':
            // Initialise database and cache
            echo $language['initialising_database_and_cache'];

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
                        'create_topic' => (($i == 0 || $i == 4) ? 0 : 1),
                        'create_post' => (($i == 0 || $i == 4) ? 0 : 1),
                        'view_other_topics' => 1,
                        'moderate' => (($i == 2 || $i == 3) ? 1 : 0),
                    ));
                }
            }

            // Forum Labels
            $queries->create('forums_labels', array(
                'name' => 'Default',
                'html' => '<span class="badge badge-default">{x}</span>'
            ));

            $queries->create('forums_labels', array(
                'name' => 'Primary',
                'html' => '<span class="badge badge-primary">{x}</span>'
            ));

            $queries->create('forums_labels', array(
                'name' => 'Success',
                'html' => '<span class="badge badge-success">{x}</span>'
            ));

            $queries->create('forums_labels', array(
                'name' => 'Info',
                'html' => '<span class="badge badge-info">{x}</span>'
            ));

            $queries->create('forums_labels', array(
                'name' => 'Warning',
                'html' => '<span class="badge badge-warning">{x}</span>'
            ));

            $queries->create('forums_labels', array(
                'name' => 'Danger',
                'html' => '<span class="badge badge-danger">{x}</span>'
            ));

            // Groups
            $queries->create('groups', array(
                'name' => 'Member',
                'group_html' => '<span class="badge badge-success">Member</span>',
                'group_html_lg' => '<span class="badge badge-success">Member</span>',
                'permissions' => '{"usercp.messaging":1,"usercp.signature":1,"usercp.nickname":1,"usercp.private_profile":1,"usercp.profile_banner":1}',
                'default_group' => 1,
				'order' => 3
            ));

            $queries->create('groups', array(
                'name' => 'Admin',
                'group_html' => '<span class="badge badge-danger">Admin</span>',
                'group_html_lg' => '<span class="badge badge-danger">Admin</span>',
                'group_username_css' => '#ff0000',
                'mod_cp' => 1,
                'admin_cp' => 1,
                'permissions' => '{"admincp.core":1,"admincp.core.api":1,"admincp.core.general":1,"admincp.core.avatars":1,"admincp.core.fields":1,"admincp.core.debugging":1,"admincp.core.emails":1,"admincp.core.navigation":1,"admincp.core.reactions":1,"admincp.core.registration":1,"admincp.core.social_media":1,"admincp.core.terms":1,"admincp.errors":1,"admincp.integrations":1,"admincp.minecraft":1,"admincp.minecraft.authme":1,"admincp.minecraft.verification":1,"admincp.minecraft.servers":1,"admincp.minecraft.query_errors":1,"admincp.minecraft.banners":1,"admincp.modules":1,"admincp.pages":1,"admincp.pages.metadata":1,"admincp.security":1,"admincp.security.acp_logins":1,"admincp.security.template":1,"admincp.sitemap":1,"admincp.styles":1,"admincp.styles.panel_templates":1,"admincp.styles.templates":1,"admincp.styles.templates.edit":1,"admincp.styles.images":1,"admincp.update":1,"admincp.users":1,"admincp.users.edit":1,"admincp.groups":1,"admincp.groups.self":1,"admincp.widgets":1,"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"admincp.forums":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1, "admincp.security.all":1}',
				'order' => 1,
				'staff' => 1
            ));

            $queries->create('groups', array(
                'name' => 'Moderator',
                'group_html' => '<span class="badge badge-primary">Moderator</span>',
                'group_html_lg' => '<span class="badge badge-primary">Moderator</span>',
                'admin_cp' => 1,
                'permissions' => '{"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"admincp.users":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1}',
				'order' => 2,
				'staff' => 1
            ));

            $queries->create('groups', array(
                'name' => 'Unconfirmed Member',
                'group_html' => '<span class="badge badge-secondary">Unconfirmed Member</span>',
                'group_html_lg' => '<span class="badge badge-secondary">Unconfirmed Member</span>',
                'group_username_css' => '#6c757d',
				'order' => 4
            ));

            // Languages
            $queries->create('languages', array(
                'name' => 'EnglishUK',
                'is_default' => 1
            ));

            $queries->create('languages', array(
                'name' => 'Chinese',
                'is_default' => 0
            ));

            $queries->create('languages', array(
                'name' => 'Czech',
                'is_default' => 0
            ));

            $queries->create('languages', array(
                'name' => 'EnglishUS',
                'is_default' => 0
            ));

            $queries->create('languages', array(
                'name' => 'Dutch',
                'is_default' => 0
            ));

            $queries->create('languages', array(
                'name' => 'German',
                'is_default' => 0
            ));

            $queries->create('languages', array(
                'name' => 'Greek',
                'is_default' => 0
            ));

            $queries->create('languages', array(
                'name' => 'Japanese',
                'is_default' => 0
            ));

	        $queries->create('languages', array(
		        'name' => 'Lithuanian',
		        'is_default' => 0
	        ));

            $queries->create('languages', array(
                'name' => 'Norwegian',
                'is_default' => 0
            ));

	        $queries->create('languages', array(
		        'name' => 'Polish',
		        'is_default' => 0
	        ));

            $queries->create('languages', array(
                'name' => 'Portuguese',
                'is_default' => 0
            ));
			
            $queries->create('languages', array(
                'name' => 'Romanian',
                'is_default' => 0
            ));

	        $queries->create('languages', array(
		        'name' => 'Slovak',
		        'is_default' => 0
	        ));

            $queries->create('languages', array(
                'name' => 'Spanish',
                'is_default' => 0
            ));

            $queries->create('languages', array(
                'name' => 'SwedishSE',
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

            $queries->create('modules', array(
                'name' => 'DefaultTheme',
                'enabled' => 0
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
                'html' => '<i class="fas fa-thumbs-up text-success"></i>',
                'enabled' => 1,
                'type' => 2
            ));

            $queries->create('reactions', array(
                'name' => 'Dislike',
                'html' => '<i class="fas fa-thumbs-down text-danger"></i>',
                'enabled' => 1,
                'type' => 0
            ));

            $queries->create('reactions', array(
                'name' => 'Meh',
                'html' => '<i class="fas fa-meh text-warning"></i>',
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
		        'name' => 'recaptcha_login',
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

            $queries->create('privacy_terms', array(
                'name' => 'terms',
                'value' => '<p>You agree to be bound by our website rules and any laws which may apply to this website and your participation.</p><p>The website administration have the right to terminate your account at any time, delete any content you may have posted, and your IP address and any data you input to the website is recorded to assist the site staff with their moderation duties.</p><p>The site administration have the right to change these terms and conditions, and any site rules, at any point without warning. Whilst you may be informed of any changes, it is your responsibility to check these terms and the rules at any point.</p>'
            ));

            $queries->create('settings', array(
                'name' => 'nameless_version',
                'value' => '2.0.0-pr7'
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

            $queries->create('settings', array(
                'name' => 'maintenance_message',
                'value' => 'This website is currently in maintenance mode.'
            ));
            $cache->setCache('maintenance_cache');
            $cache->store('maintenance', array('maintenance' => 'false', 'message' => 'This website is currently in maintenance mode.'));

            $queries->create('settings', array(
                'name' => 'authme',
                'value' => 0
            ));

            $queries->create('settings', array(
                'name' => 'authme_db',
                'value' => null
            ));
			
            $queries->create('settings', array(
                'name' => 'force_https',
                'value' => 'false'
            ));

            $queries->create('settings', array(
                'name' => 'default_avatar_type',
                'value' => 'minecraft'
            ));

            $queries->create('settings', array(
                'name' => 'custom_default_avatar',
                'value' => null
            ));

            $queries->create('settings', array(
                'name' => 'private_profile',
                'value' => 1
            ));
			
            $queries->create('settings', array(
                'name' => 'registration_disabled_message',
                'value' => null
            ));

            $queries->create('settings', array(
                'name' => 'discord_url',
                'value' => null
            ));

            $queries->create('settings', array(
                'name' => 'discord_hooks',
                'value' => '{}'
            ));

            $queries->create('settings', array(
                'name' => 'api_verification',
                'value' => '0'
            ));

            $queries->create('settings', array(
                'name' => 'validate_user_action',
                'value' => '{"action":"promote","group":1}'
            ));

            $queries->create('settings', array(
                'name' => 'login_method',
                'value' => 'email'
            ));

            $queries->create('settings', array(
                'name' => 'username_sync',
                'value' => '1'
            ));

            $queries->create('privacy_terms', array(
                'name' => 'privacy',
                'value' => 'The following privacy policy outlines how your data is used on our website.<br /><br /><strong>Data</strong><br />Basic non-identifiable information about your user on the website is collected; the majority of which is provided during registration, such as email addresses and usernames.<br />In addition to this, IP addresses for registered users are stored within the system to aid with moderation duties. This includes spam prevention, and detecting alternative accounts.<br /><br />Accounts can be deleted by a site administrator upon request, which will remove all data relating to your user from our system.<br /><br /><strong>Cookies</strong><br />Cookies are used to store small pieces of non-identifiable information with your consent. In order to consent to the use of cookies, you must either close the cookie notice (as explained within the notice) or register on our website.<br />Data stored by cookies include any recently viewed topic IDs, along with a unique, unidentifiable hash upon logging in and selecting &quot;Remember Me&quot; to automatically log you in next time you visit.'
            ));

	        $queries->create('settings', array(
		        'name' => 'status_page',
		        'value' => '1'
	        ));

            // Templates
            $queries->create('templates', array(
                'name' => 'Default',
                'enabled' => 1,
                'is_default' => 0
            ));

	        $queries->create('templates', array(
		        'name' => 'DefaultRevamp',
		        'enabled' => 1,
		        'is_default' => 1
	        ));

            $cache->setCache('templatecache');
            $cache->store('default', 'DefaultRevamp');

	        $queries->create('panel_templates', array(
		        'name' => 'Default',
		        'enabled' => 1,
		        'is_default' => 1
	        ));
	        $cache->store('panel_default', 'Default');

            // Widgets - initialise just a few default ones for now
	        $queries->create('widgets', array(
		        'name' => 'Online Staff',
		        'enabled' => 1,
				'pages' => '["index","forum"]'
	        ));

	        $queries->create('widgets', array(
		        'name' => 'Online Users',
		        'enabled' => 1,
		        'pages' => '["index","forum"]'
	        ));

	        $queries->create('widgets', array(
		        'name' => 'Statistics',
		        'enabled' => 1,
		        'pages' => '["index","forum"]'
	        ));
			
		$cache->setCache('Core-widgets');
		$cache->store('enabled', array(
			'Online Staff' => 1,
			'Online Users' => 1,
			'Statistics' => 1
		));
			
		$cache->setCache('backgroundcache');
		$cache->store('banner_image', '/uploads/template_banners/homepage_bg_trimmed.jpg');

            // Success
            Redirect::to('?step=user');
            die();

            break;

        case 'user':
        // Admin user creation
        // Require password hashing methods
        require(ROOT_PATH . '/core/includes/password.php');

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
                    $login = $user->login(Input::get('email'), Input::get('password'), true);

                    if($login){
                        Redirect::to('?step=convert');
                        die();
                    } else {
                        $error = $language['unable_to_login'];
                        $queries = new Queries();
                        $queries->delete('users', array('id', '=', 1));
                    }


                } catch(Exception $e){
                    $error = $language['unable_to_create_account'] . ': ' . $e->getMessage();
                }

            } else {
                // Get errors
                foreach($validation->errors() as $item){
                    if(strpos($item, 'is required') !== false){
                        $error = $language['input_required'];
                    } else if(strpos($item, 'minimum') !== false){
                        $error = $language['input_minimum'];
                    } else if(strpos($item, 'maximum') !== false){
                        $error = $language['input_maximum'];
                    } else if(strpos($item, 'must match') !== false){
                        $error = $language['passwords_must_match'];
                    } else if(strpos($item, 'not a valid email') !== false){
                        $error = $language['email_invalid'];
                    }
                }
            }
        }
        ?>
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h3><?php echo $language['creating_admin_account']; ?></h3>

            <p><?php echo $language['enter_admin_details']; ?></p>

            <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="inputUsername"><?php echo $language['username']; ?></label>
                    <input type="text" class="form-control" name="username" id="inputUsername" placeholder="<?php echo $language['username']; ?>" tabindex="1">
                </div>

                <div class="form-group">
                    <label for="inputEmail"><?php echo $language['email_address']; ?></label>
                    <input type="email" class="form-control" name="email" id="inputEmail" placeholder="<?php echo $language['email_address']; ?>" tabindex="2">
                </div>

                <div class="form-group">
                    <label for="inputPassword"><?php echo $language['password']; ?></label>
                    <input type="password" class="form-control" name="password" id="inputPassword" placeholder="<?php echo $language['password']; ?>" tabindex="3">
                </div>

                <div class="form-group">
                    <label for="inputPasswordAgain"><?php echo $language['confirm_password']; ?></label>
                    <input type="password" class="form-control" name="password_again" id="inputPasswordAgain" placeholder="<?php echo $language['confirm_password']; ?>" tabindex="4">
                </div>

                <div class="form-group">
                    <input type="submit" value="<?php echo $language['submit']; ?>" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
    <div style="text-align:center">
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
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h3><?php echo $language['upgrade']; ?></h3>
			<p><?php echo $language['input_v1_details']; ?></p>
            <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="inputDBAddress"><?php echo $language['database_address']; ?></label>
                    <input type="text" class="form-control" name="db_address" id="inputDBAddress" placeholder="<?php echo $language['database_address']; ?>">
                </div>

                <div class="form-group">
                    <label for="inputDBPort"><?php echo $language['database_port']; ?></label>
                    <input type="text" class="form-control" name="db_port" id="inputDBPort" placeholder="<?php echo $language['database_port']; ?>" value="3306">
                </div>

                <div class="form-group">
                    <label for="inputDBUsername"><?php echo $language['database_username']; ?></label>
                    <input type="text" class="form-control" name="db_username" id="inputDBUsername" placeholder="<?php echo $language['database_username']; ?>">
                </div>

                <div class="form-group">
                    <label for="inputDBPassword"><?php echo $language['database_password']; ?></label>
                    <input type="password" class="form-control" name="db_password" id="inputDBPassword" placeholder="<?php echo $language['database_password']; ?>">
                </div>

                <div class="form-group">
                    <label for="inputDBName"><?php echo $language['database_name']; ?></label>
                    <input type="text" class="form-control" name="db_name" id="inputDBName" placeholder="<?php echo $language['database_name']; ?>">
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="<?php echo $language['submit']; ?>">
                </div>
            </form>
        </div>
    </div>
    <div style="text-align:center">
			<?php
        break;

		case 'do_upgrade':
			// Query old v1 database and insert into v2
			if(!isset($_GET['s']) || (isset($_GET['s']) && $_GET['s'] != '9')) $conn = DB_Custom::getInstance($_SESSION['db_address'], $_SESSION['db_name'], $_SESSION['db_username'], $_SESSION['db_password'], $_SESSION['db_port']);
			echo '<div class="alert alert-info">' . $language['installer_upgrading_database'] . '</div>';

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
					echo '<div class="alert alert-warning"><p>' . $language['errors_logged'] . '</p><a href="?step=do_upgrade&amp;s=1" class="btn btn-secondary">' . $language['continue'] . '</a></div>';
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
										'last_post_date' => ($item->last_post_date) ? strtotime($item->last_post_date) : null,
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
										'admin_cp' => (($item->staff || $item->mod_cp) ? 1 : 0),
										'default_group' => (($item->id == 1) ? 1 : 0)
									));
								}

								$queries->update('groups', 1, array('permissions' => '{"usercp.messaging":1,"usercp.signature":1,"usercp.nickname":1,"usercp.private_profile":1,"usercp.profile_banner":1}'));
								$queries->update('groups', 2, array('permissions' => '{"admincp.core":1,"admincp.core.api":1,"admincp.core.general":1,"admincp.core.avatars":1,"admincp.core.fields":1,"admincp.core.debugging":1,"admincp.core.emails":1,"admincp.core.navigation":1,"admincp.core.reactions":1,"admincp.core.registration":1,"admincp.core.social_media":1,"admincp.core.terms":1,"admincp.errors":1,"admincp.integrations":1,"admincp.minecraft":1,"admincp.minecraft.authme":1,"admincp.minecraft.verification":1,"admincp.minecraft.servers":1,"admincp.minecraft.query_errors":1,"admincp.minecraft.banners":1,"admincp.modules":1,"admincp.pages":1,"admincp.pages.metadata":1,"admincp.security":1,"admincp.security.acp_logins":1,"admincp.security.template":1,"admincp.sitemap":1,"admincp.styles":1,"admincp.styles.panel_templates":1,"admincp.styles.templates":1,"admincp.styles.templates.edit":1,"admincp.styles.images":1,"admincp.update":1,"admincp.users":1,"admincp.users.edit":1,"admincp.groups":1,"admincp.groups.self":1,"admincp.widgets":1,"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"admincp.forums":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1, "admincp.security.all":1}'));
								$queries->update('groups', 3, array('permissions' => '{"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"admincp.users":1,"modcp.profile_banner_reset":1,"usercp.messaging":1,"usercp.signature":1,"usercp.private_profile":1,"usercp.nickname":1,"usercp.profile_banner":1,"profile.private.bypass":1}'));
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert groups: ' . $e->getMessage() . '</div>';
							$error = true;
						}

						if(isset($error)){
							echo '<div class="alert alert-warning"><p>' . $language['errors_logged'] . '</p><a href="?step=do_upgrade&amp;s=2" class="btn btn-secondary">' . $language['continue'] . '</a></div>';
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
										'created' => strtotime($item->post_date),
										'deleted' => $item->deleted
									));
								}
							}
						} catch(Exception $e){
							echo '<div class="alert alert-danger">Unable to convert posts: ' . $e->getMessage() . '</div>';
							$error = true;
						}

						if(isset($error)){
							echo '<div class="alert alert-warning"><p>' . $language['errors_logged'] . '</p><a href="?step=do_upgrade&amp;s=3" class="btn btn-secondary">' . $language['continue'] . '</a></div>';
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
							echo '<div class="alert alert-warning"><p>' . $language['errors_logged'] . '</p><a href="?step=do_upgrade&amp;s=4" class="btn btn-secondary">' . $language['continue'] . '</a></div>';
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
							echo '<div class="alert alert-warning"><p>' . $language['errors_logged'] . '</p><a href="?step=do_upgrade&amp;s=5" class="btn btn-secondary">' . $language['continue'] . '</a></div>';
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
							echo '<div class="alert alert-warning"><p>' . $language['errors_logged'] . '</p><a href="?step=do_upgrade&amp;s=6" class="btn btn-secondary">' . $language['continue'] . '</a></div>';
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
							echo '<div class="alert alert-warning"><p>' . $language['errors_logged'] . '</p><a href="?step=do_upgrade&amp;s=7" class="btn btn-secondary">' . $language['continue'] . '</a></div>';
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
							echo '<div class="alert alert-warning"><p>' . $language['errors_logged'] . '</p><a href="?step=do_upgrade&amp;s=8" class="btn btn-secondary">' . $language['continue'] . '</a></div>';
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
                            'name' => 'Chinese',
                            'is_default' => 0
                        ));
                        $queries->create('languages', array(
                            'name' => 'Czech',
                            'is_default' => 0
                        ));
						$queries->create('languages', array(
							'name' => 'Dutch',
							'is_default' => 0
						));
                        $queries->create('languages', array(
                            'name' => 'EnglishUS',
                            'is_default' => 0
                        ));
                        $queries->create('languages', array(
                            'name' => 'German',
                            'is_default' => 0
                        ));
                        $queries->create('languages', array(
                            'name' => 'Greek',
                            'is_default' => 0
                        ));
                        $queries->create('languages', array(
                            'name' => 'Japanese',
                            'is_default' => 0
                        ));
						$queries->create('languages', array(
							'name' => 'Lithuanian',
							'is_default' => 0
						));
                        $queries->create('languages', array(
                            'name' => 'Norwegian',
                            'is_default' => 0
                        ));
						$queries->create('languages', array(
							'name' => 'Polish',
							'is_default' => 0
						));
                        $queries->create('languages', array(
                            'name' => 'Portuguese',
                            'is_default' => 0
                        ));
                        $queries->create('languages', array(
                            'name' => 'Romanian',
                            'is_default' => 0
                        ));
						$queries->create('languages', array(
							'name' => 'Slovak',
							'is_default' => 0
						));
                        $queries->create('languages', array(
                            'name' => 'Spanish',
                            'is_default' => 0
                        ));
                        $queries->create('languages', array(
                            'name' => 'SwedishSE',
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
						$queries->create('modules', array(
							'name' => 'DefaultTheme',
							'enabled' => 0
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
							'html' => '<i class="fas fa-thumbs-up text-success"></i>',
							'enabled' => 1,
							'type' => 2
						));
						$queries->create('reactions', array(
							'name' => 'Dislike',
							'html' => '<i class="fas fa-thumbs-down text-danger"></i>',
							'enabled' => 1,
							'type' => 0
						));
						$queries->create('reactions', array(
							'name' => 'Meh',
							'html' => '<i class="fas fa-meh text-warning"></i>',
							'enabled' => 1,
							'type' => 1
						));

						// Forum Labels
						$queries->create('forums_labels', array(
							'name' => 'Default',
							'html' => '<span class="badge badge-default">{x}</span>'
						));
						$queries->create('forums_labels', array(
							'name' => 'Primary',
							'html' => '<span class="badge badge-primary">{x}</span>'
						));
						$queries->create('forums_labels', array(
							'name' => 'Success',
							'html' => '<span class="badge badge-success">{x}</span>'
						));
						$queries->create('forums_labels', array(
							'name' => 'Info',
							'html' => '<span class="badge badge-info">{x}</span>'
						));
						$queries->create('forums_labels', array(
							'name' => 'Warning',
							'html' => '<span class="badge badge-warning">{x}</span>'
						));
						$queries->create('forums_labels', array(
							'name' => 'Danger',
							'html' => '<span class="badge badge-danger">{x}</span>'
						));

						// Settings
						$queries->create('settings', array(
							'name' => 'registration_enabled',
							'value' => 1
						));

						$queries->create('settings', array(
							'name' => 'recaptcha_login',
							'value' => 'false'
						));

						$version = $queries->getWhere('settings', array('name', '=', 'version'));
						if(count($version)){
							$queries->update('settings', $version[0]->id, array(
								'name' => 'nameless_version',
								'value' => '2.0.0-pr7'
							));
						} else {
							$queries->create('settings', array(
								'name' => 'nameless_version',
								'value' => '2.0.0-pr7'
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

						$queries->create('settings', array(
							'name' => 'maintenance_message',
							'value' => 'This website is currently in maintenance mode.'
						));
						$cache->setCache('maintenance_cache');
						$cache->store('maintenance', array('maintenance' => 'false', 'message' => 'This website is currently in maintenance mode.'));

						$queries->create('settings', array(
						    'name' => 'authme',
						    'value' => 0
						));

						$queries->create('settings', array(
						    'name' => 'authme_db',
						    'value' => null
						));

						$queries->create('settings', array(
						    'name' => 'force_https',
						    'value' => 'false'
						));

						$queries->create('settings', array(
						    'name' => 'default_avatar_type',
						    'value' => 'minecraft'
						));

						$queries->create('settings', array(
						    'name' => 'custom_default_avatar',
						    'value' => null
						));
			
						$queries->create('settings', array(
						    'name' => 'private_profile',
						    'value' => 1
						));
						
						$queries->create('settings', array(
						    'name' => 'registration_disabled_message',
						    'value' => null
						));

						$queries->create('settings', array(
						    'name' => 'discord_url',
						    'value' => null
						));

						$queries->create('settings', array(
						    'name' => 'discord_hooks',
						    'value' => '{}'
						));

						$queries->create('settings', array(
						    'name' => 'api_verification',
						    'value' => '1'
						));

						$queries->create('settings', array(
						    'name' => 'validate_user_action',
						    'value' => '{"action":"activate"}'
						));

						$queries->create('settings', array(
						    'name' => 'login_method',
						    'value' => 'email'
						));

						$queries->create('settings', array(
						    'name' => 'username_sync',
						    'value' => '1'
						));

						$queries->create('privacy_terms', array(
						    'name' => 'privacy',
						    'value' => 'The following privacy policy outlines how your data is used on our website.<br /><br /><strong>Data</strong><br />Basic non-identifiable information about your user on the website is collected; the majority of which is provided during registration, such as email addresses and usernames.<br />In addition to this, IP addresses for registered users are stored within the system to aid with moderation duties. This includes spam prevention, and detecting alternative accounts.<br /><br />Accounts can be deleted by a site administrator upon request, which will remove all data relating to your user from our system.<br /><br /><strong>Cookies</strong><br />Cookies are used to store small pieces of non-identifiable information with your consent. In order to consent to the use of cookies, you must either close the cookie notice (as explained within the notice) or register on our website.<br />Data stored by cookies include any recently viewed topic IDs, along with a unique, unidentifiable hash upon logging in and selecting &quot;Remember Me&quot; to automatically log you in next time you visit.'
						));

						$terms = $queries->getWhere('settings', array('name', '=', 't_and_c_site'));
						if(count($terms)){
							$queries->create('privacy_terms', array(
								'name' => 'terms',
								'value' => $terms[0]->value
							));
						}

						$queries->create('settings', array(
							'name' => 'status_page',
							'value' => '1'
						));

						// Templates
						$queries->create('templates', array(
							'name' => 'Default',
							'enabled' => 1,
							'is_default' => 0
						));

						$queries->create('templates', array(
							'name' => 'DefaultRevamp',
							'enabled' => 1,
							'is_default' => 1
						));

						$cache->setCache('templatecache');
						$cache->store('default', 'DefaultRevamp');

						$queries->create('panel_templates', array(
							'name' => 'Default',
							'enabled' => 1,
							'is_default' => 1
						));
						$cache->store('panel_default', 'Default');

						// Widgets - initialise just a few default ones for now
						$queries->create('widgets', array(
							'name' => 'Online Staff',
							'enabled' => 1,
							'pages' => '["index","forum"]'
						));

						$queries->create('widgets', array(
							'name' => 'Online Users',
							'enabled' => 1,
							'pages' => '["index","forum"]'
						));

						$queries->create('widgets', array(
							'name' => 'Statistics',
							'enabled' => 1,
							'pages' => '["index","forum"]'
						));
						
						$cache->setCache('Core-widgets');
						$cache->store('enabled', array(
							'Online Staff' => 1,
							'Online Users' => 1,
							'Statistics' => 1
						));

						$cache->setCache('backgroundcache');
						$cache->store('banner_image', '/uploads/template_banners/homepage_bg_trimmed.jpg');

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
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-3">
		  <div class="alert alert-success"><p>Upgrade complete!</p><div style="text-align:center"><a href="?step=finish" class="btn btn-primary"><?php echo $language['continue']; ?></a></div></div>
		</div>
	</div>
	<div>
						<?php
					break;
				}
			}
		break;

        case 'convert':
            // Convert from a different forum software
            if(!isset($_GET['convert'])){
                ?>

                <h3><?php echo $language['convert']; ?></h3>
                <p><?php echo $language['convert_message']; ?></p>
                <a class="btn btn-success btn-lg" href="?step=convert&amp;convert=yes"><?php echo $language['yes']; ?></a>
                <a class="btn btn-primary btn-lg" href="?step=finish"><?php echo $language['no']; ?></a>

                <?php
            } else {
            	if($_GET['convert'] == 'yes'){
		            // Display list of converters
		            $available_converters = array_filter(glob(ROOT_PATH . '/custom/converters/*'), 'is_dir');
		            $converters = array();

		            if(count($available_converters)){
		            	foreach($available_converters as $converter){
		            		if(file_exists($converter . '/converter.php')){
		            			$path = explode(DIRECTORY_SEPARATOR, $converter);
		            			$converters[] = $path[count($path) - 1];
							}
						}
					}

		            if(count($converters)){
		            	if(isset($_POST) && !empty($_POST)){
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
									$password = str_replace('\'', '\\\'', $_POST['db_password']);
								} else {
									$password = '';
								}

								$mysqli = new mysqli(Input::get('db_address'), Input::get('db_username'), $password, Input::get('db_name'), Input::get('db_port'));
								if($mysqli->connect_errno) {
									$error = $mysqli->connect_errno . ' - ' . $mysqli->connect_error;
								} else {
									// Load converter
									if(isset($_POST['converter']) && in_array($_POST['converter'], $converters)){
										$mysqli->close();

										// Re-open as PDO
										$conn = DB_Custom::getInstance(Input::get('db_address'), Input::get('db_name'), Input::get('db_username'), $password, Input::get('db_port'));

										require_once(ROOT_PATH . '/custom/converters/' . $_POST['converter'] . '/converter.php');

										if(!isset($error)){
											Redirect::to('?step=finish');
											die();
										}
									} else {
										$error = $language['unable_to_load_converter'];
									}
								}
							} else
								$error = $language['database_error'];

						}

		            	?>
						</div>
						<div class="row">
							<div class="col-md-6 offset-md-3">
								<?php if(isset($error)){ ?><div class="alert alert-danger"><?php echo $error; ?></div><?php } ?>
								<form action="" method="post">
									<div class="form-group">
										<label for="inputConverter"><?php echo $language['converter']; ?></label>
										<select class="form-control" name="converter" id="inputConverter">
											<?php
											foreach($converters as $converter){
												?>
												<option value="<?php echo Output::getClean($converter); ?>"><?php echo str_replace('_', ' ', Output::getClean($converter)); ?></option>
												<?php
											}
											?>
										</select>
									</div>
									<div class="form-group">
										<label for="inputDBAddress"><?php echo $language['database_address']; ?></label>
										<input type="text" class="form-control" name="db_address" id="inputDBAddress" value="127.0.0.1" placeholder="<?php echo $language['database_address']; ?>">
									</div>

									<div class="form-group">
										<label for="inputDBPort"><?php echo $language['database_port']; ?></label>
										<input type="text" class="form-control" name="db_port" id="inputDBPort" placeholder="<?php echo $language['database_port']; ?>" value="3306">
									</div>

									<div class="form-group">
										<label for="inputDBUsername"><?php echo $language['database_username']; ?></label>
										<input type="text" class="form-control" name="db_username" id="inputDBUsername" placeholder="<?php echo $language['database_username']; ?>">
									</div>

									<div class="form-group">
										<label for="inputDBPassword"><?php echo $language['database_password']; ?></label>
										<input type="password" class="form-control" name="db_password" id="inputDBPassword" placeholder="<?php echo $language['database_password']; ?>">
									</div>

									<div class="form-group">
										<label for="inputDBName"><?php echo $language['database_name']; ?></label>
										<input type="text" class="form-control" name="db_name" id="inputDBName" placeholder="<?php echo $language['database_name']; ?>">
									</div>

									<div class="form-group">
										<input type="submit" class="btn btn-primary" value="<?php echo $language['submit']; ?>">
										<a href="./install.php?step=convert" class="btn btn-warning"><?php echo $language['back']; ?></a>
									</div>
								</form>
							</div>
						</div>
						<?php
					}
				}
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
			try {
				if(is_writable('core/config.php'))
					file_put_contents('core/config.php', '$CONFIG[\'installed\'] = true;', FILE_APPEND);
				else
					die('Config not writable');
			} catch(Exception $e){
				die($e->getMessage());
			}
			?>
                <h3><?php echo $language['finish']; ?></h3>
                <p><?php echo $language['finish_message']; ?></p>
                <p><?php echo $language['support_message']; ?></p>
                <p><a href="index.php?route=/panel" class="btn btn-success btn-lg"><?php echo $language['finish']; ?></a></p>
    			<hr />
    			<h3><?php echo $language['credits']; ?></h3>
    			<p><?php echo $language['credits_message']; ?></p>
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
            <div class="btn-group dropup">
              <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fa fa-globe" aria-hidden="true"></i>
              </button>
              <div class="dropdown-menu">
                  <?php
                  // Display all languages
                  $languages = glob('custom' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . '*' , GLOB_ONLYDIR);
                  foreach($languages as $item){
                      $folders = explode(DIRECTORY_SEPARATOR, $item);
                  ?>
                <a class="dropdown-item" href="#" onclick="setLanguage($(this).text())"><?php echo Output::getClean($folders[2]); ?></a>
                  <?php
                  }
                  ?>
              </div>
            </div>
		    <a class="btn btn-primary" href="https://github.com/NamelessMC/Nameless" target="_blank"><i class="fa fa-github" aria-hidden="true"></i></a>
		  </span>
        </div>
    </div>
<script src="core/assets/js/jquery.min.js"></script>
<script src="core/assets/js/tether.min.js"></script>
<script src="core/assets/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $('[data-toggle="popover"]').popover({ trigger: "manual" , html: true, animation:false}).on("mouseenter", function () {
        var _this = this;
        $(this).popover("show");
        $(".popover").on("mouseleave", function () {
            $(_this).popover('hide');
        });
    }).on("mouseleave", function () {
        var _this = this;
        setTimeout(function () {
            if (!$(".popover:hover").length) {
                $(_this).popover("hide");
            }
        }, 300);
    });

    function setLanguage(language){
        $.ajax({
            'url' : 'install.php?language=' + language,
            'type' : 'GET',
            'success' : function(data) {
                if(data == "OK"){
                    window.location.reload();
                }
            }
        });
    }
</script>
</body>
</html>
