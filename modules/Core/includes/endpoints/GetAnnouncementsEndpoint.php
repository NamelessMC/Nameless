<?php

/**
 * @param int $id NamelessMC ID of the user whose announcements to view
 *
 * @return string JSON Array of latest announcements
 */
class GetAnnouncementsEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'getAnnouncements';
        $this->_module = 'Core';
        $this->_description = 'Return latest available announcements for the supplied user';
        $this->_method = 'GET';
    }

    public function execute(Nameless2API $api) {
        if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
            $tempUser = $api->getUser('id', $_GET['user_id']);
        } else {
            $tempUser = null;
        }

        $user_announcements = [];

        $announcements = new Announcements(
            new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/'])
        );

        foreach ($announcements->getAvailable('api', null, $tempUser != null ? $tempUser->getAllGroupIds(false) : [0]) as $announcement) {
            $user_announcements[] = [
                'id' => (int)$announcement->id,
                'header' => Output::getClean($announcement->header),
                'message' => Output::getPurified($announcement->message),
                'pages' => json_decode($announcement->pages),
                'groups' => array_map('intval', json_decode($announcement->groups)),
            ];
        }

        $api->returnArray(['announcements' => $user_announcements]);
    }
}
