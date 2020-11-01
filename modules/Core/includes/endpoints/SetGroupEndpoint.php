<?php

/**
 * @param int $id NamelessMC ID of user to view
 * @param int $group_id ID of NamelessMC group
 * 
 * @return string JSON Array
 */
class SetGroupEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'setGroup';
        $this->_module = 'Core';
        $this->_description = 'Set a user\'s primary NamelessMC group';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {
            if ($api->validateParams($_POST, ['id', 'group_id'])) {
                
                // Ensure user exists
				$user = new User(htmlspecialchars($_POST['id']));
                if (!count($user->data())) $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));

                // Ensure group exists
                $group = $api->getDb()->get('groups', array('id', '=', $_POST['group_id']));
                if (!$group->count()) $api->throwError(17, $api->getLanguage()->get('api', 'unable_to_find_group'));
                $group = $group->first()->id;

                // Attempt to update their discord role as well, but ignore any output/errors
                Discord::addDiscordRole($user, $group, $api->getLanguage(), false);

                try {
                    $user->addGroup($group);
                } catch (Exception $e) {
                    $api->throwError(18, $api->getLanguage()->get('api', 'unable_to_update_group'));
                }

                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated')));
            }
        }
    }
}