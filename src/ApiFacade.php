<?php

namespace Enl\Flysystem\Cloudinary;

use Cloudinary\Api as BaseApi;
use Cloudinary\Uploader;
use Enl\Flysystem\Cloudinary\Converter\AsIsPathConverter;
use Enl\Flysystem\Cloudinary\Converter\PathConverterInterface;

/**
 * Class ApiFacade.
 */
class ApiFacade extends BaseApi
{
    /**
     * @var PathConverterInterface
     */
    private $converter;

    /**
     * @param array $options
     * @param PathConverterInterface|null $converter
     */
    public function __construct(array $options = [], PathConverterInterface $converter = null)
    {
        if (count($options)) {
            $this->configure($options);
        }
        $this->converter = $converter ?: new AsIsPathConverter();
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
     * @param string $publicId
     * @param string $contents
     * @param bool $overwrite
     * @return array
     */
    public function upload($publicId, $contents, $overwrite = false)
    {
        $options = [
            'public_id' => $publicId,
            'overwrite' => $overwrite
        ];

        return Uploader::upload(new DataUri($contents), $options);
    }

    /**
     * @param string $publicId
     * @param string $newPublicId
     *
     * @return array
     */
    public function rename($publicId, $newPublicId)
    {
        return Uploader::rename($publicId, $newPublicId);
    }

    /**
     * Returns content of file with given public id.
     *
     * @param string $publicId
     *
     * @return resource
     */
    public function content($publicId)
    {
        return fopen($this->url($publicId), 'r');
    }

    /**
     * Returns URL of file with given public id and transformations.
     *
     * @param string $publicId
     * @param array  $transformations
     *
     * @return string
     */
    public function url($publicId, array $transformations = [])
    {
        return cloudinary_url($publicId, $transformations);
    }
}
