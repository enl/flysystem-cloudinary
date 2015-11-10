<?php

namespace Enl\Flysystem\Cloudinary\Test;

use Enl\Flysystem\Cloudinary\ApiFacade;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Class ApiFacadeTest
 * Almost all the tests here a very simple just because
 * ApiFacade delegates everything to different parts of Cloudinary API library
 * @package Enl\Flysystem\Cloudinary\Test
 */
class ApiFacadeTest extends MockeryTestCase
{
    public static function setUpBeforeClass()
    {
        require_once __DIR__ . '/fixtures/functions.php';
    }

    public static $cloudinary_url_result;

    public static $fopen_result;

    public function testContent()
    {
        self::$fopen_result = $expected = 'asdf';

        $api = m::mock('Enl\Flysystem\Cloudinary\ApiFacade[url]', []);
        $api->shouldReceive('url')->with('path')->andReturn('something')->once();

        $this->assertEquals($expected, $api->content('path'));
    }

    public function testUrl()
    {
        self::$cloudinary_url_result = $expected = 'asdf';

        $api = new ApiFacade();

        $this->assertEquals($expected, $api->url('path'));
    }

    public function testConfigure()
    {
        $api = new ApiFacade();
        $api->configure(['upload_preset' => 'preset']);

        $actual = \Cloudinary::config_get('upload_preset');

        $this->assertEquals('preset', $actual);
    }

    public function testSetUploadPreset()
    {
        $api = new ApiFacade();
        $api->setUploadPreset('preset');

        $this->assertEquals('preset', \Cloudinary::config_get('upload_preset'));
    }
}
