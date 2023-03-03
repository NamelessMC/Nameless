<?php

require_once ROOT_PATH . '/modules/Members/module.php';

$members_language = new Language(ROOT_PATH . '/modules/Members/language');

$module = new Members_Module($language, $members_language, $pages);
