<?php

namespace Enl\Flysystem\Cloudinary\Test\Converter;

use Enl\Flysystem\Cloudinary\Converter\AsIsPathConverter;
use Enl\Flysystem\Cloudinary\Converter\PathConverterInterface;
use PHPUnit\Framework\TestCase;

class AsIsPathConverterTest extends TestCase
{
    /** @var PathConverterInterface */
    private $converter;

    protected function setUp(): void
    {
        $this->converter = new AsIsPathConverter();
    }

    public function testPathToId()
    {
        $this->assertEquals('file.png', $this->converter->pathToId('file.png'));
    }

    public function testIdToPath()
    {
        $this->assertEquals('file.png', $this->converter->idToPath([
            'public_id' => 'file.png'
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
