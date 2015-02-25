<?php

namespace MemcachedManager\Tests\Unit\Memcached;


use MemcachedManager\Memcached\Cluster;
use MemcachedManager\Memcached\Node;
use MemcachedManager\Memcached\Stats;
use MemcachedManager\Tests\Unit\UnitTestCase;

class ClusterTest extends UnitTestCase
{
    public function testConstruct()
    {
        $named = new Cluster( 'localhost' );
        $this->assertEquals( 'localhost', $named->getName() );

        $notNamed = new Cluster();
        $this->assertNull( $notNamed->getName() );
    }

    public function testGetSetName()
    {
        $cluster = new Cluster();

        $cluster->setName( 'test cluster' );
        $this->assertEquals( 'test cluster', $cluster->getName() );
    }

    public function testGetNodes()
    {
        $cluster = new Cluster();

        $this->assertEmpty( $cluster->getNodes() );
    }

    public function testAddNode()
    {
        $cluster = new Cluster();

        $this->assertEmpty( $cluster->getNodes() );

        $cluster->addNode( new Node( 'localhost', 11211, 'testing' ) );

        $this->assertNotEmpty( $cluster->getNodes() );
        $this->assertCount( 1, $cluster->getNodes() );

        $nodes = $cluster->getNodes();

        $this->assertEquals( 'localhost', $nodes[ 0 ]->getHost() );
        $this->assertEquals( 11211, $nodes[ 0 ]->getPort() );
        $this->assertEquals( 'testing', $nodes[ 0 ]->getName() );
    }

    public function testSetNodes()
    {
        $cluster = new Cluster();

        $nodeOne   = new Node( 'localhost', 11211 );
        $nodeTwo   = new Node( 'loocalhoost', 12121 );
        $nodeThree = new Node( 'loccalhosst', 11112 );

        $cluster->setNodes( array( $nodeOne, $nodeTwo, $nodeThree ) );

        $nodes = $cluster->getNodes();

        $this->assertCount( 3, $nodes );
        $this->assertEquals( array( $nodeOne, $nodeTwo, $nodeThree ), $nodes );
    }

    public function testGetStats()
    {
        $cluster = new Cluster();

        $this->assertNull( $cluster->getStats() );
    }

    public function testSetStats()
    {
        $stats = new Stats();
        $stats->setPid( 8991 );

        $cluster = new Cluster();
        $cluster->setStats( $stats );

        $this->assertEquals( $stats, $cluster->getStats() );
    }
}