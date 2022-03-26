<?php

/**
 * @return string JSON Array of the latest announcements
 */
class GetAnnoucementsEndpoint extends NoAuthEndpoint {

    public function __construct() {
        $this->_route = 'announcements';
        $this->_module = 'Core';
        $this->_description = 'Return latest available announcements for guests';
        $this->_method = 'GET';
    }

    public function execute(Nameless2API $api): void {
        $guest_announcements = [];

        $announcements = new Announcements(
            new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/'])
        );

        foreach ($announcements->getAvailable('api') as $announcement) {
            $guest_announcements[] = [
                'id' => (int) $announcement->id,
                'header' => Output::getClean($announcement->header),
                'message' => Output::getPurified($announcement->message),
                'pages' => json_decode($announcement->pages),
                'groups' => array_map('intval', json_decode($announcement->groups)),
            ];
        }

        $api->returnArray(['announcements' => $guest_announcements]);
    }
}
