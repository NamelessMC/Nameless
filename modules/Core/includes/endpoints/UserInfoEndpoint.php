<?php

/**
 * @param int $id NamelessMC ID of user to view
 * @param string $username The NamelessMC username of the user to view
 * @param string $uuid The Minecraft UUID of the user
 *
 * @return string JSON Array
 */
class UserInfoEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'user/info';
        $this->_route_aliases = ['userInfo'];
        $this->_module = 'Core';
        $this->_description = 'Get information about a NamelessMC user';
        $this->_method = 'GET';
    }

    public function execute(Nameless2API $api) {
        $discord_enabled = Util::isModuleEnabled('Discord Integration');

        if ($discord_enabled) {
            $query = 'SELECT nl2_users.id, nl2_users.username, nl2_users.language_id, nl2_languages.name as `language`, nl2_users.nickname as displayname, nl2_users.uuid, nl2_users.joined as registered_timestamp, nl2_users.last_online as last_online_timestamp, nl2_users.isbanned as banned, nl2_users.active as validated, nl2_users.user_title as user_title, nl2_users.discord_id as discord_id FROM nl2_users LEFT JOIN nl2_languages ON nl2_users.language_id = nl2_languages.id';
        } else {
            $query = 'SELECT nl2_users.id, nl2_users.username, nl2_users.language_id, nl2_languages.name as `language`, nl2_users.nickname as displayname, nl2_users.uuid, nl2_users.joined as registered_timestamp, nl2_users.last_online as last_online_timestamp, nl2_users.isbanned as banned, nl2_users.active as validated, nl2_users.user_title as user_title FROM nl2_users LEFT JOIN nl2_languages ON nl2_users.language_id = nl2_languages.id';
        }

        $where = '';
        $params = [];

        if (isset($_GET['id'])) {
            $where .= ' WHERE nl2_users.id = ?';
            array_push($params, $_GET['id']);
        } else {
            if (isset($_GET['username'])) {
                $where .= ' WHERE nl2_users.username = ?';
                array_push($params, $_GET['username']);
            } else {
                if (isset($_GET['uuid'])) {
                    $where .= ' WHERE nl2_users.uuid = ?';
                    array_push($params, str_replace('-', '', $_GET['uuid']));
                } else {
                    if ($discord_enabled && isset($_GET['discord_id'])) {
                        $where .= ' WHERE nl2_users.discord_id = ?';
                        array_push($params, $_GET['discord_id']);
                    } else {
                        $api->throwError(6, $api->getLanguage()->get('api', 'invalid_get_contents'));
                    }
                }
            }
        }

        // Ensure the user exists
        $user = $api->getDb()->selectQuery($query . $where, $params);

        if (!$user->count()) {
            $api->returnArray(['exists' => false]);
        }

        $user = $user->first();
        $user->exists = true;
        $user->id = intval($user->id);
        $user->language_id = intval($user->language_id);
        $user->registered_timestamp = intval($user->registered_timestamp);
        $user->last_online_timestamp = intval($user->last_online_timestamp);
        $user->banned = (bool)$user->banned;
        $user->validated = (bool)$user->validated;
        if ($discord_enabled && $user->discord_id != null) {
            $user->discord_id = intval($user->discord_id);
        }

        // Get custom profile fields
        $custom_profile_fields = $api->getDb()->selectQuery('SELECT fields.id, fields.name, fields.type, fields.public, fields.required, fields.description, pf_values.value FROM nl2_users_profile_fields pf_values LEFT JOIN nl2_profile_fields fields ON pf_values.field_id = fields.id WHERE pf_values.user_id = ?', [$user->id]);

        foreach ($custom_profile_fields->results() as $profile_field) {
            $user->profile_fields[$profile_field->id]['name'] = $profile_field->name;
            $user->profile_fields[$profile_field->id]['type'] = intval($profile_field->type);
            $user->profile_fields[$profile_field->id]['public'] = (bool)$profile_field->public;
            $user->profile_fields[$profile_field->id]['required'] = (bool)$profile_field->required;
            $user->profile_fields[$profile_field->id]['description'] = $profile_field->description;
            $user->profile_fields[$profile_field->id]['value'] = $profile_field->value;
        }

        // Get the groups the user has
        $groups = $api->getDb()->selectQuery('SELECT nl2_groups.* FROM nl2_users_groups INNER JOIN nl2_groups ON group_id = nl2_groups.id WHERE user_id = ? AND deleted = 0 ORDER BY `order`;', [$user->id])->results();

        $groups_array = [];
        foreach ($groups as $group) {
            $group_array = [
                'id' => intval($group->id),
                'name' => $group->name,
                'staff' => (bool)$group->staff,
                'order' => intval($group->order),
                'ingame_rank_name' => Util::getIngameRankName($group->id),
            ];

            if ($discord_enabled) {
                $group_array['discord_role_id'] = intval(Discord::getDiscordRoleId($api->getDb(), $group->id));
            }

            $groups_array[] = $group_array;
        }
        $user->groups = $groups_array;

        $api->returnArray((array)$user);
    }
}
