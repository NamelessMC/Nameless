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
     * @param string $location Path or URL to redirect to, or (int) 404 if the page is not found.
     */
    public static function to($location = null) {
        // Check the location is actually set
        if ($location) {
            // It is set
            if (is_numeric($location)) {
                switch ($location) {
                    // 404 request?
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        require(ROOT_PATH . '/404.php');
                        die();
                    break;
                }
            }

            // Javascript redirect
            // Tag attribute data-cfasync="false" fixes Cloudflare caching issues (credit @computerwizjared)
            echo '<script data-cfasync="false">window.location.replace("' . str_replace('&amp;', '&', htmlspecialchars($location)) . '");</script>';

            // Kill script in case user has disabled Javascript
            die();
        }
    }
}
