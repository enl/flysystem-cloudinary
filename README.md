Flysystem-cloudinary
====================

Cloudinary adapter for [Flysystem](https://github.com/thephpleague/flysystem). 

Installation
------------

```
composer require enl/flysystem-cloudinary dev-master
```

Still don't use Composer? Great moment to start;)

Usage
-----

All you need is instantiate Flysystem and set Adapter to it:

```
    $api = new Enl\Flysystem\Cloudinary\ApiFacade([
        'cloud_name' => 'your-cloudname-here',
         'api_key' => 'api-key',
         'api_secret' => 'You-know-what-to-do',
         'overwrite' => false, // set this to true if you want to overwrite existing files using $filesystem->write();
    ]);
    $adapter = new Enl\Flysystem\Cloudinary\CloudinaryAdapter($api);
    $filesystem = new League\Flysystem\Filesystem($adapter);
```

After that you can do everything Cloudinary supports using unified interface with power of [Flysystem](https://github.com/thephpleague/flysystem).

Cloudinary features
-------------------

Please, keep in mind three possible pain-in-asses of Cloudinary:
 
* It adds automatically file extension to its public_id. In terms of Flysystem, cloudinary's public_id is considered as filename. But if you set public_id as 'test.jpg' Cloudinary will save the file as 'test.jpg.jpg'
* It does not support folders creation through the API
* If you want to save your files using folder you should set public_ids like 'test/test.jpg' and allow automated folders creation in your account settings in Cloudinary dashboard.

Disclaimer
----------

Actually, this library is very UNSTABLE. I haven't yet integrated it in project I've developed it for.

By the way, there is a bunch of tests which gives me hope:)

