Path Converter
==============

As you might know, Cloudinary does not include file's extension in its `public_id`. In almost all cases it won't impact you, but sometimes it's important to preserve names as accurate as possible. That's why this adapter has `PathConverterInterface`.

This interface is as simple as this:

```php
namespace Enl\Flysystem\Cloudinary\Converter;

interface PathConverterInterface
{
    /**
     * Converts path to public Id
     *
     * @param string $path
     * @return string
     */
    public function pathToId($path);

    /**
     * Converts id to path
     *
     * @param Response $id
     * @return string
     */
    public function idToPath($id);
}
```

There are only two functions, one of them converts given path to `public_id`, another converts given resource to `path`. All implementations SHOULD be non-destructive so that `$converter->pathToId($converter->idToPath(['public_id' => '111'])` will return `111`.

By default, Api facade uses `AsIsPathConverter` which performs no conversions. In order to replace it with your custom implementation you should set second parameter in `ApiFacade` constructor:


```php
use Enl\Flysystem\Cloudinary\ApiFacade;
use Enl\Flysystem\Cloudinary\Converter\TruncateExtensionConverter;

$options = [
   'cloud_name' => 'your-cloudname-here',
   'api_key' => 'api-key',
   'api_secret' => 'You-know-what-to-do',
   'overwrite' => true, // set this to true if you want to overwrite existing files using $filesystem->write();
];

$client = new ApiFacade($options, new TruncateExtensionConverter());
```

TruncateExtensionConverter
--------------------------

It's clear from its name, it truncates extension from given path to create `public_id`. BTW, you *must* know that it relies on Cloudinary resource `format` field when recovering extension in `idToPath` operation.
