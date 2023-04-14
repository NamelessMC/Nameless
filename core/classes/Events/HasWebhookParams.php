<?php
/**
 * Represents an event which has specific parameters to send to a webhook.
 *
 * @package NamelessMC\Events
 * @author Aberdeener
 * @version 2.2.0
 * @license MIT
 */
interface HasWebhookParams {

    /**
     * @return array Array of parameters to send to the webhook
     */
    public function webhookParams(): array;

}
