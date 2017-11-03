<?php

namespace Enl\Flysystem\Cloudinary\Test\Plugin;

use Enl\Flysystem\Cloudinary\ApiFacade;
use Enl\Flysystem\Cloudinary\CloudinaryAdapter;
use Enl\Flysystem\Cloudinary\Plugin\ReadTransformation;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class ReadTransformationTest extends TestCase
{
    public function testCallsReadIfNoTransformations()
    {
        list ($filesystem, $facade) = $this->mockFacade();
        $facade->shouldReceive('content')
            ->once()
            ->with('test')
            ->andReturn(fopen('data://text/plain,test content','r'));

        $content = $filesystem->readTransformation('test.jpg');
        $this->assertEquals('test content', $content);
    }

    public function testPassesTransformationToConvert()
    {
        list ($filesystem, $facade) = $this->mockFacade();
        $transformations = ['width' => 600, 'height' => 600];
        $facade->shouldReceive('content')
            ->once()
            ->with('test', $transformations)
            ->andReturn(fopen('data://text/plain,transformed content','r'));

        $content = $filesystem->readTransformation('test.jpg', $transformations);
        $this->assertEquals('transformed content', $content);
    }

    private function mockFacade()
    {
        $facade = m::mock(ApiFacade::class);
        $filesystem = new Filesystem(new CloudinaryAdapter($facade), ['disable_asserts' => true]);
        $filesystem->addPlugin(new ReadTransformation($facade));

        return [$filesystem, $facade];
    }
}
