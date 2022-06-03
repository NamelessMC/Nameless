<?php

class Pre13 extends UpgradeScript {

    public function run(): void {
        $this->databaseQuery(function (DB $db) {
            // Disable all modules and templates & reset defaults
            $db->query("UPDATE `nl2_modules` SET `enabled` = 0 WHERE `name` NOT IN ('Core', 'Forum')");
            $this->_cache->setCache('modulescache');
            $this->_cache->eraseAll();
            $arr = [
                ['name' => 'Core', 'priority' => 1]
            ];
            if ($db->query("SELECT enabled FROM nl2_modules WHERE name = 'Forum' AND enabled = 1")->count() > 0) {
                $arr[] = ['name' => 'Forum', 'priority' => 2];
            }
            $this->_cache->store('enabled_modules', $arr);
            $this->_cache->store('module_core', true);

            $db->query("UPDATE `nl2_templates` SET `enabled` = 0 WHERE `name` <> 'DefaultRevamp'");
            $db->query("UPDATE `nl2_templates` SET `enabled` = 1, `is_default`  = 1 WHERE `name` = 'DefaultRevamp'");
            $db->query("UPDATE `nl2_panel_templates` SET `enabled` = 0 WHERE `name` <> 'Default'");
            $db->query("UPDATE `nl2_panel_templates` SET `enabled` = 1, `is_default`  = 1 WHERE `name` = 'Default'");
            $this->_cache->setCache('templatecache');
            $this->_cache->eraseAll();
        });

        // Default night mode to null instead of 0
        $this->databaseQuery(function (DB $db) {
            $db->query('ALTER TABLE nl2_users MODIFY night_mode tinyint(1) DEFAULT NULL NULL');
        });

        // Default log table user IP to null
        $this->databaseQuery(function (DB $db) {
            $db->query('ALTER TABLE nl2_logs MODIFY ip varchar(128) DEFAULT NULL NULL');
        });

        // Cookie policy
        $this->databaseQuery(function (DB $db) {
            if (!$db->query('SELECT `id` FROM nl2_privacy_terms WHERE `name` = ?', ['cookies'])->count()) {
                $db->insert('privacy_terms', array(
                    'name' => 'cookies',
                    'value' => '<span style="font-size:18px"><strong>What are cookies?</strong></span><br />Cookies are small files which are stored on your device by a website, unique to your web browser. The web browser will send these files to the website each time it communicates with the website.<br />Cookies are used by this website for a variety of reasons which are outlined below.<br /><br /><strong>Necessary cookies</strong><br />Necessary cookies are required for this website to function. These are used by the website to maintain your session, allowing for you to submit any forms, log into the website amongst other essential behaviour. It is not possible to disable these within the website, however you can disable cookies altogether via your browser.<br /><br /><strong>Functional cookies</strong><br />Functional cookies allow for the website to work as you choose. For example, enabling the &quot;Remember Me&quot; option as you log in will create a functional cookie to automatically log you in on future visits.<br /><br /><strong>Analytical cookies</strong><br />Analytical cookies allow both this website, and any third party services used by this website, to collect non-personally identifiable data about the user. This allows us (the website staff) to continue to improve the user experience and understand how the website is used.<br /><br />Further information about cookies can be found online, including the <a rel="nofollow noopener" target="_blank" href="https://ico.org.uk/your-data-matters/online/cookies/">ICO&#39;s website</a> which contains useful links to further documentation about configuring your browser.<br /><br /><span style="font-size:18px"><strong>Configuring cookie use</strong></span><br />By default, only necessary cookies are used by this website. However, some website functionality may be unavailable until the use of cookies has been opted into.<br />You can opt into, or continue to disallow, the use of cookies using the cookie notice popup on this website. If you would like to update your preference, the cookie notice popup can be re-enabled by clicking the button below.'
                ));
            }
        });

        $this->databaseQuery(function (DB $db) {
            // make the name column in nl2_settings table unique
            // first remove duplicates
            $to_delete = $db->query('SELECT s.id FROM nl2_settings s
                WHERE s.name IN (SELECT name FROM nl2_settings GROUP BY name HAVING count(*) > 1)
                AND id <> (SELECT MIN(id) FROM nl2_settings WHERE name = s.name)
            ');

            if ($to_delete->count()) {
                $items = '(';
                foreach ($to_delete->results() as $item) {
                    $items .= ((int)$item->id) . ',';
                }
                $items = rtrim($items, ',') . ')';

                $db->query('DELETE FROM nl2_settings WHERE id IN ' . $items);
            }

            // now add unique constraint
            $db->query('ALTER TABLE nl2_settings ADD CONSTRAINT UNIQUE(`name`)');
        });

        // delete old "version" row
        $this->databaseQuery(function (DB $db) {
            Util::setSetting('version', null);
        });

        // oauth
        $this->databaseQuery(function (DB $db) {
            $db->createTable('oauth', "
                                        `provider` varchar(256) NOT NULL,
                                        `enabled` tinyint(1) NOT NULL DEFAULT '0',
                                        `client_id` varchar(256) DEFAULT NULL,
                                        `client_secret` varchar(256) DEFAULT NULL,
                                        PRIMARY KEY (`provider`),
                                        UNIQUE KEY `id` (`provider`)");
            $db->createTable('oauth_users', "
                                      `user_id` int NOT NULL,
                                      `provider` varchar(256) NOT NULL,
                                      `provider_id` varchar(256) NOT NULL,
                                      PRIMARY KEY (`user_id`,`provider`,`provider_id`),
                                      UNIQUE KEY `user_id` (`user_id`,`provider`,`provider_id`)");
        });

        // User integrations
        $this->databaseQueries([
            function (DB $db) {
                $db->createTable('integrations', " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(32) NOT NULL, `enabled` tinyint(1) NOT NULL DEFAULT '1', `can_unlink` tinyint(1) NOT NULL DEFAULT '1', `required` tinyint(1) NOT NULL DEFAULT '0', `order` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)");
                $db->createTable('users_integrations', " `id` int(11) NOT NULL AUTO_INCREMENT, `integration_id` int(11) NOT NULL, `user_id` int(11) NOT NULL, `identifier` varchar(64) DEFAULT NULL, `username` varchar(32) DEFAULT NULL, `verified` tinyint(1) NOT NULL DEFAULT '0', `date` int(11) NOT NULL, `code` varchar(64) DEFAULT NULL, `show_publicly` tinyint(1) NOT NULL DEFAULT '1', `last_sync` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)");
            },
            function (DB $db) {
                $db->insert('integrations', [
                    'name' => 'Minecraft',
                    'enabled' => true,
                    'can_unlink' => false,
                    'required' => false,
                ]);

                $db->insert('integrations', [
                    'name' => 'Discord',
                    'enabled' => true,
                    'can_unlink' => true,
                    'required' => false
                ]);
            }
        ]);

        $this->databaseQuery(
            function (DB $db) {
                $db->query('ALTER TABLE `nl2_users_integrations` ADD INDEX `nl2_users_integrations_idx_integration_id` (`integration_id`)');
                $db->query('ALTER TABLE `nl2_users_integrations` ADD INDEX `nl2_users_integrations_idx_user_id` (`user_id`)');
            }
        );

        // Convert users integrations
        $this->databaseQuery(function (DB $db) {
            $users = $db->query('SELECT id, username, uuid, discord_id, discord_username, joined FROM nl2_users')->results();
            $query = 'INSERT INTO nl2_users_integrations (integration_id, user_id, identifier, username, verified, date) VALUES ';
            foreach ($users as $item) {
                $inserts = [];

                if (!empty($item->uuid) && $item->uuid != 'none') {
                    $inserts[] = '(1,' . $item->id . ',\'' . $item->uuid . '\',\'' . $item->username . '\',1,' . $item->joined . '),';
                }

                if ($item->discord_id != null && $item->discord_username != null && $item->discord_id != 010) {
                    $inserts[] = '(2,' . $item->id . ',\'' . $item->discord_id . '\',\'' . $item->discord_username . '\',1,' . $item->joined . '),';
                }

                $query .= implode('', $inserts);
            }
            $db->query(rtrim($query, ','));

            // Only delete after successful conversion
            $db->query('ALTER TABLE `nl2_users` DROP COLUMN `uuid`;');
            $db->query('ALTER TABLE `nl2_users` DROP COLUMN `discord_id`;');
            $db->query('ALTER TABLE `nl2_users` DROP COLUMN `discord_username`;');
        });

        // Add bedrock to nl2_mc_servers table
        $this->databaseQuery(function (DB $db) {
            $db->query('ALTER TABLE `nl2_mc_servers` ADD `bedrock` tinyint(1) NOT NULL DEFAULT \'0\'');
        });

        // Increase length of name column
        $this->databaseQuery(function (DB $db) {
            $db->query('ALTER TABLE nl2_mc_servers MODIFY `name` VARCHAR(128) NOT NULL');
        });

        // add unique constraint to modules table
        $this->databaseQuery(function (DB $db) {
            $db->query('ALTER TABLE nl2_modules ADD UNIQUE (`name`)');
        });

        // Increase length of reset_code column
        $this->databaseQuery(function (DB $db) {
            $db->query('ALTER TABLE nl2_users MODIFY `reset_code` VARCHAR(64) NOT NULL');
        });

        // delete language cache since it will contain the old language names and not the short codes
        $this->_cache->setCache('languagecache');
        $this->_cache->eraseAll();

        // add short code column to languages table
        $this->databaseQuery(function (DB $db) {
            $db->query('ALTER TABLE nl2_languages ADD `short_code` VARCHAR(64) NOT NULL');

            $languages = $db->query('SELECT * FROM nl2_languages')->results();
            $converted_languages = [];
            foreach (Language::LANGUAGES as $short_code => $meta) {
                $key = array_search(str_replace(' ', '', $meta['name']), array_column($languages, 'name'));

                if ($key !== false) {
                    $db->update('languages', $languages[$key]->id, [
                        'name' => $meta['name'],
                        'short_code' => $short_code
                    ]);

                    $converted_languages[] = $languages[$key]->id;
                } else {
                    $db->insert('languages', [
                        'name' => $meta['name'],
                        'short_code' => $short_code
                    ]);

                    $converted_languages[] = $db->lastId();
                }
            }

            $db->query('DELETE FROM nl2_languages WHERE `short_code` IS NULL');

            $default_language = $db->query('SELECT id, short_code FROM nl2_languages WHERE `is_default` = 1');

            if (!$default_language->count()) {
                // Default to 1 (EnglishUK)
                $default_language = 1;
                $default_short_code = 'en_UK';
                $db->query('UPDATE nl2_languages SET `is_default` = 1 WHERE `id` = 1');
            } else {
                $default_language = $default_language->first()->id;
                $default_short_code = $default_language->short_code;
            }

            $this->_cache->store('language', $default_short_code);

            $db->query('UPDATE nl2_users SET `language_id` = ? WHERE `language_id` NOT IN (' . implode(', ', $converted_languages) . ')', [$default_language]);
        });

        // add updated column to users profile fields
        $this->databaseQuery(function (DB $db) {
            $db->addColumn('users_profile_fields', 'updated', 'int(11)');
        });

        // update settings from true/false to 1/0
        $settings = ['displaynames', 'recaptcha', 'recaptcha_login', 'version_update', 'maintenance'];
        foreach ($settings as $setting) {
            $value = Util::getSetting($setting);
            if ($value === 'true' || $value === '1') {
                Util::setSetting($setting, '1');
            } else {
                Util::setSetting($setting, '0');
            }
        }

        // add captcha default
        if (Util::getSetting('recaptcha_type') == null) {
            Util::getSetting('recaptcha_type', 'Recaptcha3');
        }

        // delete old class files
        $this->deleteFiles([
            'core/classes/Alert.php',
            'core/classes/Announcements.php',
            'core/classes/AvatarSource.php',
            'core/classes/Cache.php',
            'core/classes/CaptchaBase.php',
            'core/classes/CollectionItemBase.php',
            'core/classes/CollectionManager.php',
            'core/classes/Config.php',
            'core/classes/Configuration.php',
            'core/classes/Cookie.php',
            'core/classes/DB.php',
            'core/classes/DB_Custom.php',
            'core/classes/Discord.php',
            'core/classes/Email.php',
            'core/classes/EndpointBase.php',
            'core/classes/Endpoints.php',
            'core/classes/ErrorHandler.php',
            'core/classes/ExternalMCQuery.php',
            'core/classes/Hash.php',
            'core/classes/HookHandler.php',
            'core/classes/Input.php',
            'core/classes/Language.php',
            'core/classes/Log.php',
            'core/classes/MCAssoc.php',
            'core/classes/MCQuery.php',
            'core/classes/MentionsParser.php',
            'core/classes/MinecraftBanner.php',
            'core/classes/MinecraftPing.php',
            'core/classes/Module.php',
            'core/classes/Navigation.php',
            'core/classes/Output.php',
            'core/classes/Pages.php',
            'core/classes/Paginator.php',
            'core/classes/PermissionHandler.php',
            'core/classes/Placeholders.php',
            'core/classes/Queries.php',
            'core/classes/Redirect.php',
            'core/classes/Report.php',
            'core/classes/ServerBanner.php',
            'core/classes/Session.php',
            'core/classes/TemplateBase.php',
            'core/classes/Timeago.php',
            'core/classes/Token.php',
            'core/classes/URL.php',
            'core/classes/User.php',
            'core/classes/Util.php',
            'core/classes/Validate.php',
            'core/classes/WidgetBase.php',
            'core/classes/Widgets.php',
            'modules/Core/classes/Core_Sitemap.php',
            'modules/Core/classes/CrafatarAvatarSource.php',
            'modules/Core/classes/CraftheadAvatarSource.php',
            'modules/Core/classes/CravatarAvatarSource.php',
            'modules/Core/classes/MCHeadsAvatarSource.php',
            'modules/Core/classes/MinotarAvatarSource.php',
            'modules/Core/classes/NamelessMCAvatarSource.php',
            'modules/Core/classes/Recaptcha2.php',
            'modules/Core/classes/Recaptcha3.php',
            'modules/Core/classes/VisageAvatarSource.php',
            'modules/Core/classes/hCaptcha.php',
            'modules/Core/includes/endpoints/discord/SetDiscordRolesEndpoint.php',
            'modules/Core/includes/endpoints/discord/SubmitDiscordRoleListEndpoint.php',
            'modules/Core/includes/endpoints/discord/UpdateDiscordBotSettingsEndpoint.php',
            'modules/Core/includes/endpoints/discord/UpdateDiscordUsernames.php',
            'modules/Core/includes/endpoints/discord/VerifyDiscordEndpoint.php',
            'modules/Core/includes/endpoints/ListGroupsEndpoint.php',
            'modules/Core/includes/endpoints/VerifyMinecraftEndpoint.php',
        ]);

        // delete old home type cache & update new cache
        $this->_cache->setCache('portal_cache');
        $portal = $this->_cache->retrieve('portal');
        $this->_cache->eraseAll();
        $this->databaseQuery(function (DB $db) use ($portal) {
            Util::setSetting('portal', null);
            Util::setSetting('home_type', $portal ? 'portal' : 'news');
        });

        // add existing migrations to phinxlog table, so it doesn't try to run them again
        $migrations = [];
        $migrations_dir = ROOT_PATH . '/core/migrations';
        $files = scandir($migrations_dir);
        foreach ($files as $file) {
            if ($file === 'phinx.php' || str_starts_with($file, '.')) {
                continue;
            }
            $file = explode('.', $file)[0];
            [$time, $name] =  explode('_', $file, 2);
            $name_parts = explode('_', $name);
            // join name parts with upper case
            $name = implode('', array_map('ucfirst', $name_parts));
            $epoch = time();
            $dt = new DateTime("@$epoch");
            $migrations[] = [
                'version' => $time,
                'name' => $name,
                'start_time' => $dt->format('Y-m-d H:i:s'),
                'end_time' => $dt->format('Y-m-d H:i:s'),
                'breakpoint' => 0,
            ];
        }

        $this->databaseQuery(function (DB $db) use ($migrations) {
            $db->query("CREATE TABLE IF NOT EXISTS `nl2_phinxlog` (
                `version` bigint NOT NULL,
                `migration_name` varchar(100) NULL DEFAULT NULL,
                `start_time` timestamp NULL DEFAULT NULL,
                `end_time` timestamp NULL DEFAULT NULL,
                `breakpoint` tinyint(1) NOT NULL DEFAULT '0',
                PRIMARY KEY (`version`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            $db->query("TRUNCATE TABLE `nl2_phinxlog`");

            foreach ($migrations as $migration) {
                $db->query("INSERT INTO nl2_phinxlog (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES (?, ?, ?, ?, ?)", [
                    $migration['version'],
                    $migration['name'],
                    $migration['start_time'],
                    $migration['end_time'],
                    $migration['breakpoint'],
                ]);
            }
        });

        $this->setVersion('2.0.0-pr13');
    }
}

return new Pre13();
