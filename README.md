# Imager X storage driver for Microsoft Azure Blob Storage

This is an external storage driver for Imager X that uploads your Imager X transforms to Microsoft Azure Blob Storage.

## Requirements

This plugin is compatible with Craft CMS 3, 4 and 5, and Imager X 3.0 or later. External storages are only available in the Pro edition of Imager X.

## Installation

To install the plugin, follow these instructions:

1. Install with composer via `composer require discoverlunar/craft-imager-x-azure-blob-storage` from your project directory.
2. Install the plugin in the Craft Control Panel under Settings > Plugins, or from the command line via `./craft install/plugin craft-imager-x-azure-blob-storage`.

## Configuration

Configure the storage driver by adding new key named `azure` to the `storagesConfig` config setting in your **imager-x.php config file**, with the following configuration:

    'storageConfig' => [
        'azure' => [
            'endpoint' => '',
            'connectionString' => '',
            'container' => '',
            'folder' => '',
            'requestHeaders' => [],
        ]
    ],

Enable the storage driver by adding the key `azure` to Imager's `storages` config setting:

    'storages' => ['azure'],

Here's an example config, note that the endpoint has to be a complete URL with scheme, and as always you need to make sure that `imagerUrl` is pointed to the right location:

    'imagerUrl' => 'https://foo.blob.core.windows.net/site/imager/',
    'storages' => ['azure'],
    'storageConfig' => [
        'azure'  => [
            'endpoint' => 'https://foo.blob.core.windows.net/site/',
            'connectionString' => 'MY_CONNECTION_STRING',
            'container' => 'MY_CONTAINER',
            'folder' => 'imager',
            'requestHeaders' => [],
        ]
    ],

Also remember to always empty your Imager transforms cache when adding or removing external storages, as the transforms won't be uploaded if the transform already exists in the cache.


Price, license and support
---
This plugin continues the work done in [paragonn/craft-imager-x-azure-blob](https://github.com/paragonn/craft-imager-x-azure-blob), which wasn't updated to support Craft CMS 4 and later Craft CMS 5.
As before, the plugin is released under the MIT license. It requires Imager X Pro, which is a commercial plugin [available in the Craft plugin store](https://plugins.craftcms.com/imager-x).