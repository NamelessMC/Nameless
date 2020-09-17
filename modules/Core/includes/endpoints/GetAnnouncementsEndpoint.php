<?php

/**
 * Return latest available announcements for the supplied user
 * 
 * @param int $id NamelessMC ID of the user whose announcements to view
 * 
 * @return string JSON Array of latest announcements
 */
class GetAnnouncementsEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'getAnnouncements';
        $this->_module = 'Core';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {

            $tempUser = null;

            if (isset($_GET['id'])) {
                $tempUser = new User($_GET['id']);
            }

            $announcements = array();

            foreach (Announcements::getAvailable('api', null, !is_null($tempUser) ? $tempUser->data()->group_id : 0, !is_null($tempUser) ? $tempUser->data()->secondary_groups : null) as $announcement) {
                $announcements[] = $announcement;
            }   

            $api->returnArray(array('announcements' => $announcements));
        } else $api->throwError(1, $api->getLanguage()->get('api', 'invalid_api_key'));
    }
}