<?php

namespace MemcachedManager\Tests\Unit\Utils;


use MemcachedManager\Tests\Unit\UnitTestCase;
use MemcachedManager\Utils\Time;

class TimeTest extends UnitTestCase
{
    public function testReadableTime()
    {
        $this->assertEquals( '', Time::readableTime( 0 ) );
        $this->assertEquals( '10 seconds', Time::readableTime( 10 ) );
        $this->assertEquals( '1 minute 1 second', Time::readableTime( 61 ) );
        $this->assertEquals( '1 minute 2 seconds', Time::readableTime( 62 ) );
        $this->assertEquals( '2 minutes 18 seconds', Time::readableTime( 138 ) );
        $this->assertEquals( '1 hour 0 minutes 1 second', Time::readableTime( 3601 ) );
        $this->assertEquals( '1 hour 1 minute 2 seconds', Time::readableTime( 3662 ) );
        $this->assertEquals( '1 day 0 hours 0 minutes 1 second', Time::readableTime( 86401 ) );
        $this->assertEquals( '1 day 2 hours 2 minutes 53 seconds', Time::readableTime( 93773 ) );
        $this->assertEquals( '2 days 4 hours 5 minutes 46 seconds', Time::readableTime( 187546 ) );
        $this->assertEquals( '2 days 4 hours 6 minutes 0 seconds', Time::readableTime( 187560 ) );
    }
}