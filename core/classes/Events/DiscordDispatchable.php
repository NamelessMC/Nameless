<?php

interface DiscordDispatchable {

    public function toDiscordWebook(): DiscordWebhookBuilder;

}
