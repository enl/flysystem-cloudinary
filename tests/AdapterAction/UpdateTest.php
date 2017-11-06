<?php

namespace Enl\Flysystem\Cloudinary\Test\AdapterAction;

use League\Flysystem\Config;
use Prophecy\Argument;

class UpdateTest extends ActionTestCase
{
    public function testReturnsFalseOnFailure()
    {
        list($cloudinary, $api) = $this->buildAdapter();
        $api->upload(Argument::any())->willThrow('Cloudinary\Error');
        $this->assertFalse($cloudinary->update('path', 'contents', new Config()));
    }

    public function testReturnsNormalizedMetadataOnSuccess()
    {
        list($cloudinary, $api) = $this->buildAdapter();
        $api->upload('test-path', 'contents', true)->willReturn(['public_id' => 'test-path', 'bytes' => 123123]);

        $response = $cloudinary->update('test-path', 'contents', new Config());

        $this->assertArrayHasKey('path', $response);
        $this->assertEquals('test-path', $response['path']);
        $this->assertArrayHasKey('size', $response);
        $this->assertEquals(123123, $response['size']);
    }
}
