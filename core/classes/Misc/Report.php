<?php
/**
 * Report creation class
 *
 * @package NamelessMC\Misc
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class Report {

    public const ORIGIN_WEBSITE = 0;
    public const ORIGIN_API = 1;

    /**
     * Create a report.
     *
     * @param Language $language Language to use for messages.
     * @param User $user_reporting User making the report.
     * @param User $reported_user User being reported.
     * @param array $data Array containing report data.
     */
    public static function create(Language $language, User $user_reporting, User $reported_user, array $data): void {
        $db = DB::getInstance();

        $db->insert('reports', array_merge($data, [
            'date_reported' => date('Y-m-d H:i:s'),
            'date_updated' => date('Y-m-d H:i:s'),
            'reported' => date('U'),
            'updated' => date('U'),
        ]));

        $id = $db->lastId();

        // Alert moderators
        $moderator_groups = DB::getInstance()->query('SELECT id FROM nl2_groups WHERE permissions LIKE \'%"modcp.reports":1%\'')->results();

        if (count($moderator_groups)) {
            $groups = '(';
            foreach ($moderator_groups as $group) {
                if (is_numeric($group->id)) {
                    $groups .= ((int)$group->id) . ',';
                }
            }
            $groups = rtrim($groups, ',') . ')';

            $moderators = DB::getInstance()->query('SELECT DISTINCT(nl2_users.id) AS id FROM nl2_users LEFT JOIN nl2_users_groups ON nl2_users.id = nl2_users_groups.user_id WHERE group_id in ' . $groups)->results();

            if (count($moderators)) {
                foreach ($moderators as $moderator) {
                    Alert::create($moderator->id, 'report', ['path' => 'core', 'file' => 'moderator', 'term' => 'report_alert'], ['path' => 'core', 'file' => 'moderator', 'term' => 'report_alert'], URL::build('/panel/users/reports/', 'id=' . $id));
                }
            }
        }

        EventHandler::executeEvent(new ReportCreatedEvent(
            $reported_user->data()->username,
            $language->get('general', 'reported_by', ['author' => $user_reporting->data()->username]),
            $data['report_reason'],
            $data['reported_id'] == 0 ? null : ($data['reported_uuid'] !== null ? AvatarSource::getAvatarFromUUID($data['reported_uuid']) : $reported_user->getAvatar()),
            $language->get('general', 'view_report'),
            rtrim(URL::getSelfURL(), '/') . URL::build('/panel/users/reports/', 'id=' . $id),
        ));
    }
}
