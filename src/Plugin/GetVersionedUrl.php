<?php


namespace Enl\Flysystem\Cloudinary\Plugin;


class GetVersionedUrl extends AbstractPlugin
{
    const VERSION_OPTION = 'version';

    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'getVersionedUrl';
    }

    /**
     * Returns url with version.
     * If no version was passed to $options, than the latest version will be used.
     *
     * @param string $path
     * @param array $options
     *
     * @return string
     */
    public function handle($path, $options = [])
    {
        if (!array_key_exists(self::VERSION_OPTION, $options)) {
            $options[self::VERSION_OPTION] = $this->getLatestVersion($path);
        }

        return $this->apiFacade->url($path, $options);
    }

    /**
     * @param string $path
     *
     * @return int|mixed
     */
    private function getLatestVersion($path)
    {
        $resource = $this->apiFacade->resource($path);

        return array_key_exists(self::VERSION_OPTION, $resource)
            ? $resource[self::VERSION_OPTION]
            : 1;
    }
}
