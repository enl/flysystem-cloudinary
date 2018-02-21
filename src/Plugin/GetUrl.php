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

    public function handle($path, $options = [])
    {
        return $this->apiFacade->url($path, $options);
    }
}
