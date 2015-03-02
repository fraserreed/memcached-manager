<?php

namespace MemcachedManager\Tests\Unit\Client;


use MemcachedManager\Client\Direct;
use MemcachedManager\Memcached\Node;
use MemcachedManager\Tests\Fixtures\MockSocket;
use MemcachedManager\Tests\Unit\UnitTestCase;

class DirectTest extends UnitTestCase
{
    /**
     * @expectedException \MemcachedManager\Exceptions\NotImplementedException
     */
    public function testGetClientClass()
    {
        $direct = new Direct( array() );
        $direct->getClientClass();
    }

    /**
     * @expectedException \MemcachedManager\Exceptions\NotImplementedException
     */
    public function testTest()
    {
        $direct = new Direct( array() );
        $direct->test( 'localhost', 11211 );
    }

    /**
     * @expectedException \MemcachedManager\Exceptions\NotImplementedException
     */
    public function testGetStats()
    {
        $direct = new Direct( array() );
        $direct->getStats();
    }

    public function testGetKeysEmptyNoResponse()
    {
        $socket = new MockSocket( 'localhost', 11200 );
        $socket->setResponse( 'stats slabs', array() );

        $direct = new Direct( array( array( 'host' => 'localhost', 'port' => 11200 ) ) );
        $direct->setConnection( 'localhost', 11200, $socket );

        $this->assertEmpty( $direct->getKeys( array() ) );
    }

    public function testGetKeysEmpty()
    {
        $socket = new MockSocket( 'localhost', 11200 );
        $socket->setResponse( 'stats slabs', array( 'END' ) );

        $direct = new Direct( array( array( 'host' => 'localhost', 'port' => 11200 ) ) );
        $direct->setConnection( 'localhost', 11200, $socket );

        $this->assertEmpty( $direct->getKeys( array() ) );
    }

    public function testGetKeysOneSlabEmpty()
    {
        $socket = new MockSocket( 'localhost', 11200 );
        $socket->setResponse( 'stats slabs', array( 'STAT 3:chunk_size 152', 'END' ) );
        $socket->setResponse( 'stats items 3', array() );

        $direct = new Direct( array( array( 'host' => 'localhost', 'port' => 11200 ) ) );
        $direct->setConnection( 'localhost', 11200, $socket );

        $this->assertEmpty( $direct->getKeys( array() ) );
    }

    public function testGetKeysTwoSlabsEmpty()
    {
        $socket = new MockSocket( 'localhost', 11200 );
        $socket->setResponse( 'stats slabs', array( 'STAT 3:chunk_size 152', 'STAT 5:chunk_size 152', 'END' ) );
        $socket->setResponse( 'stats items 3', array() );
        $socket->setResponse( 'stats items 5', array() );

        $direct = new Direct( array( array( 'host' => 'localhost', 'port' => 11200 ) ) );
        $direct->setConnection( 'localhost', 11200, $socket );

        $this->assertEmpty( $direct->getKeys( array() ) );
    }

    public function testGetKeysOneSlabsOneItemOneKey()
    {
        $socket = new MockSocket( 'localhost', 11200 );
        $socket->setResponse( 'stats slabs', array( 'STAT 3:chunk_size 152', 'END' ) );
        $socket->setResponse( 'stats items 3', array( 'STAT items:3:number 1', 'END' ) );
        $socket->setResponse( 'stats cachedump 3 0', array( 'ITEM dimensions_31 [71 b; 1425524991 s]', 'END' ) );

        $direct = new Direct( array( array( 'host' => 'localhost', 'port' => 11200 ) ) );
        $direct->setConnection( 'localhost', 11200, $socket );

        $this->assertEquals( array( 'dimensions_31' => array( 'key' => 'dimensions_31' ) ), $direct->getKeys( array( new Node( 'localhost', 11200 ) ) ) );
    }

    public function testGetKeys()
    {
        $socket = new MockSocket( 'localhost', 11200 );
        $socket->setResponse( 'stats slabs', array( 'STAT 3:chunk_size 152', 'STAT 5:chunk_size 152', 'STAT 17:chunk_size 152', 'END' ) );
        $socket->setResponse( 'stats items 3', array( 'STAT items:3:number 1', 'STAT items:5:number 1', 'STAT items:11:number 1', 'END' ) );
        $socket->setResponse( 'stats items 5', array( 'STAT items:3:number 1', 'STAT items:6:number 1', 'STAT items:12:number 1', 'END' ) );
        $socket->setResponse( 'stats items 17', array( 'STAT items:2:number 1', 'END' ) );
        $socket->setResponse( 'stats cachedump 3 0', array( 'ITEM dimensions_31 [71 b; 1425524991 s]', 'END' ) );
        $socket->setResponse( 'stats cachedump 5 0', array( 'ITEM dimensions_32 [71 b; 1425524991 s]', 'END' ) );
        $socket->setResponse( 'stats cachedump 11 0', array( 'ITEM cluster_x [71 b; 1425524991 s]', 'END' ) );
        $socket->setResponse( 'stats cachedump 6 0', array( 'ITEM dimensions_31 [71 b; 1425524991 s]', 'END' ) );
        $socket->setResponse( 'stats cachedump 12 0', array( 'ITEM dimensions_38 [71 b; 1425524991 s]', 'END' ) );
        $socket->setResponse( 'stats cachedump 2 0', array( 'END' ) );

        $direct = new Direct( array( array( 'host' => 'localhost', 'port' => 11200 ) ) );
        $direct->setConnection( 'localhost', 11200, $socket );

        $keys = array(
            'cluster_x'     => array( 'key' => 'cluster_x' ),
            'dimensions_31' => array( 'key' => 'dimensions_31' ),
            'dimensions_32' => array( 'key' => 'dimensions_32' ),
            'dimensions_38' => array( 'key' => 'dimensions_38' )
        );

        $this->assertEquals( $keys, $direct->getKeys( array( new Node( 'localhost', 11200 ) ) ) );
    }

    /**
     * @expectedException \MemcachedManager\Exceptions\NotImplementedException
     */
    public function testAddKey()
    {
        $direct = new Direct( array() );
        $direct->addKey( 'key', 'value' );
    }

    /**
     * @expectedException \MemcachedManager\Exceptions\NotImplementedException
     */
    public function testEditKey()
    {
        $direct = new Direct( array() );
        $direct->editKey( 'key', 'value' );
    }

    /**
     * @expectedException \MemcachedManager\Exceptions\NotImplementedException
     */
    public function testGetKey()
    {
        $direct = new Direct( array() );
        $direct->getKey( 'key' );
    }

    /**
     * @expectedException \MemcachedManager\Exceptions\NotImplementedException
     */
    public function testDeleteKey()
    {
        $direct = new Direct( array() );
        $direct->deleteKey( 'key' );
    }

    /**
     * @expectedException \MemcachedManager\Exceptions\NotImplementedException
     */
    public function testIncrementKey()
    {
        $direct = new Direct( array() );
        $direct->incrementKey( 'key' );
    }

    /**
     * @expectedException \MemcachedManager\Exceptions\NotImplementedException
     */
    public function testDecrementKey()
    {
        $direct = new Direct( array() );
        $direct->decrementKey( 'key' );
    }
}
