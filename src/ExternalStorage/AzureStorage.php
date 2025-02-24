<?php

namespace discoverlunar\ImagerXAzureBlobStorage\ExternalStorage;

use Craft;
use craft\helpers\App;
use craft\helpers\FileHelper;
use InvalidArgumentException;
use League\Flysystem\FilesystemException;
use spacecatninja\imagerx\services\ImagerService;
use spacecatninja\imagerx\externalstorage\ImagerStorageInterface;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;
use League\Flysystem\Filesystem;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use Throwable;

class AzureStorage implements ImagerStorageInterface
{
    /**
     * @param string $file
     * @param string $uri
     * @param bool $isFinal
     * @param array $settings
     * @return bool
     */
    public static function upload(string $file, string $uri, bool $isFinal, array $settings): bool
    {
        $config = ImagerService::getConfig();
        $connectionString = App::parseEnv($settings['connectionString']);

        try {
            $client = static::client($connectionString);
        } catch (InvalidArgumentException $e) {
            Craft::error('Invalid configuration of Azure Client: ' . $e->getMessage(), __METHOD__);
            return false;
        }

        if (isset($settings['folder']) && $settings['folder'] !== '') {
            $uri = ltrim(FileHelper::normalizePath(App::parseEnv($settings['folder']) . '/' . $uri), '/');
        }

        $uri = str_replace('\\', '/', $uri);
        try {
            $adapter = Craft::$container->get(AzureBlobStorageAdapter::class, [$client, App::parseEnv($settings['container'])]);
        } catch (Throwable $e) {
            Craft::error("Failed to initialize Azure Blob Storage adapter: {$e->getMessage()}");
            return false;
        }

        try {
            $filesystem = Craft::$container->get(Filesystem::class, [$adapter]);
        } catch (Throwable $e) {
            Craft::error("Failed to initialize Azure Blob Storage filesystem: {$e->getMessage()}");
            return false;
        }

        $stream = fopen($file, 'rb+');

        try {
            $filesystem->writeStream($uri, $stream);
            return true;
        } catch (FilesystemException $e) {
            Craft::error("Failed to write stream to Azure Blob Storage: {$e->getMessage()}");
            return false;
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }

    /**
     * @param string $connectionString Connection string to use
     * @return BlobRestProxy
     */
    protected static function client(string $connectionString): BlobRestProxy
    {
        return BlobRestProxy::createBlobService($connectionString);
    }
}
