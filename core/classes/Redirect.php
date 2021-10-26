<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Redirect class
 */
class Redirect {

    /**
     * Redirect the user to the specified location
     *
     * @param string $location Path or URL to redirect to
     */
    public static function to(string $location) {
        // Javascript redirect
        // Tag attribute data-cfasync="false" fixes Cloudflare caching issues (credit @computerwizjared)
        echo '<script data-cfasync="false">window.location.replace("' . str_replace('&amp;', '&', htmlspecialchars($location)) . '");</script>';

        // Kill script in case user has disabled Javascript
        die();
    }
}
