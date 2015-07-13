<?php


namespace Enl\Flysystem\Cloudinary;

use Cloudinary\Api as BaseApi;
use Cloudinary\Uploader;

/**
 * Class ApiFacade
 *
 * @package Enl\Flysystem\Cloudinary
 * @codeCoverageIgnore Nothing to test here. This is simplest possible wrapper
 */
class ApiFacade extends BaseApi
{
    /**
     * @param array $options
     * The most important options are:
     * * string $cloud_name Your cloud name
     * * string $api_key Your api key
     * * string $api_secret You api secret
     * * boolean $overwrite Weather to overwrite existing file by rename or copy?
     */
    public function __construct(array $options)
    {
        \Cloudinary::config($options);
    }

    public function upload($path, $contents)
    {
        return Uploader::upload(new DataUri($contents), ['public_id' => $path]);
    }

    public function rename($path, $newpath)
    {
        return Uploader::rename($path, $newpath);
    }

    public function content($path)
    {
        return fopen($this->url($path), 'r');
    }

    public function url($path, array $parameters = [])
    {
        return cloudinary_url($path, $parameters);
    }
}
