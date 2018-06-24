<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Admin core settings page
 */

// Can the user view the AdminCP?
if ($user->isLoggedIn()) {
    if (!$user->canViewACP()) {
        // No
        Redirect::to(URL::build('/'));
        die();
    } else {
        // Check the user has re-authenticated
        if (!$user->isAdmLoggedIn()) {
            // They haven't, do so now
            Redirect::to(URL::build('/admin/auth'));
            die();
        } else {
            if(!$user->hasPermission('admincp.core')){
                require(ROOT_PATH . '/404.php');
                die();
            }
        }
    }
} else {
    // Not logged in
    Redirect::to(URL::build('/login'));
    die();
}

$page = 'admin';
$admin_page = 'core';

// Query database for settings
$current_default_language = $queries->getWhere('settings', array('name', '=', 'language'));
$current_default_language = $current_default_language[0]->value;

?>
<!DOCTYPE html>
<html lang="<?php echo(defined('HTML_LANG') ? HTML_LANG : 'en'); ?>" <?php if(defined('HTML_RTL') && HTML_RTL === true) echo ' dir="rtl"'; ?>>
<head>
    <!-- Standard Meta -->
    <meta charset="<?php echo (defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <?php
    $title = $language->get('admin', 'admin_cp');
    require(ROOT_PATH . '/core/templates/admin_header.php');
    ?>

    <link rel="stylesheet"
          href="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.css">
    <link rel="stylesheet"
          href="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/dropzone/dropzone.min.css">
    <link rel="stylesheet"
          href="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/image-picker/image-picker.css">

    <style type="text/css">
        .thumbnails li img {
            width: 60px;
        }
    </style>

