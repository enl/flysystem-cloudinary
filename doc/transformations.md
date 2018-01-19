Cloudinary Transformations Support
==================================

In order to fully utilize Cloudinary features, you definitely will want to use their [Image Transformations API](https://cloudinary.com/documentation/image_transformations).

That is possible, but requires a bit hackish approach to work with Flysystem.

Flysystem Plugins
-----------------

As you might know Flysystem supports plugins system: all you need is implement `League\Flysystem\PluginInterface`, register new plugin and then call it as normal method:

```php
$flysystem->addPlugin(new Acme\WhateverPlugin());
$flysystem->whatever();
```

Disclaimer
----------

When you use plugins from this package, you MUST consider that this vendor-locks you to Cloudinary and Flysystem becomes  _leaky abstraction_ layer. That means you won't change adapter that easy. You will need to implement these plugins (or get rid of their use) for you new underlying service, which will be tricky if not impossible.

Plugins
-------

There are two plugins: one of them returns `url` of transformed image, another one returns content of this transformed image:

``` php
<?php
use Enl\Flysystem\Cloudinary\ApiFacade as CloudinaryClient;
use Enl\Flysystem\Cloudinary\CloudinaryAdapter;
use Enl\Flysystem\Cloudinary\Plugin\ReadTransformation;
use Enl\Flysystem\Cloudinary\Plugin\GetUrl;
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
$filesystem->addPlugin(new ReadTransformation($client));
$filesystem->addPlugin(new GetUrl($client));
```

And then all you need is just call:

```php
$url = $filesystem->getUrl('test.jpg', ['width' => 600, 'height' => 400, 'format' => 'png']);
$content = $filesystem->readTransformation('test.jpg', ['width' => 600, 'height' => 400, 'format' => 'png']);
```
