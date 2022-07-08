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
class CreateReportEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'reports/create';
        $this->_module = 'Core';
        $this->_description = 'Create a report';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api): void {
        $api->validateParams($_POST, ['reporter', 'content']);

        // Ensure either reported OR reported_username AND reported_uid are provided
        if (!$_POST['reported'] && !($_POST['reported_username'] && $_POST['reported_uid'])) {
            $api->throwError(Nameless2API::ERROR_CANNOT_FIND_USER);
        }

        // Ensure content is correct length
        if (strlen($_POST['content']) > 255) {
            $api->throwError(CoreApiErrors::ERROR_REPORT_CONTENT_TOO_LONG);
        }

        // Ensure user reporting has website account, and has not been banned
        $user_reporting = $api->getUser('id', $_POST['reporter']);
        $user_reporting_data = $user_reporting->data();

        if ($user_reporting_data->isbanned) {
            $api->throwError(CoreApiErrors::ERROR_BANNED_FROM_WEBSITE);
        }

        // See if reported user exists
        $user_reported_id = $api->getDb()->get('users', ['id', (int)$_POST['reported']]);
        if (!$user_reported_id->count()) {
            $user_reported_id = null;
        } else {
            $user_reported_id = $user_reported_id->first()->id;
        }

        if ($user_reporting_data->id == $user_reported_id) {
            $api->throwError(CoreApiErrors::ERROR_CANNOT_REPORT_YOURSELF);
        }

        // Ensure user has not already reported the same player, and the report is open
        $user_reports = $api->getDb()->get('reports', ['reporter_id', $user_reporting_data->id])->results();
        foreach ($user_reports as $report) {
            if ($report->status == 1) {
                continue;
            }
            if ((
                $report->reported_id != null && $report->reported_id == $user_reported_id)
                || (isset($_POST['reported_uid']) && $report->reported_uuid == $_POST['reported_uid'])
            ) {
                $api->throwError(CoreApiErrors::ERROR_OPEN_REPORT_ALREADY);
            }
        }

        $reported_user = new User($user_reported_id);
        if ($reported_user->exists()) {
            $integrationUser = $reported_user->getIntegration('Minecraft');
            if ($integrationUser != null) {
                $reported_uuid = $integrationUser->data()->identifier;
            }
        }

        Report::create($api->getLanguage(), $user_reporting, $reported_user, [
            'type' => Report::ORIGIN_API,
            'reporter_id' => $user_reporting_data->id,
            'reported_id' => $user_reported_id,
            'report_reason' => $_POST['content'],
            'updated_by' => $user_reporting_data->id,
            'reported_mcname' => $_POST['reported_username'] ? $_POST['reported_username'] : $reported_user->getDisplayname(),
            'reported_uuid' => $_POST['reported_uid'] ? $_POST['reported_uid'] : $reported_uuid ?? 'none',
        ]);

        $api->returnArray(['message' => $api->getLanguage()->get('api', 'report_created')], 201);
    }
}
