<?php
// 2.0.0 pr-12 to 2.0.0 ? updater
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

// delete language cache since it will contain the old language names and not the short codes
$cache->setCache('languagecache');
$cache->eraseAll();

$default_language = $queries->getWhere('languages', ['is_default', '=', 1])[0]->name;

// drop all from languages table
try {
    DB::getInstance()->createQuery('DELETE FROM nl2_languages WHERE `id` <> 0');
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
