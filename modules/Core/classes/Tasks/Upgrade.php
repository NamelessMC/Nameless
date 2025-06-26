<?php
/**
 * Download and execute update task for NamelessMC
 *
 * @package NamelessMC\Tasks
 * @author Aberdeener
 * @version 2.3.0
 * @license MIT
 */
class Upgrade extends Task
{
    private const DOWNLOAD_TIMEOUT = 30;

    public function run(): string
    {
        try {
            // Acquire lock to prevent concurrent upgrades
            $this->acquireLock();

            Settings::set('maintenance', '1');

            $updateCheck = $this->validateUpdateAvailable();

            $upgradeZipPath = $this->downloadUpgradePackage($updateCheck);

            $this->extractUpgradePackage($upgradeZipPath);

            $this->executeMigrations();

            Settings::set('nameless_version', $updateCheck->versionTag());
            Settings::set('version_update', null);

            $cache = $this->_container->get(Cache::class);
            $cache->setCache('update_check');
            $cache->store('update_check', null);
        } catch (Exception $e) {
            $this->setOutput(['error' => $e->getMessage()]);

            return Task::STATUS_FAILED;
        } finally {
            // Ensure the lock is released even if an error occurs
            $this->releaseLock();

            Settings::set('maintenance', '0');
        }

        return Task::STATUS_COMPLETED;
    }

    private function acquireLock(): void
    {
        $lockFile = ROOT_PATH . '/cache/upgrade.lock';
        if (file_exists($lockFile)) {
            throw new Exception('Upgrade is already running');
        }

        if (!file_put_contents($lockFile, 'locked')) {
            throw new Exception('Failed to create lock file');
        }
    }

    private function releaseLock(): void
    {
        $lockFile = ROOT_PATH . '/cache/upgrade.lock';
        if (file_exists($lockFile)) {
            unlink($lockFile);
        }
    }

    private function validateUpdateAvailable(): UpdateCheck
    {
        $cache = $this->_container->get(Cache::class);

        $cache->setCache('update_check');
        $updateCheck = $cache->retrieve('update_check');

        if (!$updateCheck) {
            throw new Exception('No update found');
        }

        $this->setOutput(['update_check' => "Found update: {$updateCheck->versionTag()}"]);
        return $updateCheck;
    }

    private function downloadUpgradePackage(UpdateCheck $updateCheck): string
    {
        $upgradeZipPath = $this->getTempDirectory() . "/namelessmc-upgrade-{$updateCheck->versionTag()}.zip";

        $downloadResponse = HttpClient::get($updateCheck->upgradeZipLink(), [
            'sink' => $upgradeZipPath,
            'timeout' => self::DOWNLOAD_TIMEOUT,
        ]);

        if ($downloadResponse->hasError()) {
            throw new Exception("Error downloading upgrade zip: {$downloadResponse->getError()}");
        }

        $this->setOutput([
            'zip_download' => "Downloaded upgrade zip to: {$upgradeZipPath}"
        ]);

        $this->verifyChecksum($upgradeZipPath, $updateCheck);

        return $upgradeZipPath;
    }

    public function extractUpgradePackage(string $upgradeZipPath): void
    {
        $zip = new ZipArchive();
        if ($zip->open($upgradeZipPath) !== true) {
            throw new Exception("Failed to open upgrade zip: {$upgradeZipPath}");
        }

        if (!$zip->extractTo(ROOT_PATH)) {
            throw new Exception("Failed to extract upgrade zip: {$upgradeZipPath}");
        }

        $zip->close();

        // Remove the zip file after extraction
        unlink($upgradeZipPath);

        $this->setOutput(['zip_extract' => 'Upgrade package extracted successfully']);
    }

    private function verifyChecksum(string $upgradeZipPath, UpdateCheck $updateCheck): void
    {
        $expectedChecksum = $updateCheck->checksum();

        // TODO: Remove before merging
        if (empty($expectedChecksum)) {
            $this->setOutput([
                'checksum_verify' => 'No checksum provided for verification, skipping checksum check'
            ]);
            return;
        }

        $actualChecksum = hash_file('sha256', $upgradeZipPath);
        if ($actualChecksum === false) {
            throw new Exception("Failed to calculate checksum for file: {$upgradeZipPath}");
        }

        if (hash_equals($expectedChecksum, $actualChecksum)) {
            $this->setOutput([
                'checksum_verify' => "Checksum verification passed (SHA256: {$actualChecksum})"
            ]);
            return;
        }

        // Remove the corrupted file
        unlink($upgradeZipPath);

        throw new Exception("Checksum verification failed: expected {$expectedChecksum}, got {$actualChecksum}");
    }

    private function executeMigrations(): void
    {
        $phinxWrapper = new Phinx\Wrapper\TextWrapper(
            new Phinx\Console\PhinxApplication(),
            [
                'configuration' => ROOT_PATH . '/core/migrations/phinx.php',
            ]
        );

        $output = $phinxWrapper->getMigrate();

       if ($phinxWrapper->getExitCode() !== 0) {
            throw new Exception("Migrations failed: {$output}");
        }

        $this->setOutput([
            'migrations' => $output,
        ]);
    }

    private function getTempDirectory(): string
    {
        $tmpDir = ini_get('upload_tmp_dir');
        return $tmpDir ?: sys_get_temp_dir();
    }
}
