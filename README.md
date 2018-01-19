# Enl\Flysystem\Cloudinary
[![Build Status](https://img.shields.io/travis/enl/flysystem-cloudinary/master.svg?style=flat-square)](https://travis-ci.org/enl/flysystem-cloudinary)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Coverage Status](https://coveralls.io/repos/enl/flysystem-cloudinary/badge.svg?branch=master&service=github&style=flat-square)](https://coveralls.io/github/enl/flysystem-cloudinary?branch=master)

This is a [Flysystem adapter](https://github.com/thephpleague/flysystem) for [Cloudinary API](http://cloudinary.com/documentation/php_integration).

# Installation

```bash
composer require enl/flysystem-cloudinary '~1.0'
```

Or just add the following string to `require` part of your `composer.json`:

```json
{
    "require": {
        "enl/flysystem-cloudinary": "~1.0"
    }
}
```

# Bootstrap

``` php
<?php
use Enl\Flysystem\Cloudinary\ApiFacade as CloudinaryClient;
use Enl\Flysystem\Cloudinary\CloudinaryAdapter;
use League\Flysystem\Filesystem;

include __DIR__ . '/vendor/autoload.php';

$client = new CloudinaryClient([
    'cloud_name' => 'your-cloudname-here',
    'api_key' => 'api-key',
    'api_secret' => 'You-know-what-to-do',
    'overwrite' => true, // set this to true if you want to overwrite existing files using $filesystem->write();
]);

$adapter = new CloudinaryAdapter($client);
// This option disables assert that file is absent before calling `write`.
// It is necessary if you want to overwrite files on `write` as Cloudinary does it by default.
$filesystem = new Filesystem($adapter, ['disable_asserts' => true]);
```

# Cloudinary features

Please, keep in mind three possible pain-in-asses of Cloudinary:
 
* It adds automatically file extension to its public_id. In terms of Flysystem, cloudinary's public_id is considered as filename. But if you set public_id as 'test.jpg' Cloudinary will save the file as 'test.jpg.jpg'. In order to work it around, you can use [PathConverterInterface](doc/path_converter.md).
* It does not support folders creation through the API
* If you want to save your files using folder you should set public_ids like 'test/test.jpg' and allow automated folders creation in your account settings in Cloudinary dashboard.
