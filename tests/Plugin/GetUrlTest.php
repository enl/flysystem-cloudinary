<?php

namespace Enl\Flysystem\Cloudinary\Test\Plugin;

use Enl\Flysystem\Cloudinary\ApiFacade;
use Enl\Flysystem\Cloudinary\CloudinaryAdapter;
use Enl\Flysystem\Cloudinary\Plugin\GetUrl;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class GetUrlTest extends TestCase
{
    use ProphecyTrait;

    public function testPassesTransformationToUrl()
    {
        list ($filesystem, $facade) = $this->mockFacade();
        $transformations = ['width' => 600, 'height' => 600];
        $facade->url('test.jpg', $transformations)->willReturn('http://cloudinary.url/test');

        $content = $filesystem->getUrl('test.jpg', $transformations);
        $this->assertEquals('http://cloudinary.url/test', $content);
    }

    private function mockFacade()
    {
        $api = $this->prophesize(ApiFacade::class);

        $filesystem = new Filesystem(new CloudinaryAdapter($api->reveal()), ['disable_asserts' => true]);
        $filesystem->addPlugin(new GetUrl($api->reveal()));

        return [$filesystem, $api];
    }
}
