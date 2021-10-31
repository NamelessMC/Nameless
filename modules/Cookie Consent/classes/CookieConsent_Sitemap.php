<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  CookieConsent module Sitemap method
 */

use SitemapPHP\Sitemap;

class CookieConsent_Sitemap {

    /**
     * Generate sitemap for the Cookie Consent module.
     *
     * @param ?Sitemap $sitemap Instance of sitemap generator.
     */
    public static function generateSitemap(Sitemap $sitemap = null): void {
        if (!$sitemap)
            return;

        $sitemap->addItem(URL::build('/cookies'), 0.9);
    }
}
