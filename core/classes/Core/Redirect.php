<?php
/**
 * Redirect class
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @author computerwizjared
 * @version 2.0.0-pr8
 * @license MIT
 */
class Redirect {

    /**
     * Redirect the user to the specified location.
     *
     * @param string $location Path or URL to redirect to
     */
    public static function to(string $location): void {
        // `attribute data-cfasync="false"` fixes Cloudflare caching issues
        echo '<script data-cfasync="false">window.location.replace("' . str_replace('&amp;', '&', htmlspecialchars($location)) . '");</script>';
        die();
    }
}
