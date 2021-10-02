<?php

require_once(ROOT_PATH  . '/modules/Discord Integration/module.php');
require_once(ROOT_PATH . '/modules/Discord Integration/classes/Discord.php');
require_once(ROOT_PATH . '/modules/Discord Integration/classes/DiscordGroupSyncInjector.php');

$module = new Discord_Module($language, $pages, $queries, $endpoints);