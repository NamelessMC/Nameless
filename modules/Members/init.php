<?php

require_once ROOT_PATH . '/modules/Members/module.php';

$member_language = new Language(ROOT_PATH . '/modules/Members/language');

$module = new Members_Module($language, $member_language, $pages);
