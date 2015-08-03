<?php


namespace Enl\Flysystem\Cloudinary\Test;

use Enl\Flysystem\Cloudinary\CloudinaryAdapter;
use League\Flysystem\Config;
use Mockery as m;
use Mockery\MockInterface;

class CloudinaryAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }


    private function getApiMock()
    {
        return m::mock('Enl\Flysystem\Cloudinary\ApiFacade');
    }

    public function adapterProvider()
    {
        $mock = $this->getApiMock();

        return [
            [new CloudinaryAdapter($mock), $mock]
        ];
    }

    /**
     * @test
     * @dataProvider adapterProvider
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     */
    public function writeShouldReturnFalseOnFailure(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $api->shouldReceive('upload')->withAnyArgs()->andThrow('\Cloudinary\Error', 'Error!');
        $this->assertFalse($cloudinary->write('path', 'contents', new Config()));
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function writeShouldReturnNormalizedMetadataOnSuccess(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $path = 'test-path';
        $api->shouldReceive('upload')->withAnyArgs()->andReturn(['public_id' => $path, 'bytes' => 123123]);
        $response = $cloudinary->write($path, 'contents', new Config());

        $this->assertArrayHasKey('path', $response);
        $this->assertEquals($path, $response['path']);
        $this->assertArrayHasKey('size', $response);
        $this->assertEquals(123123, $response['size']);
    }

    /**
     * @test
     * @dataProvider adapterProvider
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     */
    public function updateShouldReturnFalseOnFailure(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $api->shouldReceive('upload')->withAnyArgs()->andThrow('\Cloudinary\Error', 'Error!');
        $this->assertFalse($cloudinary->update('path', 'contents', new Config()));
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function updateShouldReturnNormalizedMetadataOnSuccess(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $path = 'test-path';
        $api->shouldReceive('upload')->withAnyArgs()->andReturn(['public_id' => $path, 'bytes' => 123123]);
        $response = $cloudinary->update($path, 'contents', new Config());

        $this->assertArrayHasKey('path', $response);
        $this->assertEquals($path, $response['path']);
        $this->assertArrayHasKey('size', $response);
        $this->assertEquals(123123, $response['size']);
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function writeStreamShouldReturnFalseOnFailure(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $api->shouldReceive('upload')->withAnyArgs()->andThrow('\Cloudinary\Error', 'Error!');
        $this->assertFalse($cloudinary->writeStream('path', tmpfile(), new Config()));
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function writeStreamShouldReturnMetadataOnSuccess(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $path = 'test-path';
        $api->shouldReceive('upload')->withAnyArgs()->andReturn(['public_id' => $path]);
        $response = $cloudinary->writeStream($path, tmpfile(), new Config());

        $this->assertArrayHasKey('path', $response);
        $this->assertEquals($path, $response['path']);
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function renameShouldReturnFalseOnFailure(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $api->shouldReceive('rename')->withArgs(['old', 'new'])->once()->andThrow('\Cloudinary\Error', 'Not Found!');
        $this->assertFalse($cloudinary->rename('old', 'new'));
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function renameShouldReturnTrueOnSuccess(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $api->shouldReceive('rename')->withArgs(['old', 'new'])->once()->andReturn(['public_id' => 'new']);
        $this->assertTrue($cloudinary->rename('old', 'new'));
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function deleteShouldReturnFalseOnFailure(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $api->shouldReceive('delete_resources')
            ->with(['file'])
            ->once()
            ->andReturn(['deleted' => ['file' => 'not_found']]);
        $this->assertFalse($cloudinary->delete('file'));
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function deleteShouldReturnTrueOnSuccess(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $api->shouldReceive('delete_resources')
            ->with(['file'])
            ->once()->andReturn(['deleted' => ['file' => 'deleted']]);
        $this->assertTrue($cloudinary->delete('file'));
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function readShouldReturnFalseOnFailure(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $api->shouldReceive('content')->with('file')->once()->andThrow('\Cloudinary\Error', 'Not found');
        $this->assertFalse($cloudinary->read('file'));
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function readShouldReturnArrayOnSuccess(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $api->shouldReceive('content')->with('file')->once()->andReturn(tmpfile());
        $this->assertEquals(['path' => 'file', 'contents' => ''], $cloudinary->read('file'));
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function readStreamShouldReturnFalseOnFailure(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $api->shouldReceive('content')->with('file')->once()->andThrow('\Cloudinary\Error', 'Not found');
        $this->assertFalse($cloudinary->readStream('file'));
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function readStreamShouldReturnArrayOnSuccess(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $api->shouldReceive('content')->with('file')->once()->andReturn(tmpfile());

        $response = $cloudinary->readStream('file');
        $this->assertEquals('file', $response['path']);
        $this->assertInternalType('resource', $response['stream']);
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function listContentsShouldReturnEmptyArrayOnError(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $api->shouldReceive('resources')->withAnyArgs()->andThrow('\Cloudinary\Error', 'oops');
        $this->assertEquals([], $cloudinary->listContents());
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function listContentsShouldReturnAllContents(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $request = ['prefix' => '', 'max_results' => 500, 'type' => 'upload'];
        $expected = [
            ['public_id' => 'test-1'],
            ['public_id' => 'test-2'],
            ['public_id' => 'test-3']
        ];

        $api->shouldReceive('resources')
            ->once()
            ->with($request)
            ->andReturn(['resources' => array_slice($expected, 0, 2, false), 'next_cursor' => 'cursor']);

        $api->shouldReceive('resources')
            ->once()
            ->with(array_merge($request, ['next_cursor' => 'cursor']))
            ->andReturn(['resources' => array_slice($expected, 2)]);

        $actual = $cloudinary->listContents();
        $this->assertEquals(count($expected), count($actual));
    }

    /**
     * @param CloudinaryAdapter $cloudinary
     * @param MockInterface     $api
     * @test
     * @dataProvider adapterProvider
     */
    public function listContentsShouldReturnNormalizedMetadata(CloudinaryAdapter $cloudinary, MockInterface $api)
    {
        $public_id = 'test';
        $bytes = 123123;
        $created_at = date('Y-m-d H:i:s');

        $api->shouldReceive('resources')->andReturn(['resources' => [compact('public_id', 'bytes', 'created_at')]]);

        $expected = ['type'      => 'file',
                     'path'      => $public_id,
                     'size'      => $bytes,
                     'timestamp' => strtotime($created_at)
        ];

        $actual = $cloudinary->listContents()[0];

        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $actual);
            $this->assertEquals($value, $actual[$key]);
        }
    }


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
     * @test
     */
    public function testMetadataCallsSuccess($method)
    {
        list ($cloudinary, $api) = $this->adapterProvider()[0];

        $public_id = 'file';
        $bytes = 123123;
        $created_at = date('Y-m-d H:i:s');

        /** @var MockInterface $api */
        $api->shouldReceive('resource')->once()->andReturn(compact('public_id', 'bytes', 'created_at'));

        $actual = $cloudinary->{$method}('one', 'two');


        $expected = ['type'      => 'file',
                     'path'      => $public_id,
                     'size'      => $bytes,
                     'timestamp' => strtotime($created_at)
        ];

        $this->assertInternalType('array', $actual);

        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $actual);
            $this->assertEquals($value, $actual[$key]);
        }
    }

    /**
     * @param $method
     * @test
     * @dataProvider metadataProvider
     */
    public function testMetadataCallsFailure($method)
    {
        list($cloudinary, $api) = $this->adapterProvider()[0];

        /** @var MockInterface $api */
        $api->shouldReceive('resource')->once()->andThrow('Cloudinary\Api\Error');
        $this->assertFalse($cloudinary->{$method}('path'));
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function getVisibilityShouldNotBeSupported()
    {
        list($adapter) = $this->adapterProvider()[0];
        /** @var CloudinaryAdapter $adapter */
        $adapter->setVisibility('path', 'visibility');
    }

    /**
     * @test
     * @expectedException \LogicException
     */
    public function setVisibilityShouldNotBeSupported()
    {
        list($adapter) = $this->adapterProvider()[0];
        /** @var CloudinaryAdapter $adapter */
        $adapter->getVisibility('path');
    }

    /**
     * @test
     * @expectedException \League\Flysystem\NotSupportedException
     */
    public function createDirShouldNotBeSupported()
    {
        list($adapter) = $this->adapterProvider()[0];
        /** @var CloudinaryAdapter $adapter */
        $adapter->createDir('path', new Config());
    }

    /**
     * @test
     * @expectedException \League\Flysystem\NotSupportedException
     */
    public function deleteDirShouldNotBeSupported()
    {
        list($adapter) = $this->adapterProvider()[0];
        /** @var CloudinaryAdapter $adapter */
        $adapter->deleteDir('path');
    }
}
