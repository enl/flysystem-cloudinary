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
     * @var array
     */
    private $deleteOptions = [];

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
     * Sets the options for resource deleting operation.
     * @param array $options
     */
    public function setDeleteOptions(array $options)
    {
        $this->deleteOptions = $options;
    }

    /**
     * @param $path
     * @param array $options
     *
     * @return BaseApi\Response
     */
    public function resource($path, $options = [])
    {
        $resource = parent::resource($this->converter->pathToId($path));

        return $this->addPathToResource($resource);
    }

    public function resources($options = [])
    {
        $response = parent::resources($options);
        $response['resources'] = array_map([$this, 'addPathToResource'], $response['resources']);

        return $response;
    }

    /**
     * @param array $paths
     * @param array $options
     *
     * @return BaseApi\Response
     */
    public function deleteResources(array $paths, array $options = [])
    {
        $map = [];

        foreach ($paths as $path) {
            $map[$this->converter->pathToId($path)] = $path;
        }

        $response = parent::delete_resources(array_keys($map), array_merge($this->deleteOptions, $options));

        $deleted = [];

        foreach ($response['deleted'] as $id => $status) {
            $deleted[$map[$id]] = $status;
        }
        $response['deleted'] = $deleted;

        return $response;
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
     * @param bool $overwrite
     * @return array
     */
    public function upload($path, $contents, $overwrite = false)
    {
        $options = [
            'public_id' => $this->converter->pathToId($path),
            'overwrite' => $overwrite
        ];

        return $this->addPathToResource(Uploader::upload(new DataUri($contents), $options));
    }

    /**
     * @param string $path
     * @param string $newPath
     *
     * @return array
     */
    public function rename($path, $newPath)
    {
        $resource = Uploader::rename(
            $this->converter->pathToId($path),
            $this->converter->pathToId($newPath)
        );

        return $this->addPathToResource($resource);
    }

    /**
     * Returns content of file with given public id.
     *
     * @param string $path
     * @param array $options
     *
     * @return resource
     */
    public function content($path, array $options = [])
    {
        return fopen($this->url($path, $options), 'r');
    }

    /**
     * Returns URL of file with given public id and transformations.
     *
     * @param string $path
     * @param array  $options
     *
     * @return string
     */
    public function url($path, array $options = [])
    {
        return cloudinary_url($this->converter->pathToId($path), $options);
    }

    /**
     * @param $resource
     *
     * @return mixed
     */
    private function addPathToResource($resource)
    {
        $resource['path'] = $this->converter->idToPath($resource);

        return $resource;
    }
}
