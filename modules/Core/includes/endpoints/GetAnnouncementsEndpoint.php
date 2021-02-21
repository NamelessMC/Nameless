<?php

/**
 * @param int $id NamelessMC ID of the user whose announcements to view
 * @param string $username NamelessMC sername of the user whose announcements to view
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
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $tempUser = $api->getUser('id', $_GET['id']);
        } else if (isset($_GET['username'])) {
            $tempUser = $api->getUser('username', $_GET['username']);
        } else {
            $tempUser = null;
        }

        $announcements = array();

        foreach (Announcements::getAvailable('api', null, $tempUser != null ? $tempUser->data()->group_id : 0, $tempUser != null ? $tempUser->data()->secondary_groups : null) as $announcement) {
            $announcements[$announcement->id]['pages'] = json_decode($announcement->pages);
            $announcements[$announcement->id]['groups'] = array_map('intval', json_decode($announcement->groups));
            $announcements[$announcement->id]['header'] = Output::getClean($announcement->header);
            $announcements[$announcement->id]['message'] = Output::getPurified($announcement->message);
        }

        $api->returnArray(array('announcements' => $announcements));
    }
}
