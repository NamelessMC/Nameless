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

        $site_id = Util::getSetting('unique_id');

        if ($site_id === null) {
            $api->throwError(Nameless2API::ERROR_NO_SITE_UID);
        }

        $ret = [];

        $ret['nameless_version'] = Util::getSetting('nameless_version');

        if (Util::getSetting('version_update') === 'urgent' || Util::getSetting('version_update') === 'true') {
            $ret['version_update'] = [
                'update' => true,
                'version' => Util::getSetting('new_version'),
                'urgent' => Util::getSetting('version_update') === 'urgent',
            ];
        }

        // Return default language
        $ret['locale'] = LANGUAGE;

        $modules_query = $api->getDb()->get('modules', ['enabled', true]);
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
