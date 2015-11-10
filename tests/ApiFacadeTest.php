<?php

namespace Enl\Flysystem\Cloudinary {

    use Enl\Flysystem\Cloudinary\Test\FunctionFixtures;

    function cloudinary_url($path, array $parameters = [])
    {
        return FunctionFixtures::$cloudinary_url_result;
    }

    function fopen($path, $attributes)
    {
        return FunctionFixtures::$fopen_result;
    }
}


namespace Enl\Flysystem\Cloudinary\Test {

    use Enl\Flysystem\Cloudinary\ApiFacade;
    use Mockery as m;
    use Mockery\Adapter\Phpunit\MockeryTestCase;

    class FunctionFixtures
    {
        public static $cloudinary_url_result;

        public static $fopen_result;
    }

    /**
     * Class ApiFacadeTest
     * Almost all the tests here a very simple just because
     * ApiFacade delegates everything to different parts of Cloudinary API library
     * @package Enl\Flysystem\Cloudinary\Test
     */
    class ApiFacadeTest extends MockeryTestCase
    {
        public function testContent()
        {
            FunctionFixtures::$fopen_result = $expected = 'asdf';

            $api = m::mock('Enl\Flysystem\Cloudinary\ApiFacade[url]', []);
            $api->shouldReceive('url')->with('path')->andReturn('something')->once();

            $this->assertEquals($expected, $api->content('path'));
        }

        public function testUrl()
        {
            FunctionFixtures::$cloudinary_url_result = $expected = 'asdf';

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

}
