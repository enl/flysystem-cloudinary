<?php

namespace Enl\Flysystem\Cloudinary;

use Enl\Flysystem\Cloudinary\Test\ApiFacadeTest;

function cloudinary_url($path, array $parameters = [])
{
    return ApiFacadeTest::$cloudinary_url_result;
}

function fopen($path, $attributes)
{
    return ApiFacadeTest::$fopen_result;
}

