<?php

namespace MemcachedManager\Tests\Unit;


use MemcachedManager\Config;

class ConfigTest extends UnitTestCase
{
    public function testReadAll()
    {
        $data = array( 'one' => 'value one', 'two' => 'value two' );
        Config::writeBulk( $data );

        $this->assertCount( 2, Config::readAll() );
        $this->assertEquals( $data, Config::readAll() );
    }

    public function testReadEmpty()
    {
        $this->assertNull( Config::read( 'testing' ) );
    }

    public function testRead()
    {
        Config::write( 'testing', 'not empty' );

        $this->assertEquals( 'not empty', Config::read( 'testing' ) );
    }

    public function testWriteBulk()
    {
        $data = array( 'one' => 'value one', 'two' => 'value two' );
        Config::writeBulk( $data );

        $this->assertEquals( 'value two', Config::read( 'two' ) );
    }
}