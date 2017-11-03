<?php

namespace Enl\Flysystem\Cloudinary\Plugin;

use Enl\Flysystem\Cloudinary\ApiFacade;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;

abstract class AbstractPlugin implements PluginInterface
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    /**
     * @var ApiFacade
     */
    protected $apiFacade;

    public function __construct(ApiFacade $facade)
    {
        $this->apiFacade = $facade;
    }

    /**
     * Set the Filesystem object.
     *
     * @param FilesystemInterface $filesystem
     */
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }
}
