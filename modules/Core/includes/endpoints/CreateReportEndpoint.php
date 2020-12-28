<?php

/**
 * @param string $reporter The NamelessMC username of the user who is creating the report
 * @param string $reported The NamelessMC username of the user who is getting reported
 * @param string $content The content of the report
 *
 * @return string JSON Array
 */
class CreateReportEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'createReport';
        $this->_module = 'Core';
        $this->_description = 'Create a report';
    }

    public function execute(Nameless2API $api) {
        $api->validateParams($_POST, ['reporter', 'reported', 'content']);

        // Ensure content is correct length
        if (strlen($_POST['content']) > 255) {
            $api->throwError(19, $api->getLanguage()->get('api', 'report_content_too_long'));
        }

        // Ensure user reporting has website account, and has not been banned
        $user_reporting = $api->getDb()->get('users', array('username', '=', Output::getClean($_POST['reporter'])));
        if (!$user_reporting->count()) {
            $api->throwError(20, $api->getLanguage()->get('api', 'you_must_register_to_report'));
        }

        $user_reporting = $user_reporting->first();
        if ($user_reporting->isbanned) {
            $api->throwError(21, $api->getLanguage()->get('api', 'you_have_been_banned_from_website'));
        }

        // See if reported user exists
        $user_reported = $api->getDb()->get('users', array('username', '=', Output::getClean($_POST['reported'])));
        if (!$user_reported->count()) {
            $api->throwError(16, $api->getLanguage()->get('api', 'unable_to_find_user'));
        }
        $user_reported = $user_reported->first()->id;

        if ($user_reporting->id == $user_reported) {
            $api->throwError(26, $api->getLanguage()->get('api', 'cannot_report_yourself'));
        }

        // Ensure user has not already reported the same player, and the report is open
        $user_reports = $api->getDb()->get('reports', array('reporter_id', '=', $user_reporting->id))->results();
        if (count($user_reports)) {
            foreach ($user_reports as $report) {
                if ($report->reported_id == $user_reported && $report->status == 0) {
                    $api->throwError(22, $api->getLanguage()->get('api', 'you_have_open_report_already'));
                }
            }
        }

        // Create report
        try {
            $report = new Report();
            $report->create(
                array(
                    'type' => 0,
                    'reporter_id' => $user_reporting->id,
                    'reported_id' => $user_reported,
                    'date_reported' => date('Y-m-d H:i:s'),
                    'date_updated' => date('Y-m-d H:i:s'),
                    'report_reason' => Output::getClean($_POST['content']),
                    'updated_by' => $user_reporting->id,
                    'reported' => date('U'),
                    'updated' => date('U')
                )
            );

            $api->returnArray(array('message' => $api->getLanguage()->get('api', 'report_created')));
        } catch (Exception $e) {
            $api->throwError(23, $api->getLanguage()->get('api', 'unable_to_create_report'));
        }
    }
}
