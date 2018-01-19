<?php

namespace Enl\Flysystem\Cloudinary\Converter;

use Cloudinary\Api\Response;

class TruncateExtensionConverter implements PathConverterInterface
{
    /**
     * Converts path to public Id
     *
     * @param string $path
     * @return string
     */
    public function pathToId($path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return $extension
            ? substr($path, 0, - (strlen($extension) + 1))
            : $path;
    }

    /**
     * Converts id to path
     *
     * @param Response $resource
     * @return string
     */
    public function idToPath($resource)
    {
        return $resource['public_id'] . '.' . $resource['format'];
    }
}
