<?php
/**
 * External storage driver for Imager X that integrates with Microsoft Azure Blob Storage
 *
 * @link      https://lunar.be/
 * @copyright Copyright (c) 2025 William Blommaert
 */

namespace discoverlunar\ImagerXAzureBlobStorage;

use yii\base\Event;
use craft\base\Plugin;
use spacecatninja\imagerx\ImagerX;
use spacecatninja\imagerx\events\RegisterExternalStoragesEvent;
use discoverlunar\ImagerXAzureBlobStorage\ExternalStorage\AzureStorage;

/**
 * @author    William Blommaert
 * @package   ImagerXAzureBlobStorage
 * @since     3.3.0
 *
 */
class ImagerXAzureBlobStorage extends Plugin
{
    public static $plugin;

    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            ImagerX::class,
            ImagerX::EVENT_REGISTER_EXTERNAL_STORAGES,
            static function (RegisterExternalStoragesEvent $event) {
            $event->storages['azure'] = AzureStorage::class;
        });
    }
}
