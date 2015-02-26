<?php

namespace MemcachedManager\Tests\Unit\Client;


use MemcachedManager\Client\Direct;
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

    public function testGetStatsEmpty()
    {
        $baseClass = new MockMemcached();
        $baseClass->setStats( array( 'localhost:11211' => array( 'pid' => 111 ) ) );

        $memcached = new Memcached( array( array( 'host' => 'localhost', 'port' => 11211 ) ) );
        $memcached->setClientClass( $baseClass );

        $stats = $memcached->getStats();

        $this->assertInstanceOf( 'MemcachedManager\Memcached\Stats', $stats );
        $this->assertEquals( 0, $stats->getPid() );
        $this->assertEquals( 0, $stats->getActiveNodes() );
    }

    public function testGetStatsNull()
    {
        $memcached = new Memcached( array( array( 'host' => 'localhost', 'port' => 11211 ) ) );
        $memcached->setClientClass( new MockMemcached() );

        $this->assertNull( $memcached->getStats() );
    }

    public function testGetStats()
    {
        $expectedStats = array(
            'pid'                   => 10000,
            'uptime'                => 10001,
            'threads'               => 10002,
            'pointer_size'          => 10003,
            'version'               => '12.1.1',
            'curr_items'            => 10004,
            'total_items'           => 10005,
            'limit_maxbytes'        => 10006,
            'curr_connections'      => 10007,
            'total_connections'     => 10008,
            'connection_structures' => 10009,
            'bytes'                 => 10010,
            'cmd_get'               => 10011,
            'cmd_set'               => 10012,
            'get_hits'              => 10013,
            'get_misses'            => 10014,
            'evictions'             => 10015,
            'bytes_read'            => 10016,
            'bytes_written'         => 10017
        );

        $baseClass = new MockMemcached();
        $baseClass->setStats( array( 'localhost:11211' => $expectedStats ) );

        $memcached = new Memcached( array( array( 'host' => 'localhost', 'port' => 11211 ) ) );
        $memcached->setClientClass( $baseClass );

        $stats = $memcached->getStats();

        $this->assertInstanceOf( 'MemcachedManager\Memcached\Stats', $stats );
        $this->assertEquals( 1, $stats->getActiveNodes() );
        $this->assertEquals( 10000, $stats->getPid() );
        $this->assertEquals( '2 hours 46 minutes 41 seconds', $stats->getUptime() );
        $this->assertEquals( 10002, $stats->getThreads() );
        $this->assertEquals( 10003, $stats->getPointerSize() );
        $this->assertEquals( '12.1.1', $stats->getVersion() );
        $this->assertEquals( 10004, $stats->getCurrItems() );
        $this->assertEquals( 10005, $stats->getTotalItems() );
        $this->assertEquals( 10006, $stats->getLimitMaxbytes() );
        $this->assertEquals( 10007, $stats->getCurrConnections() );
        $this->assertEquals( 10008, $stats->getTotalConnections() );
        $this->assertEquals( 10009, $stats->getConnectionStructures() );
        $this->assertEquals( 10010, $stats->getBytes() );
        $this->assertEquals( 10011, $stats->getCmdGet() );
        $this->assertEquals( 10012, $stats->getCmdSet() );
        $this->assertEquals( 10013, $stats->getGetHits() );
        $this->assertEquals( 10014, $stats->getGetMisses() );
        $this->assertEquals( 10015, $stats->getEvictions() );
        $this->assertEquals( 10016, $stats->getBytesRead() );
        $this->assertEquals( 10017, $stats->getBytesWritten() );
    }

    public function testGetServers()
    {
        $memcached = new Memcached( array( array( 'host' => 'localhost', 'port' => 11211 ) ) );
        $this->assertEquals( array( array( 'host' => 'localhost', 'port' => 11211 ) ), $memcached->getServers() );
    }

    public function testGetDirectClient()
    {
        $memcached = new Memcached( array( array( 'host' => 'localhost', 'port' => 11211 ) ) );
        $this->assertEquals( new Direct( array( array( 'host' => 'localhost', 'port' => 11211 ) ) ), $memcached->getDirectClient() );
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