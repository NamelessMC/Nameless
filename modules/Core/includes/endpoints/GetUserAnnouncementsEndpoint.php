<?php

/**
 * @param int $id NamelessMC ID of the user whose announcements to view
 *
 * @return string JSON Array of the latest announcements
 */
class GetUserAnnouncementsEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users/{user}/announcements';
        $this->_module = 'Core';
        $this->_description = 'Return latest available announcements for the supplied user';
        $this->_method = 'GET';
    }

    public function execute(Nameless2API $api, User $user): void {
        $user_announcements = [];

        $announcements = new Announcements(
            new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/'])
        );

        foreach ($announcements->getAvailable('api', null, $user->getAllGroupIds()) as $announcement) {
            $user_announcements[] = [
                'id' => $announcement->id,
                'header' => $announcement->header,
                'message' => $announcement->message,
                'pages' => json_decode($announcement->pages),
                'groups' => array_map('intval', json_decode($announcement->groups)),
            ];
        }

        $api->returnArray(['announcements' => $user_announcements]);
    }
}
