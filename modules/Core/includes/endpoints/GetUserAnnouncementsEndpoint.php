<?php
declare(strict_types=1);

/**
 * TODO: Add description
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
                'pages' => json_decode($announcement->pages, true),
                'groups' => array_map('intval', json_decode($announcement->groups, true)),
            ];
        }

        $api->returnArray(['announcements' => $user_announcements]);
    }
}
