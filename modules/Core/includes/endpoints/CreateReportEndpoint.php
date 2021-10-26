<?php

/**
 * @param string $reporter The NamelessMC ID of the user who is creating the report
 * @param string $reported The NamelessMC ID of the user who is getting reported (optional, required if reported_username/reported_uid not provided)
 * @param string $content The content of the report
 * @param string $reported_username The username of the reported user (optional, required if reported not provided)
 * @param string $reported_uid A unique ID for the reported user (optional, required if reported not provided)
 *
 * @return string JSON Array
 */
class CreateReportEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'createReport';
        $this->_module = 'Core';
        $this->_description = 'Create a report';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api) {
        $api->validateParams($_POST, ['reporter', 'content']);

        // Ensure either reported OR reported_username AND reported_uid are provided
        if (!$_POST['reported'] && !($_POST['reported_username'] && $_POST['reported_uid'])) {
            $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));
        }

        // Ensure content is correct length
        if (strlen($_POST['content']) > 255) {
            $api->throwError(19, $api->getLanguage()->get('api', 'report_content_too_long'));
        }

        // Ensure user reporting has website account, and has not been banned
        $user_reporting = $api->getDb()->get('users', array('id', '=', Output::getClean($_POST['reporter'])));
        if (!$user_reporting->count()) {
            $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));
        }

        $user_reporting = $user_reporting->first();
        if ($user_reporting->isbanned) {
            $api->throwError(21, $api->getLanguage()->get('api', 'you_have_been_banned_from_website'));
        }

        // See if reported user exists
        $user_reported_id = $api->getDb()->get('users', array('id', '=', Output::getClean($_POST['reported'])));
        if (!$user_reported_id->count()) {
            $user_reported_id = 0;
        } else {
            $user_reported_id = $user_reported_id->first()->id;
        }

        if ($user_reporting->id == $user_reported_id) {
            $api->throwError(26, $api->getLanguage()->get('api', 'cannot_report_yourself'));
        }

        // Ensure user has not already reported the same player, and the report is open
        $user_reports = $api->getDb()->get('reports', array('reporter_id', '=', $user_reporting->id))->results();
        if (count($user_reports)) {
            foreach ($user_reports as $report) {
                if ((($report->reported_id != 0 && $report->reported_id == $user_reported_id) || $report->reported_uuid == Output::getClean($_POST['reported_uid'])) && $report->status == 0) {
                    $api->throwError(22, $api->getLanguage()->get('api', 'you_have_open_report_already'));
                }
            }
        }

        // Create report
        try {
            $report = new Report();
            $report->create(
                array(
                    'type' => $user_reported_id ? 0 : 1, // TODO: report origin (#2440)
                    'reporter_id' => $user_reporting->id,
                    'reported_id' => $user_reported_id,
                    'date_reported' => date('Y-m-d H:i:s'),
                    'date_updated' => date('Y-m-d H:i:s'),
                    'report_reason' => Output::getClean($_POST['content']),
                    'updated_by' => $user_reporting->id,
                    'reported' => date('U'),
                    'updated' => date('U'),
                    'reported_mcname' => $_POST['reported_username'] ? Output::getClean($_POST['reported_username']) : null,
                    'reported_uuid' => $_POST['reported_uid'] ? Output::getClean($_POST['reported_uid']) : null
                )
            );

            $api->returnArray(array('message' => $api->getLanguage()->get('api', 'report_created')));
        } catch (Exception $e) {
            $api->throwError(23, $api->getLanguage()->get('api', 'unable_to_create_report'), $e->getMessage());
        }
    }
}
