<?php
if (isset($_POST['perform']) && $_POST['perform'] == 'true') {
    try {
        if ($_GET['initialise'] === 'db') {
            $message = PhinxAdapter::migrate();
            $json = [
                'message' => $language->get('installer', 'database_configured'),
                'redirect_url' => '?step=site_configuration',
            ];

            if (!str_contains($message, 'All Done')) {
                $json['error'] = $message;
            } else {
                $_SESSION['database_initialized'] = true;
            }

        } else {
            if ($_GET['initialise'] === 'site') {
                DatabaseInitialiser::runPreUser();

                $json = [
                    'success' => true,
                    'redirect_url' => '?step=admin_account_setup',
                ];

                $_SESSION['site_initialized'] = true;

            } else if ($_GET['initialise'] === 'upgrade') {
                define('UPGRADE', true);

                require(dirname(__DIR__) . '/includes/upgrade_perform.php');

                $json = [
                    'success' => !isset($errors) || !count($errors),
                    'errors' => $errors ?? [],
                    'message' => $language->get('installer', 'upgrade_error'),
                    'redirect_url' => '?step=finish',
                ];

                $_SESSION['admin_setup'] = true;

            } else {
                throw new RuntimeException('Invalid initialisation');
            }
        }
    } catch (Exception $e) {
        $json = [
            'error' => true,
            'message' => $e->getMessage(),
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($json);
    die();
}