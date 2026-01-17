<?php

interface WebhookDispatcher
{
    /**
     * @param AbstractEvent $event       Event to execute
     * @param string        $webhook_url Webhook URL to use
     */
    public static function execute(AbstractEvent $event, string $webhook_url);
}
