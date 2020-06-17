<?php

namespace Enl\Flysystem\Cloudinary\Test\Plugin;

use Enl\Flysystem\Cloudinary\ApiFacade;
use Enl\Flysystem\Cloudinary\CloudinaryAdapter;
use Enl\Flysystem\Cloudinary\Plugin\ReadTransformation;
use League\Flysystem\Filesystem;

class ReadTransformationTest extends \PHPUnit_Framework_TestCase
{
    public function testCallsReadIfNoTransformations()
    {
        list ($filesystem, $facade) = $this->mockFacade();
        $stream = 'data://text/plain,test content';
        $facade->content('test.jpg')
               ->willReturn(fopen($stream, 'r'));

        $content = $filesystem->readTransformation('test.jpg');
        $this->assertEquals('test content', $content);
    }

    public function testPassesTransformationToConvert()
    {
        list ($filesystem, $facade) = $this->mockFacade();
        $transformations = ['width' => 600, 'height' => 600];
        $stream = 'data://text/plain,transformed content';
        $facade->content('test.jpg', $transformations)
               ->willReturn(fopen($stream, 'r'));

        $content = $filesystem->readTransformation('test.jpg', $transformations);
        $this->assertEquals('transformed content', stream_get_contents($content));
    }

    private function mockFacade()
    {
        $api = $this->prophesize(ApiFacade::class);

        $filesystem = new Filesystem(new CloudinaryAdapter($api->reveal()), ['disable_asserts' => true]);
        $filesystem->addPlugin(new ReadTransformation($api->reveal()));

        return [$filesystem, $api];
    }
}
