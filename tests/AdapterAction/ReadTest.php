<?php

namespace Enl\Flysystem\Cloudinary\Test\AdapterAction;

use Cloudinary\Error;

class ReadTest extends ActionTestCase
{
    public function testReturnsFalseOnFailure()
    {
        list($cloudinary, $api) = $this->buildAdapter();

        $api->content('file')->shouldBeCalled()->willThrow(Error::class);

        $this->assertFalse($cloudinary->read('file'));
        $this->assertFalse($cloudinary->readStream('file'));
    }

    public function testReturnsArrayOnSuccess()
    {
        list($cloudinary, $api) = $this->buildAdapter();

        $api->content('file')->willReturn(fopen('php://memory', 'r+'));
        $this->assertEquals(['path' => 'file', 'contents' => ''], $cloudinary->read('file'));
    }

    public function testReadStreamReturnsArrayOnSuccess()
    {
        list($cloudinary, $api) = $this->buildAdapter();

        $api->content('file')->willReturn(fopen('php://memory', 'r+'));
        $response = $cloudinary->readStream('file');

        $this->assertIsArray($response);
        $this->assertEquals('file', $response['path']);
        $this->assertIsResource($response['stream']);
    }
}
