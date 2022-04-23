<?php

/**
 * No params
 *
 * @return string JSON Array of NamelessMC information
 */
class InfoEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'info';
        $this->_module = 'Core';
        $this->_description = 'Return info about the Nameless installation';
        $this->_method = 'GET';
    }

    public function execute(Nameless2API $api): void {
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
                        $version_checked = (int) $item->value;
                    } else {
                        $new_version = $item->value;
                    }
                }
            }
        }

        if (isset($version_checked, $version_update, $current_version) && $version_update != 'false') {
            $ret['version_update'] = [
                'update' => true,
                'version' => (isset($new_version) ? Output::getClean($new_version) : 'unknown'),
                'urgent' => ($version_update == 'urgent')
            ];
        }

        // Return default language
        $ret['locale'] = LANGUAGE;

        $modules_query = $api->getDb()->get('modules', ['enabled', '=', 1]);
        $ret_modules = [];
        if ($modules_query->count()) {
            $modules_query = $modules_query->results();
            foreach ($modules_query as $module) {
                $ret_modules[] = $module->name;
            }
        }
        $ret['modules'] = $ret_modules;

        $api->returnArray($ret);
    }
}
