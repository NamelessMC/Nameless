<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  UserCP navbar generation
 */

$smarty->assign([
    'CC_NAV_LINKS' => $cc_nav->returnNav('top')
]);