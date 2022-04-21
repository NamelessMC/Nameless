<?php
// 2.0.0-pr12 to 2.0.0-pr13 updater
try {
    $db_engine = Config::get('mysql/engine');
} catch (Exception $e) {
    // unable to retrieve from config
    echo $e->getMessage() . '<br />';
}
if (!$db_engine || ($db_engine != 'MyISAM' && $db_engine != 'InnoDB')) {
    $db_engine = 'InnoDB';
}

try {
    $db_charset = Config::get('mysql/charset');
} catch (Exception $e) {
    // unable to retrieve from config
    echo $e->getMessage() . '<br />';
}
if (!$db_charset || ($db_charset != 'utf8mb4' && $db_charset != 'latin1')) {
    $db_charset = 'latin1';
}

if (!isset($queries)) {
    $queries = new Queries();
}

// Default night mode to null instead of 0
try {
    DB::getInstance()->createQuery('ALTER TABLE nl2_users MODIFY night_mode tinyint(1) DEFAULT NULL NULL');
} catch (Exception $e) {
    // Continue
}

// Cookie policy
try {
    if (!DB::getInstance()->selectQuery('SELECT `id` FROM nl2_privacy_terms WHERE `name` = ?', ['cookies'])->count()) {
        $queries->create('privacy_terms', array(
            'name' => 'cookies',
            'value' => '<span style="font-size:18px"><strong>What are cookies?</strong></span><br />Cookies are small files which are stored on your device by a website, unique to your web browser. The web browser will send these files to the website each time it communicates with the website.<br />Cookies are used by this website for a variety of reasons which are outlined below.<br /><br /><strong>Necessary cookies</strong><br />Necessary cookies are required for this website to function. These are used by the website to maintain your session, allowing for you to submit any forms, log into the website amongst other essential behaviour. It is not possible to disable these within the website, however you can disable cookies altogether via your browser.<br /><br /><strong>Functional cookies</strong><br />Functional cookies allow for the website to work as you choose. For example, enabling the &quot;Remember Me&quot; option as you log in will create a functional cookie to automatically log you in on future visits.<br /><br /><strong>Analytical cookies</strong><br />Analytical cookies allow both this website, and any third party services used by this website, to collect non-personally identifiable data about the user. This allows us (the website staff) to continue to improve the user experience and understand how the website is used.<br /><br />Further information about cookies can be found online, including the <a rel="nofollow noopener" target="_blank" href="https://ico.org.uk/your-data-matters/online/cookies/">ICO&#39;s website</a> which contains useful links to further documentation about configuring your browser.<br /><br /><span style="font-size:18px"><strong>Configuring cookie use</strong></span><br />By default, only necessary cookies are used by this website. However, some website functionality may be unavailable until the use of cookies has been opted into.<br />You can opt into, or continue to disallow, the use of cookies using the cookie notice popup on this website. If you would like to update your preference, the cookie notice popup can be re-enabled by clicking the button below.'
        ));
    }
} catch (Exception $e) {
    // Continue
}

// delete old "version" row
try {
    DB::getInstance()->createQuery('DELETE FROM nl2_settings WHERE `name` = ?', ['version']);
} catch (Exception $e) {
    // Continue
}

