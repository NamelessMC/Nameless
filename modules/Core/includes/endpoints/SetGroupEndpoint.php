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
                $user = $api->getDb()->get('users', array('id', '=', htmlspecialchars($_POST['id'])));
                if (!$user->count()) $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));
                $user = $user->first();

                // Ensure group exists
                $group = $api->getDb()->get('groups', array('id', '=', $_POST['group_id']));
                if (!$group->count()) $api->throwError(17, $api->getLanguage()->get('api', 'unable_to_find_group'));
                $group = $group->first()->id;

                $new_secondary_groups = array();
                foreach ($user->secondary_groups as $secondary_group) {
                    if ($group != $secondary_group) {
                        $new_secondary_groups[] = $secondary_group;
                    }
                }

                // Attempt to update their discord role as well, but ignore any output/errors
                Discord::addDiscordRole($user, $group, $api->getLanguage(), false);

                try {
                    $api->getDb()->update('users', $user->id, array(
                        'group_id' => $group,
                        'secondary_groups' => $new_secondary_groups
                    ));
                } catch (Exception $e) {
                    $api->throwError(18, $api->getLanguage()->get('api', 'unable_to_update_group'));
                }

                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'group_updated')));
            }
        }
    }
}