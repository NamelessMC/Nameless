<?php

require_once(ROOT_PATH  . '/modules/Discord/module.php');
require_once(ROOT_PATH . '/modules/Discord/classes/Discord.php');


$module = new Discord_Module($language, $pages, $queries, $endpoints);