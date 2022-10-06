<?php

/**
 * @param string $name The name of the webhook
 * @param string $url The url of the webhook
 * @param string $type The webhook type
 * @param array $events A list of events the webhook should receive
 *
 * @return string JSON Array
 */
class CreateWebhooksEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'webhooks/create';
        $this->_module = 'Core';
        $this->_description = 'Create a new webhook';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API  $api): void {
        // Validation
        $api->validateParams($_POST, ['name', 'url', 'type', 'events']);
        $validation = Validate::check($_POST, [
            'name' => [
                Validate::REQUIRED => true,
                Validate::MIN => 3,
                Validate::MAX => 128
            ],
            'url' => [
                Validate::REQUIRED => true,
                Validate::MIN => 10,
                Validate::MAX => 2048
            ],
            'type' => [
                Validate::REQUIRED => true,
            ]
        ])->messages([
            'name' => CoreApiErrors::ERROR_WEBHOOK_NAME_INCORRECT_LENGTH,
            'url' => CoreApiErrors::ERROR_WEBHOOK_URL_INCORRECT_LENGTH
        ]);

        // If it didn't pass, throw the errors
        if (!$validation->passed()) {
            foreach ($validation->errors() as $error) {
                $api->throwError($error);
            }
        }

        // Insert into database
        $name = $_POST['name'];
        $url = $_POST['url'];
        $type = $_POST['type'];
        $events = $_POST['events'];

        DB::getInstance()->insert('hooks', [
            'name' => $name,
            'action' => $type,
            'url' => $url,
            'events' => json_encode($events)
        ]);

        // Clear cache so the webhooks are refreshed

        // Annoying that there isn't a global way to get the nameless cache
        $cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);
        $cache->setCache('hooks');
        if ($cache->isCached('hooks')) {
            $cache->erase('hooks');
        }

        // Return status message
        $api->returnArray(['message' => $api->getLanguage()->get('api', 'webhook_added')]);
    }
}