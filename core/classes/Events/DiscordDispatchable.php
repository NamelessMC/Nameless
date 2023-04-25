<?php
/**
 * Represents an event which is able to be sent as a Discord embed.
 *
 * @package NamelessMC\Events
 * @author Aberdeener
 * @version 2.2.0
 * @license MIT
 */
interface DiscordDispatchable {

    /**
     * Build a Discord webhook to represent the event as a Discord embed.
     *
     * @return DiscordWebhookBuilder The webhook builder to send the event as an embed
     */
    public function toDiscordWebhook(): DiscordWebhookBuilder;

}
