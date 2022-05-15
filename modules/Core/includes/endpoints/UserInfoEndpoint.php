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
        $discord_enabled = Util::isModuleEnabled('Discord Integration');

        $query = 'SELECT nl2_users.id, nl2_users.username, nl2_languages.short_code as `locale`, nl2_users.nickname as displayname, nl2_users.joined as registered_timestamp, nl2_users.last_online as last_online_timestamp, nl2_users.isbanned as banned, nl2_users.active as validated, nl2_users.user_title as user_title FROM nl2_users LEFT JOIN nl2_languages ON nl2_users.language_id = nl2_languages.id';

        // Ensure the user exists
        $results = $api->getDb()->query($query . ' WHERE nl2_users.id = ?', [(int) $user->data()->id]);

        $return = $results->first();
        $return->exists = true;
        $return->id = (int)$return->id;
        $return->registered_timestamp = (int)$return->registered_timestamp;
        $return->last_online_timestamp = (int)$return->last_online_timestamp;
        $return->banned = (bool)$return->banned;
        $return->validated = (bool)$return->validated;

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

        // Get the groups the user has
        $groups = $api->getDb()->query('SELECT nl2_groups.* FROM nl2_users_groups INNER JOIN nl2_groups ON group_id = nl2_groups.id WHERE user_id = ? AND deleted = 0 ORDER BY `order`;', [$user->data()->id])->results();

        $groups_array = [];
        foreach ($groups as $group) {
            $group_array = [
                'id' => (int)$group->id,
                'name' => $group->name,
                'staff' => (bool)$group->staff,
                'order' => (int)$group->order,
                'ingame_rank_name' => Util::getIngameRankName($group->id),
            ];

            if ($discord_enabled) {
                $group_array['discord_role_id'] = (int)Discord::getDiscordRoleId($api->getDb(), $group->id);
            }

            $groups_array[] = $group_array;
        }
        $return->groups = $groups_array;

        $integrations_array = [];
        foreach ($user->getIntegrations() as $key => $integrationUser) {
            if ($integrationUser->data()->identifier == null && $integrationUser->data()->username == null) {
                continue;
            }

            $integrations_array[] = [
                'integration' => Output::getClean($key),
                'identifier' => Output::getClean($integrationUser->data()->identifier),
                'username' => Output::getClean($integrationUser->data()->username),
                'verified' => (bool) $integrationUser->isVerified(),
                'linked_date' => $integrationUser->data()->date,
                'show_publicly' => (bool) $integrationUser->data()->show_publicly,
            ];
        }
        $return->integrations = $integrations_array;

        $api->returnArray((array)$return);
    }
}
