<?php

namespace Enl\Flysystem\Cloudinary;

use Cloudinary\Api;
use League\Flysystem\Adapter\Polyfill\NotSupportingVisibilityTrait;
use League\Flysystem\Adapter\Polyfill\StreamedCopyTrait;
use League\Flysystem\Adapter\Polyfill\StreamedTrait;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
use League\Flysystem\NotSupportedException;

class CloudinaryAdapter implements AdapterInterface
{
    /** @var ApiFacade */
    private $api;

    use NotSupportingVisibilityTrait; // We have no visibility for paths, due all of them are public
    use StreamedTrait; // We have no streaming in Cloudinary API, so we need this polyfill
    use StreamedCopyTrait;

    public function __construct(ApiFacade $api)
    {
        $this->api = $api;
    }

    /**
     * Write a new file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config Config object
     * @return array|false false on failure file meta data on success
     */
    public function write($path, $contents, Config $config)
    {
        try {
            return $this->normalizeMetadata($this->api->upload($path, $contents));
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update a file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config Config object
     * @return array|false false on failure file meta data on success
     */
    public function update($path, $contents, Config $config)
    {
        // Cloudinary does not distinguish create and update
        return $this->write($path, $contents, $config);
    }

    /**
     * Rename a file.
     *
     * @param string $path
     * @param string $newpath
     * @return bool
     */
    public function rename($path, $newpath)
    {
        try {
            return (bool) $this->api->rename($path, $newpath);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Delete a file.
     *
     * @param string $path
     * @return bool
     */
    public function delete($path)
    {
        $response = $this->api->delete_resources([$path]);

        return $response['deleted'][$path] === 'deleted';
    }

    /**
     * Delete a directory.
     *
     * @param string $dirname
     * @return bool
     * @throws NotSupportedException
     */
    public function deleteDir($dirname)
    {
        throw new NotSupportedException('Cloudinary API does not support folders management.');
    }

    /**
     * Create a directory.
     *
     * @param string $dirname directory name
     * @param Config $config
     * @return array|false
     * @throws NotSupportedException
     */
    public function createDir($dirname, Config $config)
    {
        throw new NotSupportedException(
            'Cloudinary API does not support direct folders creation. Create a file in this folder instead.'
        );
    }

    /**
     * Check whether a file exists.
     *
     * @param string $path
     * @return array|bool|null
     */
    public function has($path)
    {
        return $this->getMetadata($path);
    }

    /**
     * Read a file.
     *
     * @param string $path
     * @return array|false
     */
    public function read($path)
    {
        if ($response = $this->readStream($path)) {
            return ['contents' => stream_get_contents($response['stream']), 'path' => $response['path']];
        }

        return false;
    }

    /**
     * @param $path
     * @return array|bool
     */
    public function readStream($path)
    {
        try {
            return [
                'stream' => $this->api->content($path),
                'path' => $path
            ];
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * List contents of a directory.
     *
     * Unfortunately, Cloudinary does not support non recursive directory scan
     * because they treat filename prefixes as folders.
     *
     * @param string $directory
     * @param bool   $recursive
     * @return array
     */
    public function listContents($directory = '', $recursive = false)
    {
        try {
            return $this->doListContents($directory);
        } catch (\Exception $e) {
            return [];
        }

    }

    private function doListContents($directory = '', array $storage = [])
    {
        $options = ['prefix' => $directory, 'max_results' => 500, 'type' => 'upload'];
        if (array_key_exists('next_cursor', $storage)) {
            $options['next_cursor'] = $storage['next_cursor'];
        }

        $response = $this->api->resources($options);

        foreach ($response['resources'] as $resource) {
            ;
            $storage['files'][] = $this->normalizeMetadata($resource);
        }
        if (array_key_exists('next_cursor', $response)) {
            $storage['next_cursor'] = $response['next_cursor'];

            return $this->doListContents($directory, $storage);
        }

        return $storage['files'];
    }

    /**
     * Get all the meta data of a file or directory.
     *
     * @param string $path
     * @return array|false
     */
    public function getMetadata($path)
    {
        try {
            return $this->normalizeMetadata($this->api->resource($path));
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get all the meta data of a file or directory.
     *
     * @param string $path
     * @return array|false
     */
    public function getSize($path)
    {
        return $this->getMetadata($path);
    }

    /**
     * Get the mimetype of a file.
     *
     * @param string $path
     * @return array|false
     */
    public function getMimetype($path)
    {
        return $this->getMetadata($path);
    }

    /**
     * Get the timestamp of a file.
     *
     * @param string $path
     * @return array|false
     */
    public function getTimestamp($path)
    {
        return $this->getMetadata($path);
    }

    private function normalizeMetadata($resource)
    {
        return !is_array($resource) ? false : [
            'type'      => 'file',
            'path'      => $resource['public_id'],
            'size'      => array_key_exists('bytes', $resource) ? $resource['bytes'] : false,
            'timestamp' => array_key_exists('created_at', $resource) ? strtotime($resource['created_at']) : false
        ];
    }
}
