<?php

/**
 * @param int $id NamelessMC ID of user to view
 * @param string $username The NamelessMC username of the user to view
 * @param string $uuid The Minecraft UUID of the user
 *
 * @return string JSON Array
 */
class UserInfoEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users/{user}';
        $this->_module = 'Core';
        $this->_description = 'Get information about a NamelessMC user';
        $this->_method = 'GET';
    }

    public function execute(Nameless2API $api, User $user): void {
        $return = (object) clone $user->data();
        unset($return->password, $return->nickname, $return->email, $return->ip, $return->joined, $return->last_online, $return->banned, $return->active, $return->authme_sync_password, $return->pass_method, $return->tfa_secret);
        $return->exists = true;
        $return->registered_timestamp = $user->data()->joined;
        $return->last_online_timestamp = $user->data()->last_online;
        $return->banned = $user->data()->isbanned;
        $return->validated = $user->data()->active;
        $return->avatar_url = $user->getAvatar(128, true);
        $return->locale = DB::getInstance()->get('languages', ['id', $user->data()->language_id])->first()->short_code;
        $return->displayname = $user->data()->nickname;

        // Get custom profile fields
        foreach ($user->getProfileFields(true) as $id => $profile_field) {
            $return->profile_fields[$id] = [
                'name' => $profile_field->name,
                'type' => $profile_field->type,
                'public' => $profile_field->public,
                'required' => $profile_field->required,
                'editable' => $profile_field->editable,
                'description' => $profile_field->description,
                'value' => $profile_field->value
            ];
        }

        $groups_array = [];
        foreach ($user->getGroups() as $group) {
            $group_array = [
                'id' => (int)$group->id,
                'name' => $group->name,
                'staff' => (bool)$group->staff,
                'order' => (int)$group->order,
            ];

            $groups_array[] = $group_array;
        }
        $return->groups = $groups_array;

        $integrations_array = [];
        foreach ($user->getIntegrations() as $key => $integrationUser) {
            if ($integrationUser->data()->identifier === null && $integrationUser->data()->username === null) {
                continue;
            }

            $integrations_array[] = [
                'integration' => Output::getClean($key),
                'identifier' => Output::getClean($integrationUser->data()->identifier),
                'username' => Output::getClean($integrationUser->data()->username),
                'verified' => $integrationUser->isVerified(),
                'linked_date' => $integrationUser->data()->date,
                'show_publicly' => (bool) $integrationUser->data()->show_publicly,
            ];
        }
        $return->integrations = $integrations_array;

        $api->returnArray((array)$return);
    }
}
