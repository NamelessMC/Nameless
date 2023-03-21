<?php

interface WebhookDispatcher {

    /**
     * @param AbstractEvent|array $event Event to execute, or array of params if event is not object based
     * @param string $webhook_url Webhook URL to use, if not provided in event
     */
    public static function execute($event, string $webhook_url = '');

}
