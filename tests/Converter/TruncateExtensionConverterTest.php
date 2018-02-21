<?php

namespace Enl\Flysystem\Cloudinary\Test\Converter;

use Enl\Flysystem\Cloudinary\Converter\PathConverterInterface;
use Enl\Flysystem\Cloudinary\Converter\TruncateExtensionConverter;

class TruncateExtensionConverterTest extends \PHPUnit_Framework_TestCase
{
    /** @var PathConverterInterface */
    private $converter;

    protected function setUp()
    {
        $this->converter = new TruncateExtensionConverter();
    }

    public function testPathToId()
    {
        $this->assertEquals('file', $this->converter->pathToId('file.png'));
    }

    public function testIdToPath()
    {
        $this->assertEquals('file.png', $this->converter->idToPath([
            'public_id' => 'file',
            'format' => 'png'
        ]));
    }

    public function testNonDestructive()
    {
        $resource = [
            'public_id' => 'file',
            'format' => 'png'
        ];

        $path = $this->converter->idToPath($resource);
        $id = $this->converter->pathToId($path);

        $this->assertEquals('file', $id);
    }
}
