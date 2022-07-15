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
     * @return never
     */
    public static function to(string $location): void {
        if (!headers_sent()) {
            header("Location: $location");
        } else {
            // `attribute data-cfasync="false"` fixes Cloudflare caching issues
            echo '<script data-cfasync="false">window.location.replace("' . Output::getClean($location) . '");</script>';
        }
        die();
    }

    /**
     * Attempt to redirect the user to the previous page.
     *
     * @return never
     */
    public static function back(): void {
        if (isset($_SESSION['last_page'])) {
            self::to($_SESSION['last_page']);
        }

        if (isset($_SESSION['HTTP_REFERER'])) {
            self::to($_SESSION['HTTP_REFERER']);
        }

        self::to(URL::build('/'));
    }
}
