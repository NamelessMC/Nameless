<?php

require_once ROOT_PATH . '/modules/Minecraft Integration/module.php';

$minecraft_language = new Language(ROOT_PATH . '/modules/Minecraft Integration/language');

$module = new Minecraft_Module($language, $minecraft_language, $pages, $endpoints);
