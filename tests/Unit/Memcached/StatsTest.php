<?php

namespace MemcachedManager\Tests\Unit\Memcached;


use MemcachedManager\Memcached\Stats;
use MemcachedManager\Tests\Unit\UnitTestCase;

class StatsTest extends UnitTestCase
{

    public function testIncrementActiveNodes()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getActiveNodes() );
        $stats->incrementActiveNodes();
        $this->assertEquals( 1, $stats->getActiveNodes() );
        $stats->incrementActiveNodes();
        $stats->incrementActiveNodes();
        $this->assertEquals( 3, $stats->getActiveNodes() );
    }

    public function testSetGetPid()
    {
        $stats = new Stats();

        $this->assertNull( $stats->getPid() );
        $stats->setPid( 199 );
        $this->assertEquals( 199, $stats->getPid() );
    }

    public function testSetGetUptime()
    {
        $stats = new Stats();

        $this->assertNull( $stats->getUptime() );
        $stats->setUptime( 10 );
        $this->assertEquals( '10 seconds', $stats->getUptime() );

        $stats->setUptime( 61 );
        $this->assertEquals( '1 minute 1 second', $stats->getUptime() );

        $stats->setUptime( 62 );
        $this->assertEquals( '1 minute 2 seconds', $stats->getUptime() );

        $stats->setUptime( 138 );
        $this->assertEquals( '2 minutes 18 seconds', $stats->getUptime() );

        $stats->setUptime( 3601 );
        $this->assertEquals( '1 hour 0 minutes 1 second', $stats->getUptime() );

        $stats->setUptime( 3662 );
        $this->assertEquals( '1 hour 1 minute 2 seconds', $stats->getUptime() );

        $stats->setUptime( 86401 );
        $this->assertEquals( '1 day 0 hours 0 minutes 1 second', $stats->getUptime() );

        $stats->setUptime( 93773 );
        $this->assertEquals( '1 day 2 hours 2 minutes 53 seconds', $stats->getUptime() );

        $stats->setUptime( 187546 );
        $this->assertEquals( '2 days 4 hours 5 minutes 46 seconds', $stats->getUptime() );

        $stats->setUptime( 187560 );
        $this->assertEquals( '2 days 4 hours 6 minutes 0 seconds', $stats->getUptime() );
    }

    public function testSetGetThreads()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getThreads() );
        $stats->setThreads( 299 );
        $this->assertEquals( 299, $stats->getThreads() );
    }

    public function testSetGetPointerSize()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getPointerSize() );
        $stats->setPointerSize( 299 );
        $this->assertEquals( 299, $stats->getPointerSize() );
    }

    public function testSetGetVersion()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getVersion() );
        $stats->setVersion( 399 );
        $this->assertEquals( 399, $stats->getVersion() );
    }

    public function testSetGetCurrItems()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getCurrItems() );
        $stats->setCurrItems( 499 );
        $this->assertEquals( 499, $stats->getCurrItems() );
    }

    public function testSetGetTotalItems()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getTotalItems() );
        $stats->setTotalItems( 599 );
        $this->assertEquals( 599, $stats->getTotalItems() );
    }

    public function testSetGetLimitMaxbytes()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getLimitMaxbytes() );
        $stats->setLimitMaxbytes( 699 );
        $this->assertEquals( 699, $stats->getLimitMaxbytes() );
    }

    public function testGetLimitMaxsize()
    {
        $stats = new Stats();

        $this->assertEquals( '0 bytes', $stats->getLimitMaxsize() );

        $stats->setLimitMaxbytes( 1000 );
        $this->assertEquals( '1,000 bytes', $stats->getLimitMaxsize() );

        $stats->setLimitMaxbytes( 667880 );
        $this->assertEquals( '652 Kb', $stats->getLimitMaxsize() );

        $stats->setLimitMaxbytes( 667880 );
        $this->assertEquals( '652 Kb', $stats->getLimitMaxsize() );

        $stats->setLimitMaxbytes( 11992109 );
        $this->assertEquals( '11 Mb', $stats->getLimitMaxsize() );

        $stats->setLimitMaxbytes( 29090121898 );
        $this->assertEquals( '27.09 Gb', $stats->getLimitMaxsize() );
    }

    public function testSetGetCurrConnections()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getCurrConnections() );
        $stats->setCurrConnections( 799 );
        $this->assertEquals( 799, $stats->getCurrConnections() );
    }

    public function testSetGetTotalConnections()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getTotalConnections() );
        $stats->setTotalConnections( 899 );
        $this->assertEquals( 899, $stats->getTotalConnections() );
    }

    public function testSetGetConnectionStructures()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getConnectionStructures() );
        $stats->setConnectionStructures( 999 );
        $this->assertEquals( 999, $stats->getConnectionStructures() );
    }

    public function testSetGetBytes()
    {
        $stats = new Stats();

        $this->assertEquals( '0 bytes', $stats->getBytes() );
        $stats->setBytes( 1099 );
        $this->assertEquals( '1 Kb', $stats->getBytes() );
        $this->assertEquals( 1099, $stats->getRawBytes() );
    }

    public function testGetBytesFreeUsed()
    {
        $stats = new Stats();

        $this->assertEquals( '0 bytes', $stats->getBytesFree() );
        $this->assertEquals( 0, $stats->getBytesFreePercentage() );
        $this->assertEquals( 0, $stats->getBytesUsedPercentage() );

        $stats->setBytes( 10000 );
        $stats->setLimitMaxbytes( 200000 );

        $this->assertEquals( '186 Kb', $stats->getBytesFree() );
        $this->assertEquals( 95, $stats->getBytesFreePercentage() );
        $this->assertEquals( 5, $stats->getBytesUsedPercentage() );

        $stats->setBytes( 152000 );
        $this->assertEquals( '47 Kb', $stats->getBytesFree() );
        $this->assertEquals( 24, $stats->getBytesFreePercentage() );
        $this->assertEquals( 76, $stats->getBytesUsedPercentage() );

        $stats->setBytes( 196000 );
        $this->assertEquals( '4 Kb', $stats->getBytesFree() );
        $this->assertEquals( 2, $stats->getBytesFreePercentage() );
        $this->assertEquals( 98, $stats->getBytesUsedPercentage() );
    }

    public function testSetGetCmdGet()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getCmdGet() );
        $stats->setCmdGet( 1199 );
        $this->assertEquals( 1199, $stats->getCmdGet() );
    }

    public function testSetGetCmdSet()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getCmdSet() );
        $stats->setCmdSet( 1299 );
        $this->assertEquals( 1299, $stats->getCmdSet() );
    }

    public function testSetGetGetHits()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getGetHits() );
        $stats->setGetHits( 1399 );
        $this->assertEquals( 1399, $stats->getGetHits() );

        $this->assertEquals( 100, $stats->getGetHitsPercentage() );

        $stats->setGetMisses( 129 );
        $this->assertEquals( 91.56, $stats->getGetHitsPercentage() );
        $stats->setGetMisses( 700 );
        $this->assertEquals( 66.65, $stats->getGetHitsPercentage() );
    }

    public function testSetGetGetMisses()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getGetMisses() );
        $stats->setGetMisses( 1499 );
        $this->assertEquals( 1499, $stats->getGetMisses() );

        $this->assertEquals( 100, $stats->getGetMissesPercentage() );
        $stats->setGetHits( 129 );
        $this->assertEquals( 92.08, $stats->getGetMissesPercentage() );
        $stats->setGetHits( 700 );
        $this->assertEquals( 68.17, $stats->getGetMissesPercentage() );

    }

    public function testSetGetEvictions()
    {
        $stats = new Stats();

        $this->assertEquals( 0, $stats->getEvictions() );
        $stats->setEvictions( 1599 );
        $this->assertEquals( 1599, $stats->getEvictions() );
    }

    public function testSetGetBytesRead()
    {
        $stats = new Stats();

        $this->assertEquals( '0 bytes', $stats->getBytesRead() );
        $stats->setBytesRead( 1699 );
        $this->assertEquals( '2 Kb', $stats->getBytesRead() );
    }

    public function testSetGetBytesWritten()
    {
        $stats = new Stats();

        $this->assertEquals( '0 bytes', $stats->getBytesWritten() );
        $stats->setBytesWritten( 1799 );
        $this->assertEquals( '2 Kb', $stats->getBytesWritten() );
    }
}