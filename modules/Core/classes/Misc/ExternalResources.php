<?php
/**
 * Fetches external resources for NamelessMC from the NamelessMC website API.
 *
 * @package Modules\Core\Misc
 * @author Aberdeener
 * @version 2.3.0
 * @license MIT
 */
class ExternalResources extends Instanceable
{
    private const API_ROOT_URL = 'http://nameless_test.test/index.php?route=/api/v2/resources/';

    private const MODULES_CATEGORY_ID = 1; // "NamelessMC v2 Modules" category
    private const TEMPLATES_CATEGORY_ID = 2; // "NamelessMC v2 Templates" category

    public function __construct(
        private Cache $cache
    ) {}

    // Add "last updated" and a "refresh" button to the resources page

    public function bustCache(): void
    {
        $this->cache->setCache('external_resources');
        $this->cache->eraseAll();
    }

    public function getAllCategories(): array
    {
        $this->cache->setCache('external_resources');

        return $this->cache->fetch('categories', function () {
            $response = HttpClient::get(self::API_ROOT_URL . 'categories');
            if ($response->hasError()) {
                throw new Exception('Unable to retrieve categories: ' . $response->getError());
            }

            return $response->json(true)['categories'];
        }, 3600); // Cache for 1 hour
    }

    public function getAllModules(): array
    {
        $this->cache->setCache('external_resources');

        return $this->cache->fetch('modules', function () {
            $response = HttpClient::get(self::API_ROOT_URL . 'resources&category=' . self::MODULES_CATEGORY_ID);
            if ($response->hasError()) {
                throw new Exception('Unable to retrieve modules: ' . $response->getError());
            }

            return $response->json(true)['resources'];
        }, 3600); // Cache for 1 hour
    }

    public function getAllTemplates(): array
    {
        $this->cache->setCache('external_resources');

        return $this->cache->fetch('templates', function () {
            $response = HttpClient::get(self::API_ROOT_URL . 'resources&category=' . self::TEMPLATES_CATEGORY_ID);
            if ($response->hasError()) {
                throw new Exception('Unable to retrieve templates: ' . $response->getError());
            }

            return $response->json(true)['resources'];
        }, 3600); // Cache for 1 hour
    }

    public function installResource(int $resourceId): void
    {
        $zipPath = $this->downloadResource($resourceId);
        $extractedDir = $this->extractZip($zipPath, $resourceId);
        $this->moveExtractedFiles($extractedDir);
    }

    private function downloadResource(int $resourceId): string
    {
        $cacheDir = ROOT_PATH . '/cache/external_resources';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $zipPath = $cacheDir . '/' . $resourceId . '-' . time() . '.zip';

        HttpClient::get(self::API_ROOT_URL . "resource/{$resourceId}/download", [
            'sink' => $zipPath,
        ]);

        return $zipPath;
    }

    private function extractZip(string $zipPath, int $resourceId): string
    {
        $extractTo = dirname($zipPath) . '/' . $resourceId;
        $zip = new ZipArchive();
        if ($zip->open($zipPath) === true) {
            $zip->extractTo($extractTo);
            $zip->close();
        } else {
            throw new Exception('Unable to extract resource zip file.');
        }

        return $extractTo;
    }

    private function moveExtractedFiles(string $extractedDir): void
    {
        // recurse the extracted directory the path above where module.php is located, or two above where template.php is located
    }
}
