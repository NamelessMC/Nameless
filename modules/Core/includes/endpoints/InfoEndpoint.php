<?php

/**
 * No params
 *
 * @return string JSON Array of NamelessMC information
 */
class InfoEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'info';
        $this->_module = 'Core';
        $this->_description = 'Return info about the Nameless installation';
        $this->_method = 'GET';
    }

    public function execute(Nameless2API $api) {
        // Get version, update info and modules from database
        $version_query = $api->getDb()->selectQuery('SELECT `name`, `value` FROM nl2_settings WHERE `name` = ? OR `name` = ? OR `name` = ? OR `name` = ?', ['nameless_version', 'version_checked', 'version_update', 'new_version']);
        if ($version_query->count()) {
            $version_query = $version_query->results();
        }

        $site_id = Util::getSetting($api->getDb(), 'unique_id');
        if ($site_id == null) {
            $api->throwError(4, $api->getLanguage()->get('api', 'no_unique_site_id'));
        }

        $ret = [];
        foreach ($version_query as $item) {
            if ($item->name == 'nameless_version') {
                $ret[$item->name] = $item->value;
                $current_version = $item->value;
            } else {
                if ($item->name == 'version_update') {
                    $version_update = $item->value;
                } else {
                    if ($item->name == 'version_checked') {
                        $version_checked = (int)$item->value;
                    } else {
                        $new_version = $item->value;
                    }
                }
            }
        }

        // Return default language
        $ret['language'] = LANGUAGE;

        if (isset($version_checked) && isset($version_update) && isset($current_version)) {
            if ($version_update == 'false') {
                if ($version_checked < strtotime('-1 hour')) {
                    // Check for update now
                    $update_check = HttpClient::get('https://namelessmc.com/nl_core/nl2/stats.php?uid=' . $site_id . '&version=' . $current_version);

                    if ($update_check->hasError() || $update_check->data() == 'Failed') {
                        $api->throwError(5, $api->getLanguage()->get('api', 'unable_to_check_for_updates'));
                    }

                    $update_check = $update_check->data();
                    if ($update_check == 'None') {
                        $ret['version_update'] = ['update' => false];
                    } else {
                        $update_check = json_decode($update_check);

                        if (isset($update_check->urgent) && $update_check->urgent == 'true') {
                            $update_needed = 'urgent';
                        } else {
                            $update_needed = 'true';
                        }

                        // Update database values to say we need a version update
                        $api->getDb()->createQuery("UPDATE nl2_settings SET `value` = ? WHERE `name` = 'version_update'", [$update_needed]);
                        $api->getDb()->createQuery("UPDATE nl2_settings SET `value` = ? WHERE `name` = 'version_checked'", [date('U')]);
                        $api->getDb()->createQuery("UPDATE nl2_settings set `value` = ? WHERE `name` = 'new_version'", [$update_check->new_version]);

                        $ret['version_update'] = ['update' => true, 'version' => $update_check->new_version, 'urgent' => ($update_needed == 'urgent')];
                    }
                }
            } else {
                $ret['version_update'] = ['update' => true, 'version' => (isset($new_version) ? Output::getClean($new_version) : 'unknown'), 'urgent' => ($version_update == 'urgent')];
            }
        }
        $modules_query = $api->getDb()->get('modules', ['enabled', '=', 1]);
        $ret_modules = [];
        if ($modules_query->count()) {
            $modules_query = $modules_query->results();
            foreach ($modules_query as $module) {
                $ret_modules[] = $module->name;
            }
        }
        $ret['modules'] = $ret_modules;

        if (count($ret)) {
            $api->returnArray($ret);
        }
    }
}
