<?php

/**
 * @param int $id NamelessMC ID of the user whose announcements to view
 * 
 * @return string JSON Array of latest announcements
 */
// TODO: add another layer of protection. What if anyone can enter a user id and read staff only announcements etc
class GetAnnouncementsEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'getAnnouncements';
        $this->_module = 'Core';
        $this->_description = 'Return latest available announcements for the supplied user';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {

            $tempUser = null;
            if (isset($_GET['id']) && is_numeric($_GET['id'])) $tempUser = new User($_GET['id']);

            $announcements = array();

            foreach (Announcements::getAvailable('api', null, !is_null($tempUser) ? $tempUser->data()->group_id : 0, !is_null($tempUser) ? $tempUser->data()->secondary_groups : null) as $announcement) {
                $announcements[$announcement->id]['pages'] = json_decode($announcement->pages);
                $announcements[$announcement->id]['groups'] = json_decode($announcement->groups);
                $announcements[$announcement->id]['header'] = Output::getClean($announcement->header);
                $announcements[$announcement->id]['message'] = Output::getPurified($announcement->message);
            }   

            $api->returnArray(array('announcements' => $announcements));
        } else $api->throwError(1, $api->getLanguage()->get('api', 'invalid_api_key'));
    }
}