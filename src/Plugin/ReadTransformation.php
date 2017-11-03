<?php

namespace Enl\Flysystem\Cloudinary\Plugin;

class ReadTransformation extends AbstractPlugin
{
    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'readTransformation';
    }

    public function handle($path, $transformations = [])
    {
        if (empty($transformations)) {
            // If transformations are empty, just pass to original method
            return $this->filesystem->read($path);
        }

        return $this->apiFacade->content($path, $transformations);
    }
}
