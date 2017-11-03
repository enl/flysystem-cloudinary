<?php

namespace Enl\Flysystem\Cloudinary\Test\Plugin;

use Enl\Flysystem\Cloudinary\ApiFacade;
use Enl\Flysystem\Cloudinary\CloudinaryAdapter;
use Enl\Flysystem\Cloudinary\Plugin\GetUrl;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class GetUrlTest extends TestCase
{
    public function testPassesTransformationToUrl()
    {
        list ($filesystem, $facade) = $this->mockFacade();
        $transformations = ['width' => 600, 'height' => 600];
        $facade->shouldReceive('url')
            ->once()
            ->with('test', $transformations)
            ->andReturn('http://cloudinary.url/test');

        $content = $filesystem->getUrl('test.jpg', $transformations);
        $this->assertEquals('http://cloudinary.url/test', $content);
    }

    private function mockFacade()
    {
        $facade = m::mock(ApiFacade::class);
        $filesystem = new Filesystem(new CloudinaryAdapter($facade), ['disable_asserts' => true]);
        $filesystem->addPlugin(new GetUrl($facade));

        return [$filesystem, $facade];
    }
}
