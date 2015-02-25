<?php

namespace MemcachedManager\Tests\Unit\Client;


use MemcachedManager\Client\Memcached;
use MemcachedManager\Tests\Fixtures\MockMemcached;
use MemcachedManager\Tests\Unit\UnitTestCase;

class MemcachedTest extends UnitTestCase
{
    public function testGetClientClass()
    {
        $memcached = new Memcached( array() );
        $this->assertInstanceOf( '\Memcached', $memcached->getClientClass() );
    }

    public function testTestPass()
    {
        $baseClass = new MockMemcached();
        $baseClass->setStats( array( 'localhost:11211' => array( 'pid' => 111 ) ) );

        $memcached = new Memcached( array() );
        $memcached->setClientClass( $baseClass );

        $this->assertTrue( $memcached->test( 'localhost', 11211 ) );
    }

    public function testTestFail()
    {
        $baseClass = new MockMemcached();

        $memcached = new Memcached( array() );
        $memcached->setClientClass( $baseClass );

        $this->assertFalse( $memcached->test( 'localhost', 11211 ) );
    }

    public function testAddEditGetKey()
    {
        $baseClass = new MockMemcached();

        $memcached = new Memcached( array() );
        $memcached->setClientClass( $baseClass );

        $memcached->addKey( 'keytest', 'valuetest' );

        $this->assertEquals( array( 'keytest' => 'valuetest' ), $memcached->getKeys() );

        $memcached->editKey( 'keytest', 'not the same value' );

        $this->assertEquals( array( 'keytest' => 'not the same value' ), $memcached->getKeys() );

        $this->assertEquals( 'not the same value', $memcached->getKey( 'keytest' ) );

        $memcached->deleteKey( 'keytest' );

        $this->assertNull( $memcached->getKey( 'keytest' ) );
    }

    public function testIncrementDecrementKey()
    {
        $baseClass = new MockMemcached();

        $memcached = new Memcached( array() );
        $memcached->setClientClass( $baseClass );

        $memcached->addKey( 'keytest', 12 );

        $this->assertEquals( array( 'keytest' => 12 ), $memcached->getKeys() );

        $memcached->incrementKey( 'keytest' );

        $this->assertEquals( array( 'keytest' => 13 ), $memcached->getKeys() );

        $memcached->incrementKey( 'keytest' );

        $memcached->incrementKey( 'keytest' );

        $this->assertEquals( array( 'keytest' => 15 ), $memcached->getKeys() );

        $memcached->decrementKey( 'keytest' );

        $this->assertEquals( array( 'keytest' => 14 ), $memcached->getKeys() );
    }
}