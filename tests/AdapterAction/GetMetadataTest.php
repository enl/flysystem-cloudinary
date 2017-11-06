<?php

namespace Enl\Flysystem\Cloudinary\Test\AdapterAction;

class GetMetadataTest extends ActionTestCase
{
    public function metadataProvider()
    {
        return [
            ['getMetadata'],
            ['getMimetype'],
            ['getTimestamp'],
            ['getSize'],
            ['has'],
        ];
    }

    /**
     * @dataProvider  metadataProvider
     * @param $method
     */
    public function testMetadataCallsSuccess($method)
    {
        $public_id = 'file';
        $bytes = 123123;
        $created_at = date('Y-m-d H:i:s');

        list ($cloudinary, $api) = $this->buildAdapter();

        $api->resource('file')->willReturn(compact('public_id', 'bytes', 'created_at'));

        $expected = [
            'type' => 'file',
            'path' => $public_id,
            'size' => $bytes,
            'timestamp' => strtotime($created_at)
        ];
        $actual = $cloudinary->$method($public_id);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @param $method
     * @dataProvider metadataProvider
     */
    public function testMetadataCallsFailure($method)
    {
        list($cloudinary, $api) = $this->buildAdapter();
        $api->resource()->willThrow('Cloudinary\Api\Error');

        $this->assertFalse($cloudinary->{$method}('path'));
    }
}
