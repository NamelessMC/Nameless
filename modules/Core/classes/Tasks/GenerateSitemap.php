<?php

class GenerateSitemap extends Task {

    public function run(): string {
        $cache = $this->_container->get('Cache');
        $language = $this->_container->get('Language');
        $pages = $this->_container->get('Pages');

        $errors = [];
        $index = rtrim(URL::getSelfURL(), '/') .
                 (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/cache/sitemaps/';
        $path = ROOT_PATH . '/cache/sitemaps/';

        if (!is_dir($path)) {
            if (!is_writable(ROOT_PATH . '/cache')) {
                $this->setOutput(['errors' => [$language->get('admin', 'sitemap_not_writable')]]);
                return Task::STATUS_ERROR;
            } else {
                mkdir($path);
                file_put_contents(ROOT_PATH . '/cache/sitemaps/.htaccess', 'Allow from all');
            }
        }

        $sitemap = new \SitemapPHP\Sitemap(rtrim(URL::getSelfURL(), '/'));
        $sitemap->setPath($path);

        $methods = $pages->getSitemapMethods();

        foreach ($methods as $method) {
            if (!class_exists($method[0])) {
                $errors[] = $language->get(
                    'admin',
                    'unable_to_load_sitemap_file_x',
                    ['file' => Output::getClean($method[0])]
                );
                continue;
            }

            $method($sitemap, $cache);
        }

        $sitemap->createSitemapIndex($index);

        $cache->setCache('sitemap_cache');
        $cache->store('updated', date(DATE_FORMAT));

        $this->setOutput(['result' => ['url' => $index], 'errors' => $errors]);
        $this->reschedule($language);

        return Task::STATUS_COMPLETED;
    }

    private function reschedule(Language $language) {
        Queue::schedule((new GenerateSitemap())->fromNew(
            Module::getIdFromName('Core'),
            $language->get('admin', 'generate_sitemap'),
            [],
            Date::next()->getTimestamp()
        ));
    }

    public static function schedule(Language $language) {
        (new self())->reschedule($language);
    }
}
