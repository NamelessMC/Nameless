<?php
// 2.0.0 pr-3 to 2.0.0 pr-4 updater

// Database changes
try {
    $queries->alterTable('groups', 'permissions', "mediumtext");
} catch(Exception $e){
    // Error
}

try {
    $queries->alterTable('users', 'profile_views', "int(11) NOT NULL DEFAULT '0'");
} catch(Exception $e){
    // Error
}

try {
    $queries->alterTable('users', 'private_profile', "tinyint(1) NOT NULL DEFAULT '0'");
} catch(Exception $e){
    // Error
}

try {
    $queries->alterTable('posts', 'created', "int(11) DEFAULT NULL");
} catch(Exception $e){
    // Error
}

try {
    $queries->alterTable('forums', 'redirect_forum', "tinyint(1) NOT NULL DEFAULT '0'");
} catch(Exception $e){
    // Error
}

try {
    $queries->alterTable('forums', 'redirect_url', "varchar(512) DEFAULT NULL");
} catch(Exception $e){
    // Error
}

try {
    $queries->alterTable('query_results', 'extra', "text");
} catch(Exception $e){
    // Error
}

try {
    $queries->alterTable('groups', 'default_group', "tinyint(1) NOT NULL DEFAULT '0'");
} catch(Exception $e){
    // Error
}

// New settings
try {
    $queries->update('groups', 1, array(
        'default_group' => 1
    ));
} catch(Exception $e){

}

try {
    $queries->update('groups', 2, array(
        'permissions' => '{"admincp.core":1,"admincp.core.api":1,"admincp.core.general":1,"admincp.core.avatars":1,"admincp.core.fields":1,"admincp.core.debugging":1,"admincp.core.emails":1,"admincp.core.navigation":1,"admincp.core.reactions":1,"admincp.core.registration":1,"admincp.core.social_media":1,"admincp.core.terms":1,"admincp.errors":1,"admincp.minecraft":1,"admincp.minecraft.authme":1,"admincp.minecraft.verification":1,"admincp.minecraft.servers":1,"admincp.minecraft.query_errors":1,"admincp.minecraft.banners":1,"admincp.modules":1,"admincp.pages":1,"admincp.security":1,"admincp.security.acp_logins":1,"admincp.security.template":1,"admincp.styles":1,"admincp.styles.templates":1,"admincp.styles.templates.edit":1,"admincp.styles.images":1,"admincp.update":1,"admincp.users":1,"admincp.groups":1,"admincp.groups.self":1,"admincp.widgets":1,"modcp.ip_lookup":1,"modcp.punishments":1,"modcp.punishments.warn":1,"modcp.punishments.ban":1,"modcp.punishments.banip":1,"modcp.punishments.revoke":1,"modcp.reports":1,"usercp.messaging":1,"usercp.signature":1,"admincp.forums":1,"usercp.private_profile":1,"usercp.nickname":1,"profile.private.bypass":1, "admincp.security.all":1}'
    ));
} catch(Exception $e){
    // Error
}

try {
    $setting_exists = $queries->getWhere('settings', array('name', '=', 'private_profile'));
    if(!count($setting_exists)){
        $queries->create('settings', array(
            'name' => 'private_profile',
            'value' => 1
        ));
    }
} catch(Exception $e){
    // Error
}

try {
    $setting_exists = $queries->getWhere('settings', array('name', '=', 'registration_disabled_message'));
    if(!count($setting_exists)){
        $queries->create('settings', array(
            'name' => 'registration_disabled_message',
            'value' => null
        ));
    }
} catch(Exception $e){
    // Error
}

try {
    $setting_exists = $queries->getWhere('settings', array('name', '=', 'discord_hooks'));
    if(!count($setting_exists)){
        $queries->create('settings', array(
            'name' => 'discord_hooks',
            'value' => '{}'
        ));
    }
} catch(Exception $e){
    // Error
}

try {
    $setting_exists = $queries->getWhere('settings', array('name', '=', 'api_verification'));
    if(!count($setting_exists)){
        $queries->create('settings', array(
            'name' => 'api_verification',
            'value' => '0'
        ));
    }
} catch(Exception $e){
    // Error
}

try {
    $setting_exists = $queries->getWhere('settings', array('name', '=', 'validate_user_action'));
    if(!count($setting_exists)){
        $queries->create('settings', array(
            'name' => 'validate_user_action',
            'value' => '{"action":"promote","group":1}'
        ));
    }
} catch(Exception $e){
    // Error
}

try {
    $setting_exists = $queries->getWhere('settings', array('name', '=', 'login_method'));
    if(!count($setting_exists)){
        $queries->create('settings', array(
            'name' => 'login_method',
            'value' => 'email'
        ));
    }
} catch(Exception $e){
    // Error
}

try {
    $setting_exists = $queries->getWhere('settings', array('name', '=', 'username_sync'));
    if(!count($setting_exists)){
        $queries->create('settings', array(
            'name' => 'username_sync',
            'value' => '1'
        ));
    }
} catch(Exception $e){
    // Error
}

try {
    $setting_exists = $queries->getWhere('settings', array('name', '=', 'privacy_policy'));
    if(!count($setting_exists)){
        $queries->create('settings', array(
            'name' => 'privacy_policy',
            'value' => 'The following privacy policy outlines how your data is used on our website.<br /><br /><strong>Data</strong><br />Basic non-identifiable information about your user on the website is collected; the majority of which is provided during registration, such as email addresses and usernames.<br />In addition to this, IP addresses for registered users are stored within the system to aid with moderation duties. This includes spam prevention, and detecting alternative accounts.<br /><br />Accounts can be deleted by a site administrator upon request, which will remove all data relating to your user from our system.<br /><br /><strong>Cookies</strong><br />Cookies are used to store small pieces of non-identifiable information with your consent. In order to consent to the use of cookies, you must either close the cookie notice (as explained within the notice) or register on our website.<br />Data stored by cookies include any recently viewed topic IDs, along with a unique, unidentifiable hash upon logging in and selecting &quot;Remember Me&quot; to automatically log you in next time you visit.'
        ));
    }
} catch(Exception $e){
    // Error
}

// Template
try {
    $template = file_get_contents(ROOT_PATH . '/custom/templates/Default/widgets/online_staff.tpl');

    if(!file_exists(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/widgets/online_staff.tpl'))
        file_put_contents(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/widgets/online_staff.tpl', $template);

    $template = file_get_contents(ROOT_PATH . '/custom/templates/Default/widgets/online_users.tpl');

    if(!file_exists(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/widgets/online_users.tpl'))
        file_put_contents(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/widgets/online_users.tpl', $template);

} catch(Exception $e){
    // Error
}

// Update version number
$version_number_id = $queries->getWhere('settings', array('name', '=', 'nameless_version'));
$version_number_id = $version_number_id[0]->id;

if(count($version_number_id)){
    $queries->update('settings', $version_number_id, array(
        'value' => '2.0.0-pr4'
    ));
} else {
    $version_number_id = $queries->getWhere('settings', array('name', '=', 'version'));
    $version_number_id = $version_number_id[0]->id;

    $queries->update('settings', $version_number_id, array(
        'value' => '2.0.0-pr4'
    ));
}

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
    'value' => 'false'
));
