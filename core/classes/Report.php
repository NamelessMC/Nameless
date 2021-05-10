<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Report class
 */
class Report {

    /** @var DB */
    private $_db;

    // Construct Report class
    public function __construct() {
        $this->_db = DB::getInstance();
    }

    /**
     * Create a report.
     *
     * @param array $post Array containing fields.
     */
    public function create($post = array()) {
        // Insert into database
        if (!$this->_db->insert('reports', $post)) {
            throw new Exception('There was a problem creating the report.');
        }

        $id = $this->_db->lastId();

        // Alert moderators
        $moderator_groups = DB::getInstance()->query('SELECT id FROM nl2_groups WHERE permissions LIKE \'%"modcp.reports":1%\'')->results();

        if (count($moderator_groups)) {
            $groups = '(';
            foreach ($moderator_groups as $group) {
                if (is_numeric($group->id)) {
                    $groups .= ((int) $group->id) . ',';
                }
            }
            $groups = rtrim($groups, ',') . ')';

            $moderators = DB::getInstance()->query('SELECT DISTINCT(nl2_users.id) AS id FROM nl2_users LEFT JOIN nl2_users_groups ON nl2_users.id = nl2_users_groups.user_id WHERE group_id in ' . $groups)->results();

            if (count($moderators)) {
                foreach ($moderators as $moderator) {
                    Alert::create($moderator->id, 'report', array('path' => 'core', 'file' => 'moderator', 'term' => 'report_alert'), array('path' => 'core', 'file' => 'moderator', 'term' => 'report_alert'), URL::build('/panel/users/reports/', 'id=' . $id));
                }
            }
        }
    }
}