try {
    DB::getInstance()->createQuery("CREATE TABLE `nl2_oauth` (
                                          `provider` varchar(256) NOT NULL,
                                          `enabled` tinyint(1) NOT NULL DEFAULT '0',
                                          `client_id` varchar(256) DEFAULT NULL,
                                          `client_secret` varchar(256) DEFAULT NULL,
                                          PRIMARY KEY (`provider`),
                                          UNIQUE KEY `id` (`provider`)
                                        ) ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

try {
    DB::getInstance()->createQuery("CREATE TABLE `nl2_oauth_users` (
                                          `user_id` int NOT NULL,
                                          `provider` varchar(256) NOT NULL,
                                          `provider_id` varchar(256) NOT NULL,
                                          PRIMARY KEY (`user_id`,`provider`,`provider_id`),
                                          UNIQUE KEY `user_id` (`user_id`,`provider`,`provider_id`)
                                        ) ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// User integrations
try {
    DB::getInstance()->createTable('integrations', " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(32) NOT NULL, `enabled` tinyint(1) NOT NULL DEFAULT '1', `can_unlink` tinyint(1) NOT NULL DEFAULT '1', `required` tinyint(1) NOT NULL DEFAULT '0', `order` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
    DB::getInstance()->createTable('users_integrations', " `id` int(11) NOT NULL AUTO_INCREMENT, `integration_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `identifier` varchar(64) DEFAULT NULL, `username` varchar(32) DEFAULT NULL, `verified` tinyint(1) NOT NULL DEFAULT '0', `date` int(11) NOT NULL, `code` varchar(64) DEFAULT NULL, `show_publicly` tinyint(1) NOT NULL DEFAULT '1', `last_sync` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)", "ENGINE=$db_engine DEFAULT CHARSET=$db_charset");

    $queries->create('integrations', [
        'name' => 'Minecraft',
        'enabled' => 1,
        'can_unlink' => 0,
        'required' => 0
    ]);

    $queries->create('integrations', [
        'name' => 'Discord',
        'enabled' => 1,
        'can_unlink' => 1,
        'required' => 0
    ]);
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users_integrations` ADD INDEX `nl2_users_integrations_idx_integration_id` (`integration_id`)');
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users_integrations` ADD INDEX `nl2_users_integrations_idx_user_id` (`user_id`)');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Convert users integrations
try {
    $users = DB::getInstance()->selectQuery('SELECT id, username, uuid, discord_id, discord_username, joined FROM nl2_users')->results();
    $query = 'INSERT INTO nl2_users_integrations (integration_id, user_id, identifier, username, verified, date) VALUES ';
    foreach ($users as $item) {
        if (!empty($item->uuid) && $item->uuid != 'none') {
            $inserts = ['(1,' . $item->id . ',\'' . $item->uuid . '\',\'' . $item->username . '\',1,' . $item->joined . '),'];
        }

        if ($item->discord_id != null && $item->discord_username != null && $item->discord_id != 010) {
            $inserts[] = '(2,' . $item->id . ',\'' . $item->discord_id . '\',\'' . $item->discord_username . '\',1,' . $item->joined . '),';
        }

        $query .= implode('', $inserts);
    }
    DB::getInstance()->createQuery(rtrim($query, ','));

    // Only delete after successful conversion
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users` DROP COLUMN `uuid`;');
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users` DROP COLUMN `discord_id`;');
    DB::getInstance()->createQuery('ALTER TABLE `nl2_users` DROP COLUMN `discord_username`;');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Add bedrock to nl2_mc_servers table
try {
    DB::getInstance()->createQuery('ALTER TABLE `nl2_mc_servers` ADD `bedrock` tinyint(1) NOT NULL DEFAULT \'0\'');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Increase length of name column
try {
    DB::getInstance()->createQuery('ALTER TABLE nl2_mc_servers MODIFY `name` VARCHAR(128) NOT NULL');
} catch (Exception $e) {
    // Continue
}

// add unique constraint to modules table
try {
    DB::getInstance()->createQuery('ALTER TABLE nl2_modules ADD UNIQUE (`name`)');
} catch (Exception $e) {
    // Continue
}

// Increase length of reset_code column
try {
    DB::getInstance()->createQuery('ALTER TABLE nl2_users MODIFY `reset_code` VARCHAR(64) NOT NULL');
} catch (Exception $e) {
    // Continue
}

// delete language cache since it will contain the old language names and not the short codes
$cache->setCache('languagecache');
$cache->eraseAll();

$default_language = $queries->getWhere('languages', ['is_default', '=', 1])[0]->name;

// drop all & truncate from languages table
try {
    DB::getInstance()->createQuery('TRUNCATE TABLE nl2_languages');
} catch (Exception $e) {
    // Continue
}

// add short code column to languages table
try {
    DB::getInstance()->createQuery('ALTER TABLE nl2_languages ADD `short_code` VARCHAR(64) NOT NULL');
} catch (Exception $e) {
    // Continue
}

// insert short codes into languages table, keeping the default language
try {
    foreach (Language::LANGUAGES as $short_code => $meta) {
        DB::getInstance()->createQuery('INSERT INTO nl2_languages (`name`, `short_code`, `is_default`) VALUES (?, ?, ?)', [
            $meta['name'],
            $short_code,
            $default_language === str_replace(' ', '', $meta['name']) ? 1 : 0
        ]);
    }
} catch (Exception $e) {
    // Continue
}

// reset all user languages to default
try {
    DB::getInstance()->createQuery('UPDATE nl2_users SET `language_id` = ?', [
        1
    ]);
} catch (Exception $e) {
    // Continue
}

// Update version number
/*$version_number_id = $queries->getWhere('settings', array('name', '=', 'nameless_version'));

if (count($version_number_id)) {
    $version_number_id = $version_number_id[0]->id;
    $queries->update('settings', $version_number_id, array(
        'value' => '2.0.0'
    ));
} else {
    $queries->create('settings', array(
        'name' => 'nameless_version',
        'value' => '2.0.0'
    ));
}*/

$version_update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
$version_update_id = $version_update_id[0]->id;

$queries->update('settings', $version_update_id, array(
    'value' => 'false'
));
