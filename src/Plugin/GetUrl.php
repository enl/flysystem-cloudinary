<?php

namespace Enl\Flysystem\Cloudinary\Plugin;

class GetUrl extends AbstractPlugin
{
    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'getUrl';
    }

    public function handle($path, $transformations = [])
    {
        return $this->apiFacade->url($path, $transformations);
    }
}
