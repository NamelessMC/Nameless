<?php
declare(strict_types=1);

/**
 * TODO: Add description
 */
class GetAnnouncementsEndpoint extends NoAuthEndpoint {

    public function __construct() {
        $this->_route = 'announcements';
        $this->_module = 'Core';
        $this->_description = 'Return latest available announcements for guests';
        $this->_method = 'GET';
    }

    /**
     * @param Nameless2API $api
     *
     * @return void
     */
    public function execute(Nameless2API $api): void {
        $guest_announcements = [];

        $announcements = new Announcements(
            new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/'])
        );

        foreach ($announcements->getAvailable('api') as $announcement) {
            $guest_announcements[] = [
                'id' => $announcement->id,
                'header' => $announcement->header,
                'message' => $announcement->message,
                'pages' => json_decode($announcement->pages, true),
                'groups' => array_map('intval', json_decode($announcement->groups, true)),
            ];
        }

        $api->returnArray(['announcements' => $guest_announcements]);
    }
}
