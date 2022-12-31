<?php

/**
 * TODO: Add description
 *
 * @package Modules\Core\Endpoints
 * @author UNKNOWN
 * @version UNKNOWN
 * @license MIT
 */
class GetUserAnnouncementsEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users/{user}/announcements';
        $this->_module = 'Core';
        $this->_description = 'Return latest available announcements for the supplied user';
        $this->_method = 'GET';
    }

    /**
     * @param Nameless2API $api
     * @param User $user
     *
     * @return void
     */
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
                // TODO: Does this decode into a sequential or associative array?
                'pages' => json_decode($announcement->pages),
                // TODO: Does this decode into a sequential or associative array?
                'groups' => array_map('intval', json_decode($announcement->groups)),
            ];
        }

        $api->returnArray(['announcements' => $user_announcements]);
    }
}
