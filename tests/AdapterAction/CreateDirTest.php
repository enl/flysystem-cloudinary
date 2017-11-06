<?php

namespace Enl\Flysystem\Cloudinary\Test\AdapterAction;

use League\Flysystem\Config;

class CreateDirTest extends ActionTestCase
{
    public function createDirProvider()
    {
        return [
            ['path', ['path' => 'path/', 'type' => 'dir']],
            ['path/', ['path' => 'path/', 'type' => 'dir']],
        ];
    }

    /**
     * @dataProvider createDirProvider
     * @param $path
     * @param $expected
     */
    public function testCreateDir($path, $expected)
    {
        list($cloudinary,) = $this->buildAdapter();

        $this->assertEquals($expected, $cloudinary->createDir($path, new Config()));
    }
}
