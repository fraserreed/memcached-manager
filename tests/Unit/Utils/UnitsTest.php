<?php

namespace MemcachedManager\Tests\Unit\Utils;


use MemcachedManager\Tests\Unit\UnitTestCase;
use MemcachedManager\Utils\Units;

class UnitsTest extends UnitTestCase
{
    public function testReadableSize()
    {
        $this->assertEquals( '0 bytes', Units::readableSize( 0 ) );
        $this->assertEquals( '10 bytes', Units::readableSize( 10 ) );
        $this->assertEquals( '4 Kb', Units::readableSize( 3601 ) );
        $this->assertEquals( '182 Kb', Units::readableSize( 186401 ) );
        $this->assertEquals( '95 Mb', Units::readableSize( 99183773 ) );
        $this->assertEquals( '789 Mb', Units::readableSize( 827187546 ) );
        $this->assertEquals( '77.04 Gb', Units::readableSize( 82718754681 ) );
    }
}