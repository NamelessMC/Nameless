<?php

class CreateWebhooksEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'webhooks/create';
        $this->_module = 'Core';
        $this->_description = 'Create a new webhook';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API  $api): void {
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

        if (!$validation->passed()) {
            $errors = $validation->errors();
            foreach ($errors as $error) {
                $api->throwError($error);
            }
        }

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

        // Annoying that there isn't a global way to get the nameless cache
        $cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);
        $cache->setCache('hooks');
        if ($cache->isCached('hooks')) {
            $cache->erase('hooks');
        }

        $api->returnArray(['message' => $api->getLanguage()->get('api', 'webhook_added')]);
    }
}