<?php

namespace Enl\Flysystem\Cloudinary\Test\AdapterAction;

use Enl\Flysystem\Cloudinary\CloudinaryAdapter;

abstract class ActionTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return [CloudinaryAdapter, ApiFacade]
     */
    final protected function buildAdapter()
    {
        $api = $this->prophesize('\Enl\Flysystem\Cloudinary\ApiFacade');

        return [new CloudinaryAdapter($api->reveal()), $api];
    }
}
