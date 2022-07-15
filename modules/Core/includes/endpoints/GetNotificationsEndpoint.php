<?php

/**
 * @param int $id The NamelessMC ID of the user to get notifications for
 * @param string $username NamelessMC sername of the user to get notifications for
 *
 * @return string JSON Array
 * @see Alert
 *
 */
class GetNotificationsEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users/{user}/notifications';
        $this->_module = 'Core';
        $this->_description = 'Get notifications for a user';
        $this->_method = 'GET';
    }

    public function execute(Nameless2API $api, User $user): void {
        $return = ['notifications' => []];

        // Get unread alerts
        $alerts = $api->getDb()->query('SELECT id, type, url, content_short, content, created FROM nl2_alerts WHERE user_id = ? AND `read` = 0', [$user->data()->id]);
        if ($alerts->count()) {
            foreach ($alerts->results() as $result) {
                $return['notifications'][] = [
                    'type' => $result->type,
                    'message_short' => $result->content_short,
                    'message' => ($result->content) ? strip_tags($result->content) : $result->content_short,
                    'url' => rtrim(URL::getSelfURL(), '/') . URL::build('/user/alerts/', 'view=' . urlencode($result->id)),
                    'received_at' => $result->created,
                ];
            }
        }

        // Get unread messages
        $messages = $api->getDb()->query('SELECT nl2_private_messages.id, nl2_private_messages.title FROM nl2_private_messages WHERE nl2_private_messages.id IN (SELECT nl2_private_messages_users.pm_id as id FROM nl2_private_messages_users WHERE user_id = ? AND `read` = 0)', [$user->data()->id]);
        if ($messages->count()) {
            foreach ($messages->results() as $result) {
                $return['notifications'][] = [
                    'type' => 'message',
                    'url' => URL::getSelfURL() . ltrim(URL::build('/user/messaging/', 'action=view&message=' . urlencode($result->id)), '/'),
                    'message_short' => $result->title,
                    'message' => $result->title
                ];
            }
        }

        $api->returnArray($return);
    }
}
