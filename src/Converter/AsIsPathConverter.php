<?php

namespace Enl\Flysystem\Cloudinary\Converter;

use Cloudinary\Api\Response;

/**
 * Class AsIsPathConverter
 * Default implementation of PathConverterInterface just does nothing.
 */
class AsIsPathConverter implements PathConverterInterface
{

    /**
     * Converts path to public Id
     *
     * @param string $path
     * @return string
     */
    public function pathToId($path)
    {
        return $path;
    }

    /**
     * Converts id to path
     *
     * @param Response $resource
     * @return string
     */
    public function idToPath($resource)
    {
        return $resource['public_id'];
    }
}
