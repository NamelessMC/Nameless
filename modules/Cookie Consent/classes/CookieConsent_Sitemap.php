<?php

use SitemapPHP\Sitemap;

/**
 * CookieConsent sitemap class
 *
 * @package Modules\CookieConsent
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */
class CookieConsent_Sitemap {

    /**
     * Generate sitemap for the Cookie Consent module.
     *
     * @param Sitemap $sitemap Instance of sitemap generator.
     */
    public static function generateSitemap(Sitemap $sitemap): void {
        $sitemap->addItem(URL::build('/cookies'), 0.9);
    }
}
