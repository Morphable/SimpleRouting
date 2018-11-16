<?php

use PHPUnit\Framework\TestCase;

use \Morphable\SimpleRouting\Path;

class PathTest extends TestCase
{
    public function testPath()
    {
        $should = "/test/1";
        $is = Path::normalize("/test/1");
        $this->assertSame($should, $is);
    }
}
