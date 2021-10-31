<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  Cookie Consent initialisation file
 */

require_once ROOT_PATH . '/modules/Cookie Consent/autoload.php';

$cookie_language = new Language(ROOT_PATH . '/modules/Cookie Consent/language', LANGUAGE);

$module = new CookieConsent_Module($language, $cookie_language, $pages);
