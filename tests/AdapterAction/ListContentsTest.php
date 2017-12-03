<?php

namespace Enl\Flysystem\Cloudinary\Test\AdapterAction;

use Prophecy\Argument;

class ListContentsTest extends ActionTestCase
{
    public function testReturnsEmptyArrayOnError()
    {
        list($cloudinary, $api) = $this->buildAdapter();
        $api->resources(Argument::any())->shouldBeCalled()->willThrow('Cloudinary\Error');
        $this->assertEquals([], $cloudinary->listContents());
    }

    public function testReturnsEmptyArrayOnNonExistentDir()
    {
        list($cloudinary, $api) = $this->buildAdapter();
        $api->resources(Argument::any())->shouldBeCalled()->willReturn(['resources' => []]);
        $this->assertEquals([], $cloudinary->listContents('test'));
    }

    public function testCallsNextCursor()
    {
        list($cloudinary, $api) = $this->buildAdapter();
        $request = ['prefix' => '', 'max_results' => 500, 'type' => 'upload'];
        $expected = [
            ['public_id' => 'test-1'],
            ['public_id' => 'test-2'],
            ['public_id' => 'test-3']
        ];

        $api->resources($request)->shouldBeCalled()->willReturn([
            'resources' => array_slice($expected, 0, 2),
            'next_cursor' => 'cursor'
        ]);

        $api->resources(array_merge($request, ['next_cursor' => 'cursor']))
            ->shouldBeCalled()
            ->willReturn([
                'resources' => array_slice($expected, 2)
            ]);

        $actual = $cloudinary->listContents();
        $this->assertEquals(
            array_column($expected, 'public_id'),
            array_column($actual, 'path')
        );
    }

    public function testReturnsDirectories()
    {
        list ($cloudinary, $api) = $this->buildAdapter();
        $request = ['prefix' => '', 'max_results' => 500, 'type' => 'upload'];
        $response = [
            ['public_id' => 'test-1'],
            ['public_id' => 'dir1/test-2'],
            ['public_id' => 'dir1/test-2'],
            ['public_id' => 'dir2/test-3']
        ];

        $api->resources($request)->willReturn(['resources' => $response]);
        $actual = $cloudinary->listContents('');
        $dirs = array_column($actual, 'path');

        $this->assertEquals(
            ['test-1', 'dir1/test-2', 'dir1/test-2', 'dir2/test-3', 'dir1', 'dir2'],
            $dirs
        );
    }

    public function testReturnsNormalizedMetadata()
    {
        list ($cloudinary, $api) = $this->buildAdapter();
        $public_id = 'test';
        $bytes = 123123;
        $created_at = date('Y-m-d H:i:s');

        $api->resources(Argument::any())->willReturn([
            'resources' => [compact('public_id', 'bytes', 'created_at')]
        ]);

        $expected = [
            'type' => 'file',
            'path' => $public_id,
            'size' => $bytes,
            'timestamp' => strtotime($created_at)
        ];

        $actual = $cloudinary->listContents()[0];

        $this->assertEquals($expected, $actual);
    }
}
