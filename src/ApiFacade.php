<?php

namespace Enl\Flysystem\Cloudinary;

use Cloudinary\Api as BaseApi;
use Cloudinary\Uploader;

/**
 * Class ApiFacade.
 */
class ApiFacade extends BaseApi
{
    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (count($options)) {
            $this->configure($options);
        }
    }

    /**
     * @param array $options
     *                       The most important options are:
     *                       * string $cloud_name Your cloud name
     *                       * string $api_key Your api key
     *                       * string $api_secret You api secret
     *                       * boolean $overwrite Weather to overwrite existing file by rename or copy?
     */
    public function configure(array $options = [])
    {
        \Cloudinary::config($options);
    }

    /**
     * @param string $preset
     */
    public function setUploadPreset($preset)
    {
        $this->configure(['upload_preset' => $preset]);
    }

    /**
     * @param string $path
     * @param string $contents
     *
     * @return array
     */
    public function upload($path, $contents)
    {
        return Uploader::upload(new DataUri($contents), ['public_id' => $path]);
    }

    /**
     * @param string $path
     * @param string $newpath
     *
     * @return array
     */
    public function rename($path, $newpath)
    {
        return Uploader::rename($path, $newpath);
    }

    /**
     * Returns content of file with given path.
     *
     * @param string $path
     *
     * @return resource
     */
    public function content($path, $transformations = [])
    {
        return fopen($this->url($path, $transformations), 'r');
    }

    /**
     * Returns URL of file with given $path and $transformations.
     *
     * @param string $path
     * @param array  $transformations
     *
     * @return string
     */
    public function url($path, array $transformations = [])
    {
        return cloudinary_url($path, $transformations);
    }
}
