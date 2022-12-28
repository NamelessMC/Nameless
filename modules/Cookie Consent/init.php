<?php
declare(strict_types=1);
/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Cookie Consent initialisation file
 *
 * @var Language $language
 * @var Pages $pages
 */

require_once ROOT_PATH . '/modules/Cookie Consent/module.php';

try {
    $cookie_language = new Language(ROOT_PATH . '/modules/Cookie Consent/language');
} catch (Exception $ignored) {
}

$module = new CookieConsent_Module($language, $cookie_language ?? null, $pages);