</head>
<body>
<?php require(ROOT_PATH . '/modules/Core/pages/admin/navbar.php'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php require(ROOT_PATH . '/modules/Core/pages/admin/sidebar.php'); ?>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-block">
                    <h3><?php echo $language->get('admin', 'core'); ?></h3>
                    <?php if (!isset($_GET['view'])) { ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <?php if($user->hasPermission('admincp.core.general')){ ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo URL::build('/admin/core/', 'view=general'); ?>"><?php echo $language->get('admin', 'general_settings'); ?></a>
                                    </td>
                                </tr>
                                <?php } if($user->hasPermission('admincp.core.api')){ ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo URL::build('/admin/api'); ?>"><?php echo $language->get('admin', 'api'); ?></a>
                                        </td>
                                    </tr>
                                <?php } if($user->hasPermission('admincp.core.avatars')){ ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo URL::build('/admin/core/', 'view=avatars'); ?>"><?php echo $language->get('admin', 'avatars'); ?></a>
                                    </td>
                                </tr>
                                <?php } if($user->hasPermission('admincp.core.fields')){ ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo URL::build('/admin/core/', 'view=profile'); ?>"><?php echo $language->get('admin', 'custom_fields'); ?></a>
                                    </td>
                                </tr>
                                <?php } if($user->hasPermission('admincp.core.debugging')){ ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo URL::build('/admin/core/', 'view=maintenance'); ?>"><?php echo $language->get('admin', 'debugging_and_maintenance'); ?></a>
                                    </td>
                                </tr>
                                <?php } if($user->hasPermission('admincp.core.emails')){ ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo URL::build('/admin/core/', 'view=email'); ?>"><?php echo $language->get('admin', 'emails'); ?></a>
                                    </td>
                                </tr>
                                <?php } if($user->hasPermission('admincp.core.navigation')){ ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo URL::build('/admin/core/', 'view=navigation'); ?>"><?php echo $language->get('admin', 'navigation'); ?></a>
                                    </td>
                                </tr>
                                <?php } if($user->hasPermission('admincp.core.terms')){ ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo URL::build('/admin/core/', 'view=terms'); ?>"><?php echo $language->get('admin', 'privacy_and_terms'); ?></a>
                                        </td>
                                    </tr>
                                <?php } if($user->hasPermission('admincp.core.reactions')){ ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo URL::build('/admin/core/', 'view=reactions'); ?>"><?php echo $language->get('user', 'reactions'); ?></a>
                                    </td>
                                </tr>
                                <?php } if($user->hasPermission('admincp.core.registration')){ ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo URL::build('/admin/registration'); ?>"><?php echo $language->get('admin', 'registration'); ?></a>
                                    </td>
                                </tr>
                                <?php } if($user->hasPermission('admincp.core.social_media')){ ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo URL::build('/admin/core/', 'view=social'); ?>"><?php echo $language->get('admin', 'social_media'); ?></a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>
                        <?php
                    } else {
                        switch ($_GET['view']) {
                            case 'general':
                                if(!$user->hasPermission('admincp.core.general')){
                                    Redirect::to(URL::build('/admin/core'));
                                    die();
                                }
                                if (isset($_GET['do']) && $_GET['do'] == 'installLanguage') {
                                    // Install new language
                                    $languages = glob('custom' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
                                    foreach ($languages as $item) {
                                        $folders = explode(DIRECTORY_SEPARATOR, $item);

                                        // Is it already in the database?
                                        $exists = $queries->getWhere('languages', array('name', '=', Output::getClean($folders[2])));
                                        if (!count($exists)) {
                                            // No, add it now
                                            $queries->create('languages', array(
                                                'name' => Output::getClean($folders[2])
                                            ));
                                        }
                                    }

                                    Session::flash('general_language', $language->get('admin', 'installed_languages'));
                                    Redirect::to(URL::build('/admin/core/', 'view=general'));
                                    die();
                                }

                                // Deal with input
                                if (Input::exists()) {
                                    if (Token::check(Input::get('token'))) {
                                        // Validate input
                                        $validate = new Validate();

                                        $validation = $validate->check($_POST, array(
                                            'sitename' => array(
                                                'required' => true,
                                                'min' => 2,
                                                'max' => 64
                                            ),
                                            'contact_email' => array(
                                                'required' => true,
                                                'min' => 3,
                                                'max' => 255
                                            )
                                        ));

                                        if ($validation->passed()) {
                                            // Update settings
                                            // Sitename
                                            $sitename_id = $queries->getWhere('settings', array('name', '=', 'sitename'));
                                            $sitename_id = $sitename_id[0]->id;

                                            $queries->update('settings', $sitename_id, array(
                                                'value' => Output::getClean(Input::get('sitename'))
                                            ));

                                            // Update cache
                                            $cache->setCache('sitenamecache');
                                            $cache->store('sitename', Output::getClean(Input::get('sitename')));

                                            // Email address
                                            $contact_id = $queries->getWhere('settings', array('name', '=', 'incoming_email'));
                                            $contact_id = $contact_id[0]->id;

                                            $queries->update('settings', $contact_id, array(
                                                'value' => Output::getClean(Input::get('contact_email'))
                                            ));

                                            // Language
                                            // Get current default language
                                            $default_language = $queries->getWhere('languages', array('is_default', '=', 1));
                                            $default_language = $default_language[0];

                                            if ($default_language->name != Input::get('language')) {
                                                // The default language has been changed
                                                $queries->update('languages', $default_language->id, array(
                                                    'is_default' => 0
                                                ));

                                                $language_id = $queries->getWhere('languages', array('id', '=', Input::get('language')));
                                                $language_name = Output::getClean($language_id[0]->name);
                                                $language_id = $language_id[0]->id;

                                                $queries->update('languages', $language_id, array(
                                                    'is_default' => 1
                                                ));

                                                // Update cache
                                                $cache->setCache('languagecache');
                                                $cache->store('language', $language_name);
                                            }

                                            // Timezone
                                            $timezone_id = $queries->getWhere('settings', array('name', '=', 'timezone'));
                                            $timezone_id = $timezone_id[0]->id;

                                            try {
                                                $queries->update('settings', $timezone_id, array(
                                                    'value' => Output::getClean($_POST['timezone'])
                                                ));

                                                // Cache
                                                $cache->setCache('timezone_cache');
                                                $cache->store('timezone', Output::getClean($_POST['timezone']));

                                            } catch (Exception $e) {
                                                $errors = array($e->getMessage());
                                            }

                                            // Portal
                                            $portal_id = $queries->getWhere('settings', array('name', '=', 'portal'));
                                            $portal_id = $portal_id[0]->id;

                                            if ($_POST['homepage'] == 'portal') {
                                                $use_portal = 1;
                                            } else $use_portal = 0;

                                            $queries->update('settings', $portal_id, array(
                                                'value' => $use_portal
                                            ));

                                            // Update cache
                                            $cache->setCache('portal_cache');
                                            $cache->store('portal', $use_portal);
											
                                            // Private profile
                                            $private_profile_id = $queries->getWhere('settings', array('name', '=', 'private_profile'));
                                            $private_profile_id = $private_profile_id[0]->id;

                                            if($_POST['privateProfile'])
                                              $private_profile = 1;
                                            else
                                              $private_profile = 0;

                                            $queries->update('settings', $private_profile_id, array(
                                                'value' => $private_profile
                                            ));

                                            // Registration displaynames
                                            $displaynames_id = $queries->getWhere('settings', array('name', '=', 'displaynames'));
                                            $displaynames_id = $displaynames_id[0]->id;

                                            $queries->update('settings', $displaynames_id, array(
                                                'value' => $_POST['displaynames']
                                            ));

                                            // Post formatting
                                            $formatting_id = $queries->getWhere('settings', array('name', '=', 'formatting_type'));
                                            $formatting_id = $formatting_id[0]->id;

                                            $queries->update('settings', $formatting_id, array(
                                                'value' => Output::getClean(Input::get('formatting'))
                                            ));

                                            // Update cache
                                            $cache->setCache('post_formatting');
                                            $cache->store('formatting', Output::getClean(Input::get('formatting')));

                                            // Friendly URLs
                                            if (Input::get('friendlyURL') == 'true') $friendly = true;
                                            else $friendly = false;

                                            if (is_writable(ROOT_PATH . '/' . join(DIRECTORY_SEPARATOR, array('core', 'config.php')))) {

                                                // Require config
                                                if (isset($path) && file_exists($path . 'core/config.php')) {
                                                    $loadedConfig = json_decode(file_get_contents($path . 'core/config.php'), true);
                                                } else {
                                                    $loadedConfig = json_decode(file_get_contents(ROOT_PATH . '/core/config.php'), true);
                                                }

                                                if (is_array($loadedConfig)) {
                                                    $GLOBALS['config'] = $loadedConfig;
                                                }

                                                // Make string to input
                                                Config::set('core/friendly', $friendly);

                                            } else $errors = array($language->get('admin', 'config_not_writable'));

                                            // Force HTTPS?
                                            if (Input::get('forceHTTPS') == 'true')
                                                $https = 'true';
                                            else
                                                $https = 'false';

                                            $force_https_id = $queries->getWhere('settings', array('name', '=', 'force_https'));
                                            if (count($force_https_id)) {
                                                $force_https_id = $force_https_id[0]->id;
                                                $queries->update('settings', $force_https_id, array(
                                                    'value' => $https
                                                ));
                                            } else {
                                                $queries->create('settings', array(
                                                    'name' => 'force_https',
                                                    'value' => $https
                                                ));
                                            }

                                            /*
                                            if(!empty($_POST["allowedProxies"])) {
                                                $allowedProxies = $_POST["allowedProxies"];
                                                $allowedProxies = str_replace("\r", "", $allowedProxies);
                                                $allowedProxies = preg_replace('/\s+/', ' ', $allowedProxies);
                                                $allowedProxies = str_replace(" ", "", $allowedProxies);

                                                Config::set("allowedProxies", $allowedProxies);
                                            }else {
                                                Config::set("allowedProxies", "");
                                            }
                                            */

                                            // Login method
                                            $login_method_id = $queries->getWhere('settings', array('name', '=', 'login_method'));
                                            $login_method_id = $login_method_id[0]->id;

                                            $queries->update('settings', $login_method_id, array(
                                                'value' => $_POST['login_method']
                                            ));

                                            Log::getInstance()->log(Log::Action('admin/core/general'));
                                            
                                            // Update cache
                                            $cache->setCache('force_https_cache');
                                            $cache->store('force_https', $https);

                                            // Redirect in case URL type has changed
                                            if (!isset($errors)) {
                                                if ($friendly == 'true') {
                                                    $redirect = URL::build('/admin/core', 'view=general', 'friendly');
                                                } else {
                                                    $redirect = URL::build('/admin/core', 'view=general', 'non-friendly');
                                                }
                                                Redirect::to($redirect);
                                                die();
                                            }

                                        } else $errors = array($language->get('admin', 'missing_sitename'));
                                    } else {
                                        // Invalid token
                                        $errors = array($language->get('general', 'invalid_token'));
                                    }
                                }
                                ?>
                                <form action="" method="post">
                                    <?php if (Session::exists('general_language')) { ?>
                                        <div class="alert alert-success"><?php echo Session::flash('general_language'); ?></div><?php } ?>
                                    <?php if (isset($errors)) { ?>
                                        <div class="alert alert-danger"><?php foreach ($errors as $error) echo $error; ?></div><?php } ?>
                                    <div class="form-group">
                                        <?php
                                        // Get site name
                                        $sitename = $queries->getWhere('settings', array('name', '=', 'sitename'));
                                        $sitename = $sitename[0];
                                        ?>
                                        <label for="inputSitename"><?php echo $language->get('admin', 'sitename'); ?></label>
                                        <input type="text" class="form-control" name="sitename" id="inputSitename"
                                               value="<?php echo Output::getClean($sitename->value); ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <?php
                                        // Get contact email address
                                        $contact_email = $queries->getWhere('settings', array('name', '=', 'incoming_email'));
                                        $contact_email = $contact_email[0];
                                        ?>
                                        <label for="inputContactEmail"><?php echo $language->get('admin', 'contact_email_address'); ?></label>
                                        <input type="text" class="form-control" name="contact_email"
                                               id="inputContactEmail"
                                               value="<?php echo Output::getClean($contact_email->value); ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputLanguage"><?php echo $language->get('admin', 'default_language'); ?></label>
                                        <span class="badge badge-info"><i class="fa fa-question" data-container="body"
                                                                          data-toggle="popover" data-placement="top"
                                                                          title="<?php echo $language->get('general', 'info'); ?>"
                                                                          data-content="<?php echo $language->get('admin', 'default_language_help'); ?>"></i></span>
                                        <div class="input-group">
                                            <?php
                                            // Get languages
                                            $languages = $queries->getWhere('languages', array('id', '<>', 0));
                                            ?>
                                            <select name="language" class="form-control" id="inputLanguage">
                                                <?php
                                                foreach ($languages as $item) {
                                                    ?>
                                                    <option value="<?php echo $item->id; ?>"<?php if ($item->is_default == 1) { ?> selected<?php } ?>><?php echo Output::getClean($item->name); ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="input-group-btn">
                                                <a class="btn btn-secondary"
                                                   href="<?php echo URL::build('/admin/core/', 'view=general&amp;do=installLanguage'); ?>"><i
                                                            class="fa fa-plus-circle"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputTimezone"><?php echo $language->get('admin', 'default_timezone'); ?></label>
                                        <?php
                                        // Get timezone setting
                                        $timezone = $queries->getWhere('settings', array('name', '=', 'timezone'));
                                        $timezone = $timezone[0];
                                        ?>
                                        <select name="timezone" class="form-control" id="inputTimezone">
                                            <?php foreach (Util::listTimezones() as $key => $item) { ?>
                                                <option value="<?php echo $key; ?>"<?php if ($timezone->value == $key) { ?> selected<?php } ?>>
                                                    (<?php echo $item['offset']; ?>) - <?php echo $item['name']; ?>
                                                    (<?php echo $item['time']; ?>)
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputHomepage"><?php echo $language->get('admin', 'homepage_type'); ?></label>
                                        <?php
                                        // Get portal setting
                                        $portal = $queries->getWhere('settings', array('name', '=', 'portal'));
                                        $portal = $portal[0];
                                        ?>
                                        <select name="homepage" class="form-control" id="inputHomepage">
                                            <option value="default"<?php if ($portal->value == 0) { ?> selected<?php } ?>><?php echo $language->get('admin', 'default'); ?></option>
                                            <option value="portal"<?php if ($portal->value == 1) { ?> selected<?php } ?>><?php echo $language->get('admin', 'portal'); ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <?php
                                        // Get post formatting setting
                                        $cache->setCache('post_formatting');
                                        $formatting = $cache->retrieve('formatting');
                                        ?>
                                        <label for="inputFormatting"><?php echo $language->get('admin', 'post_formatting_type'); ?></label>
                                        <select name="formatting" class="form-control" id="inputFormatting">
                                            <option value="html"<?php if ($formatting == 'html') { ?> selected<?php } ?>>
                                                HTML
                                            </option>
                                            <option value="markdown"<?php if ($formatting == 'markdown') { ?> selected<?php } ?>>
                                                Markdown
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <?php
                                        // Get friendly URL setting
                                        $friendly_url = Config::get('core/friendly');
                                        ?>
                                        <label for="inputFriendlyURL"><?php echo $language->get('admin', 'use_friendly_urls'); ?></label>
                                        <span class="badge badge-info"><i class="fa fa-question" data-container="body"
                                                                          data-toggle="popover" data-placement="top"
                                                                          title="<?php echo $language->get('general', 'info'); ?>"
                                                                          data-content="<?php echo $language->get('admin', 'use_friendly_urls_help'); ?>"></i></span>
                                        <select name="friendlyURL" class="form-control" id="inputFriendlyURL">
                                            <option value="true"<?php if ($friendly_url == true) { ?> selected<?php } ?>><?php echo $language->get('admin', 'enabled'); ?></option>
                                            <option value="false"<?php if ($friendly_url == false) { ?> selected<?php } ?>><?php echo $language->get('admin', 'disabled'); ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <?php
                                        // Get force SSL setting
                                        if(defined('FORCE_SSL'))
                                            $force_https = true;
                                        else
                                            $force_https = false;
                                        ?>
                                        <label for="inputForceHTTPS"><?php echo $language->get('admin', 'force_https'); ?></label>
                                        <span class="badge badge-info"><i class="fa fa-question" data-container="body"
                                                                          data-toggle="popover" data-placement="top"
                                                                          title="<?php echo $language->get('general', 'info'); ?>"
                                                                          data-content="<?php echo $language->get('admin', 'force_https_help'); ?>"></i></span>
                                        <select name="forceHTTPS" class="form-control" id="inputForceHTTPS">
                                            <option value="true"<?php if ($force_https) { ?> selected<?php } ?>><?php echo $language->get('admin', 'enabled'); ?></option>
                                            <option value="false"<?php if (!$force_https) { ?> selected<?php } ?>><?php echo $language->get('admin', 'disabled'); ?></option>
                                        </select>
                                    </div>
									<div class="form-group">
                                        <label for="inputPrivateProfile"><?php echo $language->get('user', 'private_profile'); ?></label>
                                        <?php
                                        // Get private profile settings
                                        $private_profile = $queries->getWhere('settings', array('name', '=', 'private_profile'));
                                        $private_profile = $private_profile[0];
                                        ?>
                                        <select name="privateProfile" class="form-control" id="inputPrivateProfile">
                                            <option value="1"<?php if ($private_profile->value == 1) { ?> selected<?php } ?>><?php echo $language->get('admin', 'enabled'); ?></option>
                                            <option value="0"<?php if ($private_profile->value == 0) { ?> selected<?php } ?>><?php echo $language->get('admin', 'disabled'); ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEnableNicknames"><?php echo $language->get('admin', 'enable_nicknames_on_registration'); ?></label>
                                        <?php
                                        // Get nickname setting
                                        $displaynames = $queries->getWhere('settings', array('name', '=', 'displaynames'));
                                        $displaynames = $displaynames[0];
                                        ?>
                                        <select name="displaynames" class="form-control" id="inputEnableNicknames">
                                            <option value="true"<?php if ($displaynames->value == "true") { ?> selected<?php } ?>><?php echo $language->get('admin', 'enabled'); ?></option>
                                            <option value="false"<?php if ($displaynames->value == "false") { ?> selected<?php } ?>><?php echo $language->get('admin', 'disabled'); ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputLoginMethod"><?php echo $language->get('admin', 'login_method'); ?></label>
                                        <?php
                                        // Get login method
                                        $method = $queries->getWhere('settings', array('name', '=', 'login_method'));
                                        $method = $method[0];
                                        ?>
                                        <select name="login_method" class="form-control" id="inputLoginMethod">
                                            <option value="email"<?php if ($method->value == "email") { ?> selected<?php } ?>><?php echo $language->get('user', 'email'); ?></option>
                                            <option value="username"<?php if ($method->value == "username") { ?> selected<?php } ?>><?php echo $language->get('user', 'username'); ?></option>
                                        </select>
                                    </div>
                                    <!--
                                    <div class="form-group">
                                        <label for=allowedProxies"><?php echo $language->get('admin', 'allowed_proxies'); ?></label>
                                        <?php
                                        // Make sure there's a default list
                                        $allowedProxies = Config::get("allowedProxies");
                                        $allowedProxies = str_replace(",", "\n", $allowedProxies)
                                        ?>
                                        <textarea class="form-control" placeholder="<?php echo $language->get('admin', 'allowed_proxies_info'); ?>" name="allowedProxies" id="allowedProxies" cols="30" rows="10"><?php
                                            echo $allowedProxies;
                                            ?></textarea>
                                    </div>
                                    -->
                                    <br/>
                                    <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                                    <input type="submit" class="btn btn-primary"
                                           value="<?php echo $language->get('general', 'submit'); ?>">
                                </form>
                                <?php
                                break;

                            case 'profile':
                                if(!$user->hasPermission('admincp.core.fields')){
                                    Redirect::to(URL::build('/admin/core'));
                                    die();
                                }
                                if (!isset($_GET['id']) && !isset($_GET['action'])) {
                                    // Custom profile fields
                                    $profile_fields = $queries->getWhere('profile_fields', array('id', '<>', 0));
                                    ?>
                                    <h4 style="display:inline;"><?php echo $language->get('admin', 'custom_fields'); ?></h4>
                                    <span class="pull-right">
                                      <a class="btn btn-primary" href="<?php echo URL::build('/admin/core/', 'view=profile&amp;action=new'); ?>"><?php echo $language->get('admin', 'new_field'); ?></a>
                                    </span>
                                    <br/><br/>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th><?php echo $language->get('admin', 'field_name'); ?></th>
                                            <th><?php echo $language->get('admin', 'type'); ?></th>
                                            <th><?php echo $language->get('admin', 'required'); ?></th>
											<th><?php echo $language->get('admin', 'editable'); ?></th>
                                            <th><?php echo $language->get('admin', 'public'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (count($profile_fields)) {
                                            foreach ($profile_fields as $field) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <a href="<?php echo URL::build('/admin/core/', 'view=profile&amp;id=' . $field->id); ?>"><?php echo Output::getClean($field->name); ?></a>
                                                    </td>
                                                    <td><?php
                                                        switch ($field->type) {
                                                            case 1:
                                                                // Text field
                                                                echo $language->get('admin', 'text');
                                                                break;
                                                            case 2:
                                                                // Textarea
                                                                echo $language->get('admin', 'textarea');
                                                                break;
                                                            case 3:
                                                                // Date
                                                                echo $language->get('admin', 'date');
                                                                break;
                                                        } ?></td>
                                                    <td><?php
                                                        if ($field->required == 1) echo '<i class="fa fa-check-circle-o" aria-hidden="true"></i>';
                                                        else echo '<i class="fa fa-times-circle-o" aria-hidden="true"></i>';
                                                        ?></td>
													<td><?php
                                                        if ($field->editable == 1) echo '<i class="fa fa-check-circle-o" aria-hidden="true"></i>';
                                                        else echo '<i class="fa fa-times-circle-o" aria-hidden="true"></i>';
                                                        ?></td>
                                                    <td><?php
                                                        if ($field->public == 1) echo '<i class="fa fa-check-circle-o" aria-hidden="true"></i>';
                                                        else echo '<i class="fa fa-times-circle-o" aria-hidden="true"></i>';
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php
                                } else {
                                    if (isset($_GET['action'])) {
                                        if ($_GET['action'] == 'new') {
                                            // New field
                                            if (Input::exists()) {
                                                if (Token::check(Input::get('token'))) {
                                                    // Validate input
                                                    $validate = new Validate();

                                                    $validation = $validate->check($_POST, array(
                                                        'name' => array(
                                                            'required' => true,
                                                            'min' => 2,
                                                            'max' => 16
                                                        ),
                                                        'type' => array(
                                                            'required' => true
                                                        )
                                                    ));

                                                    if ($validation->passed()) {
                                                        // Input into database
                                                        try {
                                                            // Get whether required/public/editable/forum post options are enabled or not
                                                            if (isset($_POST['required']) && $_POST['required'] == 'on') $required = 1;
                                                            else $required = 0;

                                                            if (isset($_POST['public']) && $_POST['public'] == 'on') $public = 1;
                                                            else $public = 0;

                                                            if (isset($_POST['forum']) && $_POST['forum'] == 'on') $forum_posts = 1;
                                                            else $forum_posts = 0;
															
															if (isset($_POST['editable']) && $_POST['editable'] == 'on') $editable = 1;
                                                            else $editable = 0;

                                                            // Insert into database
                                                            $queries->create('profile_fields', array(
                                                                'name' => Output::getClean(Input::get('name')),
                                                                'type' => Input::get('type'),
                                                                'public' => $public,
                                                                'required' => $required,
                                                                'description' => Output::getClean(Input::get('description')),
                                                                'forum_posts' => $forum_posts,
																'editable' => $editable
                                                            ));

                                                            Log::getInstance()->log(Log::Action('admin/core/profile/new'), Output::getClean(Input::get('name')));

                                                            // Redirect
                                                            Redirect::to(URL::build('/admin/core/', 'view=profile'));
                                                            die();

                                                        } catch (Exception $e) {
                                                            $error = $e->getMessage();
                                                        }

                                                    } else {
                                                        // Display errors
                                                        $error = $language->get('admin', 'profile_field_error');
                                                    }
                                                } else {
                                                    // Invalid token
                                                    $error = $language->get('admin', 'invalid_token');
                                                }
                                            }

                                            ?>
                                            <h4 style="display:inline;"><?php echo $language->get('admin', 'creating_profile_field'); ?></h4>
                                            <span class="pull-right">
                                              <a class="btn btn-danger" href="<?php echo URL::build('/admin/core/', 'view=profile'); ?>" onclick="return confirm('<?php echo $language->get('general', 'confirm_cancel'); ?>');"><?php echo $language->get('general', 'cancel'); ?></a>
                                            </span>
                                            <br/><br/>
                                            <?php if (isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
                                            <form action="" method="post">
                                                <div class="form-group">
                                                    <label for="inputName"><?php echo $language->get('admin', 'field_name'); ?></label>
                                                    <input type="text" name="name" id="inputName" class="form-control"
                                                           placeholder="<?php echo $language->get('admin', 'field_name'); ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputType"><?php echo $language->get('admin', 'type'); ?></label>
                                                    <select class="form-control" name="type" id="inputType">
                                                        <option value="1"><?php echo $language->get('admin', 'text'); ?></option>
                                                        <option value="2"><?php echo $language->get('admin', 'textarea'); ?></option>
                                                        <option value="3"><?php echo $language->get('admin', 'date'); ?></option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputDescription"><?php echo $language->get('admin', 'description'); ?></label>
                                                    <textarea id="inputDescription" name="description"
                                                              class="form-control"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputRequired"><?php echo $language->get('admin', 'required'); ?></label>
                                                    <span class="badge badge-info"><i class="fa fa-question"
                                                                                      data-container="body"
                                                                                      data-toggle="popover"
                                                                                      data-placement="top"
                                                                                      title="<?php echo $language->get('general', 'info'); ?>"
                                                                                      data-content="<?php echo $language->get('admin', 'profile_field_required_help'); ?>"></i></span>
                                                    <input type="checkbox" id="inputRequired" name="required"
                                                           class="js-switch"/>
                                                </div>
												
												<div class="form-group">
                                                    <label for="inputEditable"><?php echo $language->get('admin', 'editable'); ?></label>
                                                    <span class="badge badge-info"><i class="fa fa-question"
                                                                                      data-container="body"
                                                                                      data-toggle="popover"
                                                                                      data-placement="top"
                                                                                      title="<?php echo $language->get('general', 'info'); ?>"
                                                                                      data-content="<?php echo $language->get('admin', 'profile_field_editable_help'); ?>"></i></span>
                                                    <input type="checkbox" id="inputEditable" name="editable"
                                                           class="js-switch"/>
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputPublic"><?php echo $language->get('admin', 'public'); ?></label>
                                                    <span class="badge badge-info"><i class="fa fa-question"
                                                                                      data-container="body"
                                                                                      data-toggle="popover"
                                                                                      data-placement="top"
                                                                                      title="<?php echo $language->get('general', 'info'); ?>"
                                                                                      data-content="<?php echo $language->get('admin', 'profile_field_public_help'); ?>"></i></span>
                                                    <input type="checkbox" id="inputPublic" name="public"
                                                           class="js-switch"/>
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputForum"><?php echo $language->get('admin', 'display_field_on_forum'); ?></label>
                                                    <span class="badge badge-info"><i class="fa fa-question"
                                                                                      data-container="body"
                                                                                      data-toggle="popover"
                                                                                      data-placement="top"
                                                                                      title="<?php echo $language->get('general', 'info'); ?>"
                                                                                      data-content="<?php echo $language->get('admin', 'profile_field_forum_help'); ?>"></i></span>
                                                    <input type="checkbox" id="inputForum" name="forum"
                                                           class="js-switch"/>
                                                </div>

                                                <div class="form-group">
                                                    <input type="hidden" name="token"
                                                           value="<?php echo Token::get(); ?>">
                                                    <input type="submit" class="btn btn-primary"
                                                           value="<?php echo $language->get('general', 'submit'); ?>">
                                                </div>
                                            </form>
                                            <?php
                                        } else if ($_GET['action'] == 'delete') {
                                            // Delete field
                                            if (isset($_GET['id'])){
                                                $queries->delete('profile_fields', array('id', '=', $_GET['id']));
                                                Log::getInstance()->log(Log::Action('admin/core/profile/delete'), Output::getClean($_GET['id']));
                                            }

                                            Redirect::to(URL::build('/admin/core/', 'view=profile'));
                                            die();
                                        }
                                    } else if (isset($_GET['id']) && !isset($_GET['action'])) {
                                        // Editing field

                                        // Ensure field actually exists
                                        if (!is_numeric($_GET['id'])) {
                                            Redirect::to(URL::build('/admin/core/', 'view=profile'));
                                            die();
                                        }

                                        $field = $queries->getWhere('profile_fields', array('id', '=', $_GET['id']));
                                        if (!count($field)) {
                                            Redirect::to(URL::build('/admin/core/', 'view=profile'));
                                            die();
                                        }

                                        $field = $field[0];

                                        if (Input::exists()) {
                                            if (Token::check(Input::get('token'))) {
                                                // Validate input
                                                $validate = new Validate();

                                                $validation = $validate->check($_POST, array(
                                                    'name' => array(
                                                        'required' => true,
                                                        'min' => 2,
                                                        'max' => 16
                                                    ),
                                                    'type' => array(
                                                        'required' => true
                                                    )
                                                ));

                                                if ($validation->passed()) {
                                                    // Update database
                                                    try {
                                                        // Get whether required/public/editable/forum post options are enabled or not
                                                        if (isset($_POST['required']) && $_POST['required'] == 'on') $required = 1;
                                                        else $required = 0;

                                                        if (isset($_POST['public']) && $_POST['public'] == 'on') $public = 1;
                                                        else $public = 0;

                                                        if (isset($_POST['forum']) && $_POST['forum'] == 'on') $forum_posts = 1;
                                                        else $forum_posts = 0;
														
														if (isset($_POST['editable']) && $_POST['editable'] == 'on') $editable = 1;
                                                        else $editable = 0;

                                                        // Update database
                                                        $queries->update('profile_fields', $field->id, array(
                                                            'name' => Output::getClean(Input::get('name')),
                                                            'type' => Input::get('type'),
                                                            'public' => $public,
                                                            'required' => $required,
                                                            'description' => Output::getClean(Input::get('description')),
                                                            'forum_posts' => $forum_posts,
															'editable' => $editable
                                                        ));

                                                        Log::getInstance()->log(Log::Action('admin/core/profile/update'), Output::getClean(Input::get('name')));

                                                        // Redirect
                                                        Redirect::to(URL::build('/admin/core/', 'view=profile'));
                                                        die();

                                                    } catch (Exception $e) {
                                                        $error = $e->getMessage();
                                                    }
                                                } else {
                                                    // Error
                                                    $error = $language->get('admin', 'profile_field_error');
                                                }

                                            } else {
                                                $error = $language->get('admin', 'invalid_token');
                                            }
                                        }

                                        // Generate form token
                                        $token = Token::get();

                                        ?>
                                        <h4 style="display:inline;"><?php echo $language->get('admin', 'editing_profile_field'); ?></h4>
                                        <span class="pull-right">
                                          <a class="btn btn-warning" href="<?php echo URL::build('/admin/core/', 'view=profile'); ?>" onclick="return confirm('<?php echo $language->get('general', 'confirm_cancel'); ?>');"><?php echo $language->get('general', 'cancel'); ?></a>
                                          <a class="btn btn-danger" href="<?php echo URL::build('/admin/core/', 'view=profile&amp;action=delete&amp;id=' . $field->id); ?>" onclick="return confirm('<?php echo $language->get('general', 'confirm_deletion'); ?>');"><?php echo $language->get('general', 'delete'); ?></a>
                                        </span>
                                        <br/><br/>
                                        <?php if (isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
                                        <form action="" method="post">
                                            <div class="form-group">
                                                <label for="inputName"><?php echo $language->get('admin', 'field_name'); ?></label>
                                                <input type="text" name="name" id="inputName" class="form-control"
                                                       placeholder="<?php echo $language->get('admin', 'field_name'); ?>"
                                                       value="<?php echo Output::getClean($field->name); ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="inputType"><?php echo $language->get('admin', 'type'); ?></label>
                                                <select class="form-control" name="type" id="inputType">
                                                    <option value="1"<?php if ($field->type == 1) echo ' selected'; ?>><?php echo $language->get('admin', 'text'); ?></option>
                                                    <option value="2"<?php if ($field->type == 2) echo ' selected'; ?>><?php echo $language->get('admin', 'textarea'); ?></option>
                                                    <option value="3"<?php if ($field->type == 3) echo ' selected'; ?>><?php echo $language->get('admin', 'date'); ?></option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputDescription"><?php echo $language->get('admin', 'description'); ?></label>
                                                <textarea id="inputDescription" name="description"
                                                          class="form-control"><?php echo Output::getPurified($field->description); ?></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputRequired"><?php echo $language->get('admin', 'required'); ?></label>
                                                <span class="badge badge-info"><i class="fa fa-question"
                                                                                  data-container="body"
                                                                                  data-toggle="popover"
                                                                                  data-placement="top"
                                                                                  title="<?php echo $language->get('general', 'info'); ?>"
                                                                                  data-content="<?php echo $language->get('admin', 'profile_field_required_help'); ?>"></i></span>
                                                <input type="checkbox" id="inputRequired" name="required"
                                                       class="js-switch" <?php if ($field->required == 1) echo ' checked'; ?>/>
                                            </div>
											
											<div class="form-group">
                                                <label for="inputEditable"><?php echo $language->get('admin', 'editable'); ?></label>
                                                <span class="badge badge-info"><i class="fa fa-question"
                                                                                  data-container="body"
                                                                                  data-toggle="popover"
                                                                                  data-placement="top"
                                                                                  title="<?php echo $language->get('general', 'info'); ?>"
                                                                                  data-content="<?php echo $language->get('admin', 'profile_field_editable_help'); ?>"></i></span>
                                                <input type="checkbox" id="inputEditable" name="editable"
                                                       class="js-switch" <?php if ($field->editable == 1) echo ' checked'; ?>/>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputPublic"><?php echo $language->get('admin', 'public'); ?></label>
                                                <span class="badge badge-info"><i class="fa fa-question"
                                                                                  data-container="body"
                                                                                  data-toggle="popover"
                                                                                  data-placement="top"
                                                                                  title="<?php echo $language->get('general', 'info'); ?>"
                                                                                  data-content="<?php echo $language->get('admin', 'profile_field_public_help'); ?>"></i></span>
                                                <input type="checkbox" id="inputPublic" name="public"
                                                       class="js-switch" <?php if ($field->public == 1) echo ' checked'; ?>/>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputForum"><?php echo $language->get('admin', 'display_field_on_forum'); ?></label>
                                                <span class="badge badge-info"><i class="fa fa-question"
                                                                                  data-container="body"
                                                                                  data-toggle="popover"
                                                                                  data-placement="top"
                                                                                  title="<?php echo $language->get('general', 'info'); ?>"
                                                                                  data-content="<?php echo $language->get('admin', 'profile_field_forum_help'); ?>"></i></span>
                                                <input type="checkbox" id="inputForum" name="forum"
                                                       class="js-switch" <?php if ($field->forum_posts == 1) echo ' checked'; ?>/>
                                            </div>

                                            <div class="form-group">
                                                <input type="hidden" name="token" value="<?php echo $token; ?>">
                                                <input type="submit" class="btn btn-primary"
                                                       value="<?php echo $language->get('general', 'submit'); ?>">
                                            </div>
                                        </form>
                                        <?php
                                    }
                                }
                                break;

                            case 'reactions':
                                if(!$user->hasPermission('admincp.core.reactions')){
                                    Redirect::to(URL::build('/admin/core'));
                                    die();
                                }
                                if (!isset($_GET['id']) && (!isset($_GET['action']))) {
                                    // Get all reactions
                                    $reactions = $queries->getWhere('reactions', array('id', '<>', 0));
                                    ?>
                                    <h4 style="display:inline;"><?php echo $language->get('user', 'reactions'); ?></h4>
                                    <span class="pull-right">
                                      <a class="btn btn-primary" href="<?php echo URL::build('/admin/core/', 'view=reactions&amp;action=new'); ?>"><?php echo $language->get('admin', 'new_reaction'); ?></a>
                                    </span>
                                    <br /><br />
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th><?php echo $language->get('admin', 'name'); ?></th>
                                            <th><?php echo $language->get('admin', 'icon'); ?></th>
                                            <th><?php echo $language->get('admin', 'type'); ?></th>
                                            <th><?php echo $language->get('admin', 'enabled'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (count($reactions)){
                                        foreach ($reactions as $reaction){
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo URL::build('/admin/core/', 'view=reactions&amp;id=' . $reaction->id); ?>"><?php echo Output::getClean($reaction->name); ?></a>
                                            </td>
                                            <td><?php echo $reaction->html; ?></td>
                                            <td><?php if ($reaction->type == 2) echo $language->get('admin', 'positive'); else if ($reaction->type == 1) echo $language->get('admin', 'neutral'); else echo $language->get('admin', 'negative'); ?></td>
                                            <td><?php if ($reaction->enabled == 1) { ?><i
                                                        class="fa fa-check-circle text-success"
                                                        aria-hidden="true"></i><?php } else { ?><i
                                                        class="fa fa-times-circle text-danger"
                                                        aria-hidden="true"></i><?php } ?></td>
                                            <?php
                                            }
                                            }
                                            ?>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <?php
                                } else {
                                    if (isset($_GET['id']) && !isset($_GET['action'])) {
                                        // Get reaction
                                        $reaction = $queries->getWhere('reactions', array('id', '=', $_GET['id']));
                                        if (!count($reaction)) {
                                            // Reaction doesn't exist
                                            Redirect::to(URL::build('/admin/core/', 'view=reactions'));
                                            die();

                                        } else $reaction = $reaction[0];

                                        // Deal with input
                                        if (Input::exists()) {
                                            if (Token::check(Input::get('token'))) {
                                                // Validate input
                                                $validate = new Validate();
                                                $validation = $validate->check($_POST, array(
                                                    'name' => array(
                                                        'required' => true,
                                                        'min' => 1,
                                                        'max' => 16
                                                    ),
                                                    'html' => array(
                                                        'required' => true,
                                                        'min' => 1,
                                                        'max' => 255
                                                    ),
                                                    'type' => array(
                                                        'required' => true
                                                    )
                                                ));

                                                if ($validation->passed()) {
                                                    // Check enabled status
                                                    if (isset($_POST['enabled']) && $_POST['enabled'] == 'on') $enabled = 1;
                                                    else $enabled = 0;

                                                    switch (Input::get('type')) {
                                                        case 1:
                                                            $type = 1;
                                                            break;
                                                        case 2:
                                                            $type = 2;
                                                            break;
                                                        default:
                                                            $type = 0;
                                                            break;
                                                    }

                                                    // Update database
                                                    $queries->update('reactions', $_GET['id'], array(
                                                        'name' => Output::getClean(Input::get('name')),
                                                        'html' => Output::getPurified(htmlspecialchars_decode(Input::get('html'))),
                                                        'type' => $type,
                                                        'enabled' => $enabled
                                                    ));

                                                    Log::getInstance()->log(Log::Action('admin/core/reaction/update'), Output::getClean(Input::get('name')));

                                                    $reaction = $queries->getWhere('reactions', array('id', '=', $_GET['id']));
                                                    $reaction = $reaction[0];
                                                } else {
                                                    // Validation error
                                                }
                                            } else {
                                                // Invalid token
                                            }
                                        }
                                        ?>
                                        <h4 style="display:inline;"><?php echo $language->get('admin', 'editing_reaction'); ?></h4>
                                        <span class="pull-right">
                                          <a href="<?php echo URL::build('/admin/core/', 'view=reactions&amp;action=delete&amp;reaction=' . $reaction->id); ?>" onclick="return confirm('<?php echo $language->get('general', 'confirm_deletion'); ?>');" class="btn btn-danger"><?php echo $language->get('general', 'delete'); ?></a>
                                          <a href="<?php echo URL::build('/admin/core/', 'view=reactions'); ?>" class="btn btn-warning"><?php echo $language->get('general', 'cancel'); ?></a>
                                        </span>
                                        <hr/>
                                        <form action="" method="post">
                                            <div class="form-group">
                                                <label for="InputReactionName"><?php echo $language->get('admin', 'name'); ?></label>
                                                <input type="text" class="form-control" name="name"
                                                       id="InputReactionName"
                                                       placeholder="<?php echo $language->get('admin', 'name'); ?>"
                                                       value="<?php echo Output::getClean($reaction->name); ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="InputReactionHTML"><?php echo $language->get('admin', 'html'); ?></label>
                                                <input type="text" class="form-control" name="html"
                                                       id="InputReactionHTML"
                                                       placeholder="<?php echo $language->get('admin', 'html'); ?>"
                                                       value="<?php echo Output::getClean($reaction->html); ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="InputReactionType"><?php echo $language->get('admin', 'type'); ?></label>
                                                <select name="type" class="form-control" id="InputReactionType">
                                                    <option value="2"<?php if ($reaction->type == 2) echo ' selected'; ?>><?php echo $language->get('admin', 'positive'); ?></option>
                                                    <option value="1"<?php if ($reaction->type == 1) echo ' selected'; ?>><?php echo $language->get('admin', 'neutral'); ?></option>
                                                    <option value="-1"<?php if ($reaction->type == 0) echo ' selected'; ?>><?php echo $language->get('admin', 'negative'); ?></option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="InputEnabled"><?php echo $language->get('admin', 'enabled'); ?></label>
                                                <input type="checkbox" name="enabled"
                                                       class="js-switch"<?php if ($reaction->enabled == 1) echo ' checked'; ?>/>
                                            </div>

                                            <div class="form-group">
                                                <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                                                <input type="submit"
                                                       value="<?php echo $language->get('general', 'submit'); ?>"
                                                       class="btn btn-primary">
                                            </div>
                                        </form>
                                        <?php
                                    } else if (isset($_GET['action'])) {
                                        if ($_GET['action'] == 'new') {
                                            // Deal with input
                                            if (Input::exists()) {
                                                if (Token::check(Input::get('token'))) {
                                                    // Validate input
                                                    $validate = new Validate();
                                                    $validation = $validate->check($_POST, array(
                                                        'name' => array(
                                                            'required' => true,
                                                            'min' => 1,
                                                            'max' => 16
                                                        ),
                                                        'html' => array(
                                                            'required' => true,
                                                            'min' => 1,
                                                            'max' => 255
                                                        ),
                                                        'type' => array(
                                                            'required' => true
                                                        )
                                                    ));

                                                    if ($validation->passed()) {
                                                        // Check enabled status
                                                        if (isset($_POST['enabled']) && $_POST['enabled'] == 'on') $enabled = 1;
                                                        else $enabled = 0;

                                                        switch (Input::get('type')) {
                                                            case 1:
                                                                $type = 1;
                                                                break;
                                                            case 2:
                                                                $type = 2;
                                                                break;
                                                            default:
                                                                $type = 0;
                                                                break;
                                                        }

                                                        // Update database
                                                        $queries->create('reactions', array(
                                                            'name' => Output::getClean(Input::get('name')),
                                                            'html' => Output::getPurified(htmlspecialchars_decode(Input::get('html'))),
                                                            'type' => $type,
                                                            'enabled' => $enabled
                                                        ));

                                                        Log::getInstance()->log(Log::Action('admin/core/reaction/add'), Output::getClean(Input::get('name')));

                                                        Redirect::to(URL::build('/admin/core/', 'view=reactions'));
                                                        die();
                                                    } else {
                                                        // Validation error
                                                    }
                                                } else {
                                                    // Invalid token
                                                }
                                            }
                                            ?>
                                            <h4 style="display:inline;"><?php echo $language->get('admin', 'creating_reaction'); ?></h4>
                                            <span class="pull-right">
                                              <a href="<?php echo URL::build('/admin/core/', 'view=reactions'); ?>" class="btn btn-warning"><?php echo $language->get('general', 'cancel'); ?></a>
                                            </span>
                                            <hr/>
                                            <form action="" method="post">
                                                <div class="form-group">
                                                    <label for="InputReactionName"><?php echo $language->get('admin', 'name'); ?></label>
                                                    <input type="text" class="form-control" name="name"
                                                           id="InputReactionName"
                                                           placeholder="<?php echo $language->get('admin', 'name'); ?>"
                                                           value="<?php echo Output::getClean(Input::get('name')); ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label for="InputReactionHTML"><?php echo $language->get('admin', 'html'); ?></label>
                                                    <input type="text" class="form-control" name="html"
                                                           id="InputReactionHTML"
                                                           placeholder="<?php echo $language->get('admin', 'html'); ?>"
                                                           value="<?php echo Output::getClean(Input::get('html')); ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label for="InputReactionType"><?php echo $language->get('admin', 'type'); ?></label>
                                                    <select name="type" class="form-control" id="InputReactionType">
                                                        <option value="2"><?php echo $language->get('admin', 'positive'); ?></option>
                                                        <option value="1"><?php echo $language->get('admin', 'neutral'); ?></option>
                                                        <option value="-1"><?php echo $language->get('admin', 'negative'); ?></option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="InputEnabled"><?php echo $language->get('admin', 'enabled'); ?></label>
                                                    <input type="checkbox" name="enabled" class="js-switch"/>
                                                </div>

                                                <div class="form-group">
                                                    <input type="hidden" name="token"
                                                           value="<?php echo Token::get(); ?>">
                                                    <input type="submit"
                                                           value="<?php echo $language->get('general', 'submit'); ?>"
                                                           class="btn btn-primary">
                                                </div>
                                            </form>
                                            <?php
                                        } else if ($_GET['action'] == 'delete') {
                                            // Check specified reaction exists
                                            if (!isset($_GET['reaction']) || !is_numeric($_GET['reaction'])) {
                                                Redirect::to(URL::build('/admin/core/', 'view=reactions'));
                                                die();
                                            }

                                            // Delete reaction
                                            $queries->delete('reactions', array('id', '=', $_GET['reaction']));

                                            //TODO: Name
                                            Log::getInstance()->log(Log::Action('admin/core/reaction/delete'), $_GET['reaction']);

                                            // Redirect
                                            Redirect::to(URL::build('/admin/core/', 'view=reactions'));
                                            die();
                                        }
                                    }
                                }
                                break;

                            case 'social':
                                if(!$user->hasPermission('admincp.core.social_media')){
                                    Redirect::to(URL::build('/admin/core'));
                                    die();
                                }
                                // Deal with input
                                if (Input::exists()) {
                                    if (Token::check(Input::get('token'))) {
                                        // Update database values
                                        // Youtube URL
                                        $youtube_url_id = $queries->getWhere('settings', array('name', '=', 'youtube_url'));
                                        $youtube_url_id = $youtube_url_id[0]->id;

                                        $queries->update('settings', $youtube_url_id, array(
                                            'value' => Output::getClean(Input::get('youtubeurl'))
                                        ));

                                        // Update cache
                                        $cache->setCache('social_media');
                                        $cache->store('youtube', Output::getClean(Input::get('youtubeurl')));

                                        // Twitter URL
                                        $twitter_url_id = $queries->getWhere('settings', array('name', '=', 'twitter_url'));
                                        $twitter_url_id = $twitter_url_id[0]->id;

                                        $queries->update('settings', $twitter_url_id, array(
                                            'value' => Output::getClean(Input::get('twitterurl'))
                                        ));

                                        $cache->store('twitter', Output::getClean(Input::get('twitterurl')));

                                        // Twitter dark theme
                                        $twitter_dark_theme = $queries->getWhere('settings', array('name', '=', 'twitter_style'));
                                        $twitter_dark_theme = $twitter_dark_theme[0]->id;

                                        if (isset($_POST['twitter_dark_theme']) && $_POST['twitter_dark_theme'] == 1) $theme = 'dark';
                                        else $theme = 'light';

                                        $queries->update('settings', $twitter_dark_theme, array(
                                            'value' => $theme
                                        ));

                                        $cache->store('twitter_theme', $theme);

										// Discord ID
                                        $discord_id = $queries->getWhere('settings', array('name', '=', 'discord'));
                                        $discord_id = $discord_id[0]->id;

                                        $queries->update('settings', $discord_id, array(
                                            'value' => Output::getClean(Input::get('discordid'))
                                        ));

                                        $cache->store('discord', Output::getClean(Input::get('discordid')));
										
                                        // Google Plus URL
                                        $gplus_url_id = $queries->getWhere('settings', array('name', '=', 'gplus_url'));
                                        $gplus_url_id = $gplus_url_id[0]->id;

                                        $queries->update('settings', $gplus_url_id, array(
                                            'value' => Output::getClean(Input::get('gplusurl'))
                                        ));

                                        $cache->store('google_plus', Output::getClean(Input::get('gplusurl')));

                                        // Facebook URL
                                        $fb_url_id = $queries->getWhere('settings', array('name', '=', 'fb_url'));
                                        $fb_url_id = $fb_url_id[0]->id;
                                        $queries->update('settings', $fb_url_id, array(
                                            'value' => Output::getClean(Input::get('fburl'))
                                        ));

                                        $cache->store('facebook', Output::getClean(Input::get('fburl')));

                                        // Discord hook
                                        $discord_url_id = $queries->getWhere('settings', array('name', '=', 'discord_url'));
                                        $discord_url_id = $discord_url_id[0]->id;

                                        $queries->update('settings', $discord_url_id, array(
                                            'value' => Output::getClean(Input::get('discord_url'))
                                        ));

                                        $discord_hooks_id = $queries->getWhere('settings', array('name', '=', 'discord_hooks'));
                                        $discord_hooks_id = $discord_hooks_id[0]->id;

                                        if(isset($_POST['discord_hooks']))
                                            $hooks = $_POST['discord_hooks'];
                                        else
                                            $hooks = array();

                                        $queries->update('settings', $discord_hooks_id, array(
                                            'value' => json_encode($hooks)
                                        ));

                                        Log::getInstance()->log(Log::Action('admin/core/social'));

                                        $cache->setCache('discord_hook');
                                        $cache->store('events', $_POST['discord_hooks']);
                                        $cache->store('url', $_POST['discord_url']);

                                        Session::flash('social_media_links', '<div class="alert alert-success">' . $language->get('admin', 'successfully_updated') . '</div>');
                                    } else {
                                        // Invalid token
                                        Session::flash('social_media_links', '<div class="alert alert-danger">' . $language->get('general', 'invalid_token') . '</div>');
                                    }
                                }

                                // Show settings for social media links
                                // Get values from database
                                $youtube_url = $queries->getWhere('settings', array('name', '=', 'youtube_url'));
                                $twitter_url = $queries->getWhere('settings', array('name', '=', 'twitter_url'));
                                $twitter_style = $queries->getWhere('settings', array('name', '=', 'twitter_style'));
								$discord = $queries->getWhere('settings', array('name', '=', 'discord'));
                                $gplus_url = $queries->getWhere('settings', array('name', '=', 'gplus_url'));
                                $fb_url = $queries->getWhere('settings', array('name', '=', 'fb_url'));
                                $discord_url = $queries->getWhere('settings', array('name', '=', 'discord_url'));
                                $discord_hooks = $queries->getWhere('settings', array('name', '=', 'discord_hooks'));
                                $discord_hooks = json_decode($discord_hooks[0]->value, true);
                                ?>
                                <h4><?php echo $language->get('admin', 'social_media'); ?></h4>
                                <?php
                                if (Session::exists('social_media_links')) {
                                    echo Session::flash('social_media_links');
                                }
                                ?>
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="InputYoutube"><?php echo $language->get('admin', 'youtube_url'); ?></label>
                                        <input type="text" name="youtubeurl" class="form-control" id="InputYoutube"
                                               placeholder="<?php echo $language->get('admin', 'youtube_url'); ?>"
                                               value="<?php echo Output::getClean($youtube_url[0]->value); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="InputTwitter"><?php echo $language->get('admin', 'twitter_url'); ?></label>
                                        <input type="text" name="twitterurl" class="form-control" id="InputTwitter"
                                               placeholder="<?php echo $language->get('admin', 'twitter_url'); ?>"
                                               value="<?php echo Output::getClean($twitter_url[0]->value); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="InputTwitterStyle"><?php echo $language->get('admin', 'twitter_dark_theme'); ?></label>
                                        <input id="InputTwitterStyle" name="twitter_dark_theme" type="checkbox"
                                               class="js-switch"
                                               value="1" <?php if ($twitter_style[0]->value == 'dark') echo 'checked'; ?>/>
                                    </div>
                                    <div class="form-group">
                                        <label for="InputDiscord"><?php echo $language->get('admin', 'discord_id'); ?></label>
                                        <input type="text" name="discordid" class="form-control" id="InputDiscord"
                                               placeholder="<?php echo $language->get('admin', 'discord_id'); ?>"
                                               value="<?php echo Output::getClean($discord[0]->value); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="InputGPlus"><?php echo $language->get('admin', 'google_plus_url'); ?></label>
                                        <input type="text" name="gplusurl" class="form-control" id="InputGPlus"
                                               placeholder="<?php echo $language->get('admin', 'google_plus_url'); ?>"
                                               value="<?php echo Output::getClean($gplus_url[0]->value); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="InputFacebook"><?php echo $language->get('admin', 'facebook_url'); ?></label>
                                        <input type="text" name="fburl" class="form-control" id="InputFacebook"
                                               placeholder="<?php echo $language->get('admin', 'facebook_url'); ?>"
                                               value="<?php echo Output::getClean($fb_url[0]->value); ?>">
                                    </div>
                                    <h4><?php echo $language->get('admin', 'discord_hooks'); ?></h4>
                                    <div class="alert alert-info"><?php echo $language->get('admin', 'discord_hooks_info'); ?></div>
                                    <div class="form-group">
                                        <label for="InputDiscordHookURL"><?php echo $language->get('admin', 'discord_hook_url'); ?></label>
                                        <input type="text" class="form-control" name="discord_url" placeholder="<?php echo $language->get('admin', 'discord_hook_url'); ?>" value="<?php echo Output::getClean($discord_url[0]->value); ?>" id="InputDiscordHookURL">
                                    </div>
                                    <div class="form-group">
                                        <label for="InputDiscordHooks"><?php echo $language->get('admin', 'discord_hook_events'); ?></label>
                                        <select multiple class="form-control" name="discord_hooks[]" id="InputDiscordHooks">
                                            <?php foreach(HookHandler::getHooks() as $hook => $description){ ?>
                                            <option value="<?php echo Output::getClean($hook); ?>"<?php if(in_array(Output::getClean($hook), $discord_hooks)) echo ' selected'; ?>><?php echo Output::getClean($description); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                                    <input type="submit" class="btn btn-primary"
                                           value="<?php echo $language->get('general', 'submit'); ?>">
                                </form>
                                <?php
                                break;

                            case 'maintenance':
                                if(!$user->hasPermission('admincp.core.debugging')){
                                    Redirect::to(URL::build('/admin/core'));
                                    die();
                                }
                                // Maintenance mode settings
                                // Deal with input
                                if (Input::exists()) {
                                    if (Token::check(Input::get('token'))) {
                                        // Valid token
                                        // Validate message
                                        $validate = new Validate();
                                        $validation = $validate->check($_POST, array(
                                            'message' => array(
                                                'max' => 1024
                                            )
                                        ));

                                        if ($validation->passed()) {
                                            // Update database and cache
                                            // Is debug mode enabled or not?
                                            if (isset($_POST['enable_debugging']) && $_POST['enable_debugging'] == 1) $enabled = 1;
                                            else $enabled = 0;

                                            $debug_id = $queries->getWhere('settings', array('name', '=', 'error_reporting'));
                                            $debug_id = $debug_id[0]->id;
                                            $queries->update('settings', $debug_id, array(
                                                'value' => $enabled
                                            ));

                                            // Cache
                                            $cache->setCache('error_cache');
                                            $cache->store('error_reporting', $enabled);

                                            // Is maintenance enabled or not?
                                            if (isset($_POST['enable_maintenance']) && $_POST['enable_maintenance'] == 1) $enabled = 'true';
                                            else $enabled = 'false';

                                            $maintenance_id = $queries->getWhere('settings', array('name', '=', 'maintenance'));
                                            $maintenance_id = $maintenance_id[0]->id;
                                            $queries->update('settings', $maintenance_id, array(
                                                'value' => $enabled
                                            ));

                                            if (isset($_POST['message']) && !empty($_POST['message'])) $message = Input::get('message');
                                            else $message = 'Maintenance mode is enabled.';

                                            $maintenance_id = $queries->getWhere('settings', array('name', '=', 'maintenance_message'));
                                            $maintenance_id = $maintenance_id[0]->id;
                                            $queries->update('settings', $maintenance_id, array(
                                                'value' => Output::getClean($message)
                                            ));

                                            Log::getInstance()->log(Log::Action('admin/core/maintenance/update'));

                                            // Cache
                                            $cache->setCache('maintenance_cache');
                                            $cache->store('maintenance', array(
                                                'maintenance' => $enabled,
                                                'message' => Output::getClean($message)
                                            ));

                                            // Page load timer
                                            if (isset($_POST['enable_page_load_timer']) && $_POST['enable_page_load_timer'] == 1) $enabled = 1;
                                            else $enabled = 0;

                                            $load_id = $queries->getWhere('settings', array('name', '=', 'page_loading'));
                                            $load_id = $load_id[0]->id;
                                            $queries->update('settings', $load_id, array(
                                                'value' => $enabled
                                            ));

                                            // Cache
                                            $cache->setCache('page_load_cache');
                                            $cache->store('page_load', $enabled);

                                            // Reload to update debugging
                                            Redirect::to(URL::build('/admin/core/', 'view=maintenance'));
                                            die();

                                        } else $error = $language->get('admin', 'maintenance_message_max_1024');
                                    } else {
                                        // Invalid token
                                        $error = $language->get('general', 'invalid_token');
                                    }

                                    // Re-query cache for updated values
                                    $cache->setCache('maintenance_cache');
                                    $maintenance = $cache->retrieve('maintenance');

                                    $cache->setCache('page_load_cache');
                                    if($cache->isCached('page_load'))
                                        $page_loading = $cache->retrieve('page_load');
                                    else
                                        $page_loading = 0;
                                }
                                ?>
                                <h4 style="display:inline;"><?php echo $language->get('admin', 'debugging_and_maintenance'); ?></h4>
                                <?php if($user->hasPermission('admincp.errors')){ ?><span class="pull-right"><a class="btn btn-primary" href="<?php echo URL::build('/admin/core/', 'view=errors'); ?>"><?php echo $language->get('admin', 'error_logs'); ?></a></span><?php } ?>
                                <br /><br />

                                <form action="" method="post">
                                    <?php if (isset($error)) { ?>
                                        <div class="alert alert-danger"><?php echo $error; ?></div>
                                    <?php } ?>
                                    <div class="form-group">
                                        <label for="InputDebug"><?php echo $language->get('admin', 'enable_debug_mode'); ?></label>
                                        <input id="InputDebug" name="enable_debugging" type="checkbox" class="js-switch"
                                               value="1" <?php if (defined('DEBUGGING')) echo 'checked'; ?>/>
                                    </div>
                                    <div class="form-group">
                                        <label for="InputPageLoad"><?php echo $language->get('admin', 'enable_page_load_timer'); ?></label>
                                        <input id="InputPageLoad" name="enable_page_load_timer" type="checkbox" class="js-switch"
                                               value="1" <?php if($page_loading == '1') echo 'checked'; ?>/>
                                    </div>
                                    <div class="form-group">
                                        <label for="InputMaintenance"><?php echo $language->get('admin', 'enable_maintenance_mode'); ?></label>
                                        <input id="InputMaintenance" name="enable_maintenance" type="checkbox"
                                               class="js-switch"
                                               value="1" <?php if (isset($maintenance['maintenance']) && $maintenance['maintenance'] != 'false') echo 'checked'; ?>/>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputMaintenanceMessage"><?php echo $language->get('admin', 'maintenance_mode_message'); ?></label>
                                        <textarea style="width:100%" rows="10" name="message"
                                                  id="InputMaintenanceMessage"><?php echo Output::getPurified(htmlspecialchars_decode($maintenance['message'])); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                                        <input type="submit" value="<?php echo $language->get('general', 'submit'); ?>"
                                               class="btn btn-primary">
                                    </div>
                                </form>
                                <?php
                                break;

                            case 'email':
                                if(!$user->hasPermission('admincp.core.emails')){
                                    Redirect::to(URL::build('/admin/core'));
                                    die();
                                }
                                if (isset($_GET['action'])) {
                                    if ($_GET['action'] == 'errors') {
                                        if (isset($_GET['do'])) {
                                            if ($_GET['do'] == 'purge') {
                                                // Purge all errors
                                                try {
                                                    $queries->delete('email_errors', array('id', '<>', 0));
                                                } catch (Exception $e) {
                                                    // Error
                                                }

                                                Redirect::to(URL::build('/admin/core/', 'view=email&action=errors'));
                                                die();

                                            } else if ($_GET['do'] == 'delete' && isset($_GET['id']) && is_numeric($_GET['id'])) {
                                                try {
                                                    $queries->delete('email_errors', array('id', '=', $_GET['id']));
                                                } catch (Exception $e) {
                                                    // Error
                                                }

                                                Redirect::to(URL::build('/admin/core/', 'view=email&action=errors'));
                                                die();

                                            } else if ($_GET['do'] == 'view' && isset($_GET['id']) && is_numeric($_GET['id'])) {
                                                // Check the error exists
                                                $error = $queries->getWhere('email_errors', array('id', '=', $_GET['id']));
                                                if (!count($error)) {
                                                    Redirect::to(URL::build('/admin/core/', 'view=email&action=errors'));
                                                    die();
                                                }
                                                $error = $error[0];

                                                // Display error
                                                echo '<h4 style="display:inline;">' . $language->get('admin', 'email_errors') . '</h4>';
                                                echo '<span class="pull-right"><a href="' . URL::build('/admin/core/', 'view=email&amp;action=errors') . '" class="btn btn-primary">' . $language->get('general', 'back') . '</a></span>';
                                                ?>
                                                <br/><br/>
                                                <strong><?php echo $language->get('admin', 'viewing_email_error'); ?></strong>
                                                <hr/>
                                                <strong><?php echo $language->get('user', 'username'); ?>
                                                    :</strong> <?php echo Output::getClean($user->idToName($error->user_id)); ?>
                                                <br/>
                                                <strong><?php echo $language->get('general', 'date'); ?>
                                                    :</strong> <?php echo date('d M Y, H:i', $error->at); ?><br/>
                                                <strong><?php echo $language->get('admin', 'type'); ?>:</strong> <?php
                                                switch ($error->type) {
                                                    case 1:
                                                        echo $language->get('admin', 'registration_email');
                                                        break;
                                                    case 2:
                                                        echo $language->get('admin', 'contact_email');
                                                        break;
                                                    case 3:
                                                        echo $language->get('admin', 'forgot_password_email');
                                                        break;
                                                    case 4:
                                                        echo $language->get('admin', 'api_registration_email');
                                                        break;
                                                    default:
                                                        echo $language->get('admin', 'unknown');
                                                        break;
                                                }
                                                ?><br/><br/>
                                                <div class="card">
                                                    <div class="card-block">
                                                        <?php echo Output::getPurified($error->content); ?>
                                                    </div>
                                                </div>
                                                <hr/>
                                                <h4><?php echo $language->get('general', 'actions'); ?></h4>
                                                <?php
                                                if ($error->type == 1) {
                                                    $user_validated = $queries->getWhere('users', array('id', '=', $error->user_id));
                                                    if (count($user_validated)) {
                                                        $user_validated = $user_validated[0];
                                                        if ($user_validated->active == 0) {
                                                            ?>
                                                            <a href="<?php echo URL::build('/admin/users/', 'user=' . $error->user_id . '&amp;action=validate'); ?>"
                                                               class="btn btn-secondary"><?php echo $language->get('admin', 'validate_user'); ?></a>
                                                            <?php
                                                        }
                                                    }
                                                } else if($error->type == 4){
                                                    $user_error = $queries->getWhere('users', array('id', '=', $error->user_id));
                                                    if(count($user_error)){
                                                        $user_error = $user_error[0];
                                                        if($user_error->active == 0 && !is_null($user_error->reset_code)){
                                                            ?>
                                                            <div class="alert alert-info"><?php echo str_replace('{x}', rtrim(Util::getSelfURL(), '/') . URL::build('/complete_signup/', 'c=' . Output::getClean($user_error->reset_code)), $language->get('admin', 'link_to_complete_registration')); ?></div>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                                <a href="<?php echo URL::build('/admin/core/', 'view=email&amp;action=errors&amp;do=delete&amp;id=' . $error->id); ?>"
                                                   class="btn btn-warning"
                                                   onclick="return confirm('<?php echo $language->get('admin', 'confirm_email_error_deletion'); ?>');"><?php echo $language->get('admin', 'delete_email_error'); ?></a>
                                                <?php
                                            } else {
                                                Redirect::to(URL::build('/admin/core/', 'view=email&action=errors'));
                                                die();
                                            }
                                        } else {
                                            // Display all errors
                                            $errors = $queries->orderWhere('email_errors', 'id <> 0', 'at', 'DESC');

                                            // Get page
                                            if (isset($_GET['p'])) {
                                                if (!is_numeric($_GET['p'])) {
                                                    Redirect::to(URL::build('/admin/core/', 'view=email&action=errors'));
                                                    die();
                                                } else {
                                                    if ($_GET['p'] == 1) {
                                                        // Avoid bug in pagination class
                                                        Redirect::to(URL::build('/admin/core/', 'view=email&action=errors'));
                                                        die();
                                                    }
                                                    $p = $_GET['p'];
                                                }
                                            } else {
                                                $p = 1;
                                            }

                                            // Pagination
                                            $paginator = new Paginator();

                                            $results = $paginator->getLimited($errors, 10, $p, count($errors));
                                            $pagination = $paginator->generate(7, URL::build('/admin/core/', 'view=email&action=errors&'));

                                            echo '<h4 style="display:inline;">' . $language->get('admin', 'email_errors') . '</h4>';
                                            echo '<span class="pull-right"><a href="' . URL::build('/admin/core/', 'view=email') . '" class="btn btn-primary">' . $language->get('general', 'back') . '</a></span>';
                                            ?>
                                            <br/><br/>
                                            <?php if (count($errors)) { ?>
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th><?php echo $language->get('admin', 'type'); ?></th>
                                                        <th><?php echo $language->get('general', 'date'); ?></th>
                                                        <th><?php echo $language->get('user', 'username'); ?></th>
                                                        <th><?php echo $language->get('general', 'actions'); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    for ($n = 0; $n < count($results->data); $n++) {
                                                        switch ($results->data[$n]->type) {
                                                            case 1:
                                                                $type = $language->get('admin', 'registration_email');
                                                                break;
                                                            case 2:
                                                                $type = $language->get('admin', 'contact_email');
                                                                break;
                                                            case 3:
                                                                $type = $language->get('admin', 'forgot_password_email');
                                                                break;
                                                            default:
                                                                $type = $language->get('admin', 'unknown');
                                                                break;
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $type; ?></td>
                                                            <td><?php echo date('d M Y, H:i', $results->data[$n]->at); ?></td>
                                                            <td><?php echo Output::getClean($user->idToName($results->data[$n]->user_id)); ?></td>
                                                            <td>
                                                                <a href="<?php echo URL::build('/admin/core/', 'view=email&amp;action=errors&amp;do=view&amp;id=' . $results->data[$n]->id); ?>"
                                                                   class="btn btn-info btn-sm"><i
                                                                            class="fa fa-search fa-fw"></i></a> <a
                                                                        href="<?php echo URL::build('/admin/core/', 'view=email&amp;action=errors&amp;do=delete&amp;id=' . $results->data[$n]->id); ?>"
                                                                        class="btn btn-warning btn-sm"
                                                                        onclick="return confirm('<?php echo $language->get('admin', 'confirm_email_error_deletion'); ?>')"><i
                                                                            class="fa fa-trash fa-fw"></i></a></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                                <?php
                                                echo $pagination;
                                            }
                                        }
                                    } else if ($_GET['action'] == 'test') {
                                        echo '<h4 style="display:inline;">' . $language->get('admin', 'send_test_email') . '</h4>';
                                        echo '<span class="pull-right"><a href="' . URL::build('/admin/core/', 'view=email') . '" class="btn btn-primary">' . $language->get('general', 'back') . '</a></span>';
                                        Log::getInstance()->log(Log::Action('admin/core/email/test'));
                                        if (isset($_GET['do']) && $_GET['do'] == 'send') {
                                            $php_mailer = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
                                            $php_mailer = $php_mailer[0]->value;

                                            if ($php_mailer == '1') {
                                                // PHP Mailer
                                                // HTML to display in message
                                                $path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'templates', $template, 'email', 'register.html'));
                                                $html = file_get_contents($path);

                                                $html = SITE_NAME . ' - Test email successful!';;

                                                $email = array(
                                                    'to' => array('email' => Output::getClean($user->data()->email), 'name' => Output::getClean($user->data()->nickname)),
                                                    'subject' => SITE_NAME . ' - Test Email',
                                                    'message' => $html
                                                );

                                                $sent = Email::send($email, 'mailer');

                                                if (isset($sent['error']))
                                                    // Error
                                                    $error = $sent['error'];

                                            } else {
                                                // PHP mail function
                                                $siteemail = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
                                                $siteemail = $siteemail[0]->value;

                                                $to = $user->data()->email;
                                                $subject = SITE_NAME . ' - Test Email';

                                                $message = SITE_NAME . ' - Test email successful!';

                                                $headers = 'From: ' . $siteemail . "\r\n" .
                                                    'Reply-To: ' . $siteemail . "\r\n" .
                                                    'X-Mailer: PHP/' . phpversion() . "\r\n" .
                                                    'MIME-Version: 1.0' . "\r\n" . 
                                                    'Content-type: text/html; charset=UTF-8' . "\r\n";

                                                $email = array(
                                                    'to' => $to,
                                                    'subject' => $subject,
                                                    'message' => $message,
                                                    'headers' => $headers
                                                );

                                                $sent = Email::send($email, 'php');

                                                if (isset($sent['error']))
                                                    // Error
                                                    $error = $sent['error'];
                                            }
                                            echo '<br /><br />';
                                            if (isset($error)) {
                                                ?>
                                                <div class="alert alert-danger">
                                                    <strong><?php echo $language->get('admin', 'test_email_error'); ?></strong>
                                                    <p><?php echo Output::getClean($error); ?></p></div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="alert alert-success"><?php echo $language->get('admin', 'test_email_success'); ?></div>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <br/><br/>
                                            <div class="alert alert-info"><?php echo str_replace('{x}', Output::getClean($user->data()->email), $language->get('admin', 'send_test_email_info')); ?></div>
                                            <hr/>
                                            <a class="btn btn-primary"
                                               href="<?php echo URL::build('/admin/core/', 'view=email&amp;action=test&do=send'); ?>"><?php echo $language->get('admin', 'send'); ?></a>
                                            <?php
                                        }
                                    }
                                } else {
                                    // Handle input
                                    if (Input::exists()) {
                                        if (Token::check(Input::get('token'))) {
                                            if (isset($_POST['enable_mailer']) && $_POST['enable_mailer'] == 1)
                                                $mailer = '1';
                                            else
                                                $mailer = '0';

                                            $php_mailer = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
                                            $php_mailer = $php_mailer[0]->id;

                                            $queries->update('settings', $php_mailer, array(
                                                'value' => $mailer
                                            ));

                                            Log::getInstance()->log(Log::Action('admin/core/email/update'));

                                            if (!empty($_POST['email'])) {
                                                $outgoing_email = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
                                                $outgoing_email = $outgoing_email[0]->id;

                                                $queries->update('settings', $outgoing_email, array(
                                                    'value' => Output::getClean($_POST['email'])
                                                ));
                                            }

                                            // Update config
                                            $config_path = 'core' . DIRECTORY_SEPARATOR . 'email.php';
                                            if (file_exists($config_path)) {
                                                if (is_writable($config_path)) {
                                                    require(ROOT_PATH . '/core/email.php');
                                                    // Build new email config
                                                    $config = '<?php' . PHP_EOL .
                                                        '$GLOBALS[\'email\'] = array(' . PHP_EOL .
                                                        '    \'email\' => \'' . str_replace('\'', '\\\'', (!empty($_POST['email']) ? $_POST['email'] : $GLOBALS['email']['email'])) . '\',' . PHP_EOL .
                                                        '    \'username\' => \'' . str_replace('\'', '\\\'', (!empty($_POST['username']) ? $_POST['username'] : $GLOBALS['email']['username'])) . '\',' . PHP_EOL .
                                                        '    \'password\' => \'' . str_replace('\'', '\\\'', ((!empty($_POST['password'])) ? $_POST['password'] : $GLOBALS['email']['password'])) . '\',' . PHP_EOL .
                                                        '    \'name\' => \'' . str_replace('\'', '\\\'', (!empty($_POST['name']) ? $_POST['name'] : $GLOBALS['email']['name'])) . '\',' . PHP_EOL .
                                                        '    \'host\' => \'' . str_replace('\'', '\\\'', (!empty($_POST['host']) ? $_POST['host'] : $GLOBALS['email']['host'])) . '\',' . PHP_EOL .
                                                        '    \'port\' => ' . str_replace('\'', '\\\'', (!empty($_POST['port']) ? $_POST['port'] : $GLOBALS['email']['port'])) . ',' . PHP_EOL .
                                                        '    \'secure\' => \'' . str_replace('\'', '\\\'', $GLOBALS['email']['secure']) . '\',' . PHP_EOL .
                                                        '    \'smtp_auth\' => ' . (($GLOBALS['email']['smtp_auth']) ? 'true' : 'false') . PHP_EOL .
                                                        ');';

                                                    $file = fopen($config_path, 'w');
                                                    fwrite($file, $config);
                                                    fclose($file);

                                                } else {
                                                    // Permissions incorrect
                                                    $error = $language->get('admin', 'unable_to_write_email_config');
                                                }
                                            } else {
                                                // Create one now
                                                if (is_writable(ROOT_PATH . DIRECTORY_SEPARATOR . 'core')) {
                                                    // Build new email config
                                                    $config = '<?php' . PHP_EOL .
                                                        '$GLOBALS[\'email\'] = array(' . PHP_EOL .
                                                        '    \'email\' => \'' . str_replace('\'', '\\\'', (!empty($_POST['email']) ? $_POST['email'] : '')) . '\',' . PHP_EOL .
                                                        '    \'username\' => \'' . str_replace('\'', '\\\'', (!empty($_POST['username']) ? $_POST['username'] : '')) . '\',' . PHP_EOL .
                                                        '    \'password\' => \'' . str_replace('\'', '\\\'', ((!empty($_POST['password'])) ? $_POST['password'] : '')) . '\',' . PHP_EOL .
                                                        '    \'name\' => \'' . str_replace('\'', '\\\'', (!empty($_POST['name']) ? $_POST['name'] : '')) . '\',' . PHP_EOL .
                                                        '    \'host\' => \'' . str_replace('\'', '\\\'', (!empty($_POST['host']) ? $_POST['host'] : '')) . '\',' . PHP_EOL .
                                                        '    \'port\' => \'' . str_replace('\'', '\\\'', (!empty($_POST['port']) ? $_POST['host'] : 587)) . ',' . PHP_EOL .
                                                        '    \'secure\' => \'tls\',' . PHP_EOL .
                                                        '    \'smtp_auth\' => true' . PHP_EOL .
                                                        ');';
                                                    $file = fopen($config_path, 'w');
                                                    fwrite($file, $config);
                                                    fclose($file);
                                                } else {
                                                    $error = $language->get('admin', 'unable_to_write_email_config');
                                                }
                                            }
											
											if(!isset($error)){
												// Redirect to refresh config values
												Redirect::to(URL::build('/admin/core/', 'view=email'));
												die();
											}
                                        } else
                                            $error = $language->get('general', 'invalid_token');
                                    }

                                    echo '<h4 style="display:inline;">' . $language->get('admin', 'emails') . '</h4>';
                                    echo '<span class="pull-right"><a class="btn btn-info" href="' . URL::build('/admin/core/', 'view=email&amp;action=test') . '">' . $language->get('admin', 'send_test_email') . '</a> <a href="' . URL::build('/admin/core/', 'view=email&amp;action=errors') . '" class="btn btn-primary">' . $language->get('admin', 'email_errors') . '</a></span>';

                                    if (isset($error))
                                        echo '<div class="alert alert-danger">' . $error . '</div>';

                                    $php_mailer = $queries->getWhere('settings', array('name', '=', 'phpmailer'));
                                    $php_mailer = $php_mailer[0]->value;

                                    $outgoing_email = $queries->getWhere('settings', array('name', '=', 'outgoing_email'));
                                    $outgoing_email = $outgoing_email[0]->value;

                                    require(ROOT_PATH . '/core/email.php');
                                    ?>
                                    <br/><br/>
                                    <form action="" method="post">
                                        <div class="form-group">
                                            <label for="inputMailer"><?php echo $language->get('admin', 'enable_mailer'); ?></label>
                                            <span class="badge badge-info"><i class="fa fa-question-circle"
                                                                              data-container="body"
                                                                              data-toggle="popover" data-placement="top"
                                                                              title="<?php echo $language->get('general', 'info'); ?>"
                                                                              data-content="<?php echo $language->get('admin', 'enable_mailer_help'); ?>"></i></span>
                                            <input type="hidden" name="enable_mailer" value="0">
                                            <input id="inputMailer" name="enable_mailer" type="checkbox"
                                                   class="js-switch"
                                                   value="1"<?php if ($php_mailer == '1') { ?> checked<?php } ?> />
                                        </div>
                                        <div class="form-group">
                                            <label for="InputOutgoingEmail"><?php echo $language->get('admin', 'outgoing_email'); ?></label>
                                            <span class="badge badge-info"><i class="fa fa-question-circle"
                                                                              data-container="body"
                                                                              data-toggle="popover" data-placement="top"
                                                                              title="<?php echo $language->get('general', 'info'); ?>"
                                                                              data-content="<?php echo $language->get('admin', 'outgoing_email_info'); ?>"></i></span>
                                            <input type="text" id="InputOutgoingEmail" name="email"
                                                   value="<?php echo Output::getClean($outgoing_email); ?>"
                                                   class="form-control">
                                        </div>
                                        <hr/>
                                        <div class="alert alert-info">
                                            <?php echo $language->get('admin', 'mailer_settings_info'); ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputUsername"><?php echo $language->get('user', 'username'); ?></label>
                                            <input class="form-control" type="text" name="username"
                                                   value="<?php if (!empty($GLOBALS['email']['username'])) echo Output::getClean($GLOBALS['email']['username']); ?>"
                                                   id="inputUsername">
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword"><?php echo $language->get('user', 'password'); ?></label>
                                            <span class="badge badge-info"><i class="fa fa-question-circle"
                                                                              data-container="body"
                                                                              data-toggle="popover" data-placement="top"
                                                                              title="<?php echo $language->get('general', 'info'); ?>"
                                                                              data-content="<?php echo $language->get('admin', 'email_password_hidden'); ?>"></i></span>
                                            <input class="form-control" type="password" name="password"
                                                   id="inputPassword">
                                        </div>
                                        <div class="form-group">
                                            <label for="inputName"><?php echo $language->get('admin', 'name'); ?></label>
                                            <input class="form-control" type="text" name="name"
                                                   value="<?php if (!empty($GLOBALS['email']['name'])) echo Output::getClean($GLOBALS['email']['name']); ?>"
                                                   id="inputName">
                                        </div>
                                        <div class="form-group">
                                            <label for="inputHost"><?php echo $language->get('admin', 'host'); ?></label>
                                            <input class="form-control" type="text" name="host"
                                                   value="<?php if (!empty($GLOBALS['email']['host'])) echo Output::getClean($GLOBALS['email']['host']); ?>"
                                                   id="inputHost">
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPort"><?php echo $language->get('admin', 'email_port'); ?></label>
                                            <input class="form-control" type="text" name="port"
                                                   value="<?php if (!empty($GLOBALS['email']['port'])) echo Output::getClean(isset($GLOBALS['email']['port']) ? $GLOBALS['email']['port'] : 587); ?>"
                                                   id="inputPort">
                                        </div>
                                        <hr/>
                                        <div class="form-group">
                                            <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                                            <input type="submit" class="btn btn-primary"
                                                   value="<?php echo $language->get('general', 'submit'); ?>">
                                        </div>
                                    </form>
                                    <?php
                                }

                                break;

                            case 'terms':
                                if(!$user->hasPermission('admincp.core.terms')){
                                    Redirect::to(URL::build('/admin/core'));
                                    die();
                                }
                                if (Input::exists()) {
                                    if (Token::check(Input::get('token'))) {
                                        $validate = new Validate();
                                        $validation = $validate->check($_POST, array(
                                            'privacy' => array(
                                                'required' => true,
                                                'max' => 2048
                                            ),
                                            'terms' => array(
                                                'required' => true,
                                                'max' => 2048
                                            )
                                        ));

                                        if ($validation->passed()) {
                                            try {
                                                $privacy_id = $queries->getWhere('settings', array('name', '=', 'privacy_policy'));
                                                $privacy_id = $privacy_id[0]->id;

                                                $queries->update('settings', $privacy_id, array(
                                                    'value' => Input::get('privacy')
                                                ));

                                                $terms_id = $queries->getWhere('settings', array('name', '=', 't_and_c_site'));
                                                $terms_id = $terms_id[0]->id;

                                                $queries->update('settings', $terms_id, array(
                                                    'value' => Input::get('terms')
                                                ));

                                                Log::getInstance()->log(Log::Action('admin/core/term'));
                                                $success = $language->get('admin', 'terms_updated');
                                            } catch (Exception $e) {
                                                $error = $e->getMessage();
                                            }
                                        } else
                                            $error = $language->get('admin', 'terms_error');

                                    } else
                                        $error = $language->get('general', 'invalid_token');
                                }

                                $site_terms = $queries->getWhere('settings', array('name', '=', 't_and_c_site'));
                                $site_terms = $site_terms[0]->value;

                                $site_privacy = $queries->getWhere('settings', array('name', '=', 'privacy_policy'));
                                $site_privacy = $site_privacy[0]->value;
                                ?>
                                <h4><?php echo $language->get('admin', 'privacy_and_terms'); ?></h4>

                                <form action="" method="post">
                                    <?php if (isset($error)) { ?>
                                        <div class="alert alert-danger"><?php echo $error; ?></div>
                                    <?php } else if (isset($success)) { ?>
                                        <div class="alert alert-success"><?php echo $success; ?></div>
                                    <?php } ?>

                                    <div class="form-group">
                                        <label for="InputPrivacy"><?php echo $language->get('general', 'privacy_policy'); ?></label>
                                        <textarea style="width:100%" rows="10" name="privacy"
                                                  id="InputPrivacy"><?php echo Output::getPurified($site_privacy); ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="InputTerms"><?php echo $language->get('user', 'terms_and_conditions'); ?></label>
                                        <textarea style="width:100%" rows="10" name="terms"
                                                  id="InputTerms"><?php echo Output::getPurified($site_terms); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                                        <input type="submit" value="<?php echo $language->get('general', 'submit'); ?>"
                                               class="btn btn-primary">
                                    </div>
                                </form>
                                <?php
                                break;

                            case 'avatars':
                                if(!$user->hasPermission('admincp.core.avatars')){
                                    Redirect::to(URL::build('/admin/core'));
                                    die();
                                }
                                // Input
                                if (Input::exists()) {
                                    if (Token::check(Input::get('token'))) {
                                        if (isset($_POST['avatar_source'])) {
                                            // Custom avatars?
                                            if (isset($_POST['custom_avatars']) && $_POST['custom_avatars'] == 1)
                                                $custom_avatars = 1;
                                            else
                                                $custom_avatars = 0;

                                            try {
                                                $custom_avatars_id = $queries->getWhere('settings', array('name', '=', 'user_avatars'));
                                                $custom_avatars_id = $custom_avatars_id[0]->id;
                                                $queries->update('settings', $custom_avatars_id, array('value' => $custom_avatars));

                                                $default_avatar_type = $queries->getWhere('settings', array('name', '=', 'default_avatar_type'));
                                                $default_avatar_type = $default_avatar_type[0]->id;
                                                $queries->update('settings', $default_avatar_type, array('value' => Input::get('default_avatar')));

                                                $mc_avatar_source = $queries->getWhere('settings', array('name', '=', 'avatar_site'));
                                                $mc_avatar_source = $mc_avatar_source[0]->id;
                                                $queries->update('settings', $mc_avatar_source, array('value' => Input::get('avatar_source')));

                                                $mc_avatar_perspective = $queries->getWhere('settings', array('name', '=', 'avatar_type'));
                                                $mc_avatar_perspective = $mc_avatar_perspective[0]->id;
                                                $queries->update('settings', $mc_avatar_perspective, array('value' => Input::get('avatar_perspective')));

                                                $cache->setCache('avatar_settings_cache');
                                                $cache->store('custom_avatars', $custom_avatars);
                                                $cache->store('default_avatar_type', Input::get('default_avatar'));
                                                $cache->store('avatar_source', Input::get('avatar_source'));
                                                $cache->store('avatar_perspective', Input::get('avatar_perspective'));

                                            } catch (Exception $e) {
                                                $error = $e->getMessage();
                                            }
                                        } else if (isset($_POST['avatar'])) {
                                            // Selecting a new default avatar
                                            try {
                                                $default_avatar = $queries->getWhere('settings', array('name', '=', 'custom_default_avatar'));
                                                $default_avatar = $default_avatar[0]->id;
                                                $queries->update('settings', $default_avatar, array('value' => Input::get('avatar')));

                                                $cache->setCache('avatar_settings_cache');
                                                $cache->store('default_avatar_image', Input::get('avatar'));

                                            } catch (Exception $e) {
                                                $error = $e->getMessage();
                                            }
                                        }

                                        Log::getInstance()->log(Log::Action('admin/core/avatar'));

                                        $success = $language->get('admin', 'avatar_settings_updated_successfully');
                                    } else
                                        $error = $language->get('general', 'invalid_token');
                                }

                                // Get setting values
                                $custom_avatars = $queries->getWhere('settings', array('name', '=', 'user_avatars'));
                                $custom_avatars = $custom_avatars[0]->value;

                                $default_avatar_type = $queries->getWhere('settings', array('name', '=', 'default_avatar_type'));
                                $default_avatar_type = $default_avatar_type[0]->value;

                                $mc_avatar_source = $queries->getWhere('settings', array('name', '=', 'avatar_site'));
                                $mc_avatar_source = $mc_avatar_source[0]->value;

                                $mc_avatar_perspective = $queries->getWhere('settings', array('name', '=', 'avatar_type'));
                                $mc_avatar_perspective = $mc_avatar_perspective[0]->value;
                                ?>
                                <h4><?php echo $language->get('admin', 'avatars'); ?></h4>

                                <?php if (isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
                                <?php if (isset($success)) echo '<div class="alert alert-success">' . $success . '</div>'; ?>

                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="inputCustomAvatars"><?php echo $language->get('admin', 'allow_custom_avatars'); ?></label>
                                        <input type="hidden" name="custom_avatars" value="0">
                                        <input id="inputCustomAvatars" name="custom_avatars" type="checkbox"
                                               class="js-switch"
                                               value="1"<?php if ($custom_avatars == '1') { ?> checked<?php } ?> />
                                    </div>
                                    <div class="form-group">
                                        <label for="inputDefaultAvatar"><?php echo $language->get('admin', 'default_avatar'); ?></label>
                                        <select class="form-control" name="default_avatar" id="inputDefaultAvatar">
                                            <option value="minecraft"<?php if ($default_avatar_type == 'minecraft') echo ' selected'; ?>><?php echo $language->get('admin', 'minecraft_avatar'); ?></option>
                                            <option value="custom"<?php if ($default_avatar_type == 'custom') echo ' selected'; ?>><?php echo $language->get('admin', 'custom_avatar'); ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputMinecraftAvatarSource"><?php echo $language->get('admin', 'minecraft_avatar_source'); ?></label>
                                        <select class="form-control" name="avatar_source"
                                                id="inputMinecraftAvatarSource">
                                            <option value="cravatar"<?php if ($mc_avatar_source == 'cravatar') echo ' selected'; ?>>
                                                cravatar.eu
                                            </option>
                                            <option value="crafatar"<?php if ($mc_avatar_source == 'crafatar') echo ' selected'; ?>>
                                                crafatar.com
                                            </option>
                                            <option value="nameless"<?php if ($mc_avatar_source == 'nameless') echo ' selected'; ?>><?php echo $language->get('admin', 'built_in_avatars'); ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputAvatarPerspective"><?php echo $language->get('admin', 'minecraft_avatar_perspective'); ?></label>
                                        <select class="form-control" name="avatar_perspective"
                                                id="inputAvatarPerspective">
                                            <option value="face"<?php if ($mc_avatar_perspective == 'avatar' || $mc_avatar_perspective == 'helmavatar') echo ' selected'; ?>><?php echo $language->get('admin', 'face'); ?></option>
                                            <option value="head"<?php if ($mc_avatar_perspective == 'head') echo ' selected'; ?>><?php echo $language->get('admin', 'head'); ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                                        <input type="submit" class="btn btn-primary"
                                               value="<?php echo $language->get('general', 'submit'); ?>">
                                    </div>
                                </form>
                                <h5><?php echo $language->get('admin', 'default_avatar'); ?></h5>
                                <button class="btn btn-primary" data-toggle="modal"
                                        data-target="#uploadModal"><?php echo $language->get('admin', 'upload_new_image'); ?></button>
                                <br/><br/>

                                <form action="" method="post" style="display:inline;">
                                    <label for="inputAvatar"><?php echo $language->get('admin', 'select_default_avatar'); ?></label>
                                    <select name="avatar" class="image-picker show-html">
                                        <?php
                                        $image_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'avatars', 'defaults'));
                                        $images = scandir($image_path);

                                        // Only display jpeg, png, jpg, gif
                                        $allowed_exts = array('gif', 'png', 'jpg', 'jpeg');

                                        foreach ($images as $image) {
                                            $ext = pathinfo($image, PATHINFO_EXTENSION);
                                            if (!in_array($ext, $allowed_exts)) {
                                                continue;
                                            }
                                            $count = 1;
                                            ?>
                                            <option data-img-src="<?php echo((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/'); ?>uploads/avatars/defaults/<?php echo Output::getClean($image); ?>"
                                                    value="<?php echo Output::getClean($image); ?>" <?php if ($default_avatar_image == ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'uploads/avatars/defaults/' . Output::getClean($image)) echo 'selected'; ?>><?php echo Output::getClean($image); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <?php if (!isset($count)) echo '<strong>' . $language->get('admin', 'no_avatars_available') . '</strong>'; else { ?>
                                        <div class="form-group">
                                            <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                                            <input type="submit" class="btn btn-primary"
                                                   value="<?php echo $language->get('general', 'submit'); ?>">
                                        </div>
                                    <?php } ?>
                                </form>

                                <!-- Modal -->
                                <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog"
                                     aria-labelledby="uploadModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title"
                                                    id="uploadModalLabel"><?php echo $language->get('admin', 'upload_new_image'); ?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Upload modal -->
                                                <form action="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/includes/image_upload.php"
                                                      class="dropzone" id="upload_avatar_dropzone">
                                                    <div class="dz-message" data-dz-message>
                                                        <span><?php echo $language->get('admin', 'drag_files_here'); ?></span>
                                                    </div>
                                                    <input type="hidden" name="token"
                                                           value="<?php echo Token::get(); ?>">
                                                    <input type="hidden" name="type" value="default_avatar">
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                        onclick="location.reload();"
                                                        data-dismiss="modal"><?php echo $language->get('general', 'cancel'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                break;

                            case 'navigation':
                                if(!$user->hasPermission('admincp.core.navigation')){
                                    Redirect::to(URL::build('/admin/core'));
                                    die();
                                }
                                // Maintenance mode settings
                                // Deal with input
                                if(Input::exists()){
                                    if(Token::check(Input::get('token'))){
                                        // Valid token
                                        // Update cache
                                        $cache->setCache('navbar_order');
                                        if(isset($_POST['inputOrder']) && count($_POST['inputOrder'])) {
                                            foreach($_POST['inputOrder'] as $key => $item){
                                                if(is_numeric($item) && $item > 0){
                                                    $cache->store($key . '_order', $item);
                                                }
                                            }
                                        }
										
										// Icons
                                        $cache->setCache('navbar_icons');
                                        if(isset($_POST['inputIcon']) && count($_POST['inputIcon'])){
                                            foreach($_POST['inputIcon'] as $key => $item){
                                                if(is_numeric($key)){
                                                    // Custom page?
                                                    $custom_page = $queries->getWhere('custom_pages', array('id', '=', $key));
                                                    if(count($custom_page)){
                                                        $queries->update('custom_pages', $key, array(
                                                            'icon' => $item
                                                        ));
                                                    }
                                                }
                                                $cache->store($key . '_icon', $item);
                                            }
                                        }
										
										Log::getInstance()->log(Log::Action('admin/core/nav'));

                                        // Reload to update info
                                        Redirect::to(URL::build('/admin/core/', 'view=navigation'));
                                        die();
                                    } else {
                                        // Invalid token
                                        $error = $language->get('general', 'invalid_token');
                                    }
                                }
                                ?>
                                <h4><?php echo $language->get('admin', 'navigation'); ?></h4>

                                <form action="" method="post">
                                    <div class="alert alert-info"><?php echo $language->get('admin', 'navbar_order_instructions'); ?><hr /><?php echo $language->get('admin', 'navbar_icon_instructions'); ?></div>
                                    <?php
                                    // Display fields for each page
                                    $nav_items = $navigation->returnNav('top');
                                    foreach($nav_items as $key => $item){
										echo '<strong>' . Output::getClean($item['title']) . '</strong>';
                                        ?>
                                    <div class="form-group">
                                        <label for="input<?php echo Output::getClean($item['title']); ?>"><?php echo $language->get('admin', 'navbar_order'); ?></label>
                                        <input type="number" min="1" class="form-control" id="input<?php echo Output::getClean($item['title']); ?>" name="inputOrder[<?php echo ((isset($item['custom']) && is_numeric($item['custom'])) ? $item['custom'] : Output::getClean($key)); ?>]" value="<?php echo Output::getClean($item['order']); ?>">
                                    </div>
									<div class="form-group">
                                        <label for="input<?php echo Output::getClean($item['title']); ?>Icon"><?php echo $language->get('admin', 'navbar_icon'); ?></label>
                                        <input type="text" class="form-control" id="input<?php echo Output::getClean($item['title']); ?>Icon" name="inputIcon[<?php echo ((isset($item['custom']) && is_numeric($item['custom'])) ? $item['custom'] : Output::getClean($key)); ?>]" value="<?php echo Output::getClean($item['icon']); ?>">
                                    </div>
                                    <?php
										if(isset($item['items']) && count($item['items'])){
                                            echo '<strong>' . Output::getClean($item['title']) . ' &raquo; ' . $language->get('admin', 'dropdown_items') . '</strong><br />';
                                            foreach($item['items'] as $dropdown_key => $dropdown_item){
                                                echo '<strong>' . Output::getClean($dropdown_item['title']) . '</strong>';
                                                ?>
                                                <!--<div class="form-group">
                                                    <label for="input<?php echo Output::getClean($dropdown_item['title']); ?>"><?php echo $language->get('admin', 'navbar_order'); ?></label>
                                                    <input type="number" min="1" class="form-control" id="input<?php echo Output::getClean($dropdown_item['title']); ?>" name="inputOrder[<?php echo ((isset($dropdown_item['custom']) && is_numeric($dropdown_item['custom'])) ? $dropdown_item['custom'] : Output::getClean($dropdown_key)); ?>]" value="<?php echo Output::getClean($dropdown_item['order']); ?>">
                                                </div>-->
                                                <div class="form-group">
                                                    <label for="input<?php echo Output::getClean($dropdown_item['title']); ?>Icon"><?php echo $language->get('admin', 'navbar_icon'); ?></label>
                                                    <input type="text" class="form-control" id="input<?php echo Output::getClean($dropdown_item['title']); ?>Icon" name="inputIcon[<?php echo ((isset($dropdown_item['custom']) && is_numeric($dropdown_item['custom'])) ? $dropdown_item['custom'] : Output::getClean($dropdown_key)); ?>]" value="<?php echo Output::getClean($dropdown_item['icon']); ?>">
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="form-group">
                                        <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                                        <input type="submit" value="<?php echo $language->get('general', 'submit'); ?>"
                                               class="btn btn-primary">
                                    </div>
                                </form>
                                <?php
                                break;

                            case 'errors':
                              if(!$user->hasPermission('admincp.errors')){
                                Redirect::to(URL::build('/admin/core'));
                                die();
                              }
                              echo '<h4 style="display:inline;">' . $language->get('admin', 'error_logs') . '</h4><span class="pull-right"><a class="btn btn-primary" href="' . (!isset($_GET['log']) ? URL::build('/admin/core/', 'view=maintenance') : URL::build('/admin/core/', 'view=errors')) . '">' . $language->get('general', 'back') . '</a></span><br /><br />';
                              if(!isset($_GET['log'])){
                              ?>
                              <div class="table-responsive">
                                <table class="table table-striped">
                                  <tr>
                                    <td><a href="<?php echo URL::build('/admin/core/', 'view=errors&amp;log=fatal'); ?>"><?php echo $language->get('admin', 'fatal_log'); ?></a></td>
                                  </tr>
                                  <tr>
                                    <td><a href="<?php echo URL::build('/admin/core/', 'view=errors&amp;log=notice'); ?>"><?php echo $language->get('admin', 'notice_log'); ?></a></td>
                                  </tr>
                                  <tr>
                                    <td><a href="<?php echo URL::build('/admin/core/', 'view=errors&amp;log=warning'); ?>"><?php echo $language->get('admin', 'warning_log'); ?></a></td>
                                  </tr>
                                  <tr>
                                    <td><a href="<?php echo URL::build('/admin/core/', 'view=errors&amp;log=other'); ?>"><?php echo $language->get('admin', 'other_log'); ?></a></td>
                                  </tr>
                                </table>
                              </div>
                              <?php
                              } else {
                                if(!in_array($_GET['log'], array('fatal', 'notice', 'warning', 'other'))){
                                  Redirect::to(URL::build('/admin/core/', 'view=errors'));
                                  die();
                                }

                                if(isset($_GET['do']) && $_GET['do'] == 'purge')
                                  file_put_contents(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'cache', 'logs', $_GET['log'] . '-log.log')), '');

                                if(file_exists(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'cache', 'logs', $_GET['log'] . '-log.log')))){
                                  echo '
                                  <style>
                                  .error_log {
                                    width: 100%;
                                    height: 400px;
                                    padding: 0 10px;
                                    -webkit-box-sizing: border-box;
                                    -moz-box-sizing: border-box;
                                    box-sizing: border-box;
                                    overflow-y: scroll;
                                    overflow-x: scroll;
                                    white-space: initial;
                                    background-color: #eceeef;
                                  }
                                  </style>';
                                  echo '<pre class="error_log">';
                                  echo nl2br(Output::getClean(file_get_contents(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'cache', 'logs', $_GET['log'] . '-log.log')))));
                                  echo '</pre>';
                                  echo '<hr /><h4>' . $language->get('general', 'actions') . '</h4>';
                                  echo '<a href="' . URL::build('/admin/core/', 'view=errors&amp;log=' . $_GET['log'] . '&amp;do=purge') . '" class="btn btn-warning" onclick="return confirm(\'' . $language->get('admin', 'confirm_purge_errors') . '\');">' . $language->get('admin', 'purge_errors') . '</a>';
                                } else {
                                  echo '<div class="alert alert-info">' . $language->get('admin', 'log_file_not_found') . '</div>';
                                }
                              }
                              break;

                            default:
                                Redirect::to(URL::build('/admin/core'));
                                die();
                                break;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require(ROOT_PATH . '/modules/Core/pages/admin/footer.php'); ?>
<?php require(ROOT_PATH . '/modules/Core/pages/admin/scripts.php'); ?>

<script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.js"></script>

<script>
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function (html) {
        var switchery = new Switchery(html);
    });
</script>

<?php if (isset($_GET['view']) && ($_GET['view'] == 'maintenance' || $_GET['view'] == 'terms')) { ?>
    <script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
    <script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js"></script>
    <script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/ckeditor.js"></script>
    <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/emojione/dialogs/emojione.json"></script>
    <script type="text/javascript">
        <?php
        if ($_GET['view'] == 'maintenance')
            echo Input::createEditor('InputMaintenanceMessage');
        else {
            echo Input::createEditor('InputPrivacy');
            echo Input::createEditor('InputTerms');
        }
        ?>
    </script>
<?php } else if (isset($_GET['view']) && $_GET['view'] == 'avatars'){ ?>
    <script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/dropzone/dropzone.min.js"></script>
    <script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/image-picker/image-picker.min.js"></script>

    <script>
        // Dropzone options
        Dropzone.options.upload_avatar_dropzone = {
            maxFilesize: 2,
            dictDefaultMessage: "<?php echo $language->get('admin', 'drag_files_here'); ?>",
            dictInvalidFileType: "<?php echo $language->get('admin', 'invalid_file_type'); ?>",
            dictFileTooBig: "<?php echo $language->get('admin', 'file_too_big'); ?>"
        };

        $(".image-picker").imagepicker();
    </script>
<?php } ?>
</body>
</html>