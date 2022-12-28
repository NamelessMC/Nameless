<?php
declare(strict_types=1);
/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
 *
 *  License: MIT
 *
 *  TODO: Description
 *
 * @var Language $language
 */

if (isset($_POST['perform']) && $_POST['perform'] === 'true') {
    try {
        if ($_GET['initialise'] === 'db') {
            $message = PhinxAdapter::migrate();

            $redirect_url = (($_SESSION['action'] === 'install') ? '?step=site_configuration' : '?step=upgrade');

            $json = [
                'message' => $language->get('installer', 'database_configured'),
                'redirect_url' => $redirect_url,
            ];

            if (!str_contains($message, 'All Done')) {
                $json['error'] = $message;
            } else {
                $_SESSION['database_initialized'] = true;
            }

        } else if ($_GET['initialise'] === 'site') {
            DatabaseInitializer::runPreUser();

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
