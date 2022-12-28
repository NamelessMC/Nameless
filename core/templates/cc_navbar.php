<?php
declare(strict_types=1);
/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  UserCP navbar generation
 *
 * @var Smarty $smarty
 * @var Navigation $cc_nav
 */

$smarty->assign([
    'CC_NAV_LINKS' => $cc_nav->returnNav()
]);
