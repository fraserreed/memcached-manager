<?php

namespace MemcachedManager\Tests\Unit;


use MemcachedManager\Client;
use MemcachedManager\Memcached\Cluster;
use MemcachedManager\Memcached\Key;
use MemcachedManager\Memcached\Node;
use MemcachedManager\Utils\Hash;

class ClientTest extends UnitTestCase
{

    public function testGetClusters()
    {
        $client = new Client( array( 'c1' => array( array( 'name' => 'local1', 'host' => 'localhost', 'port' => 11200 ) ) ), 'MemcachedManager\Tests\Fixtures\MockMemcached' );

        $clusters = $client->getClusters();

        $node = new Node( 'localhost', 11200, 'local1' );
        $node->setAlive( 1 );
        $expected = new Cluster( 'c1' );
        $expected->addNode( $node );

        $this->assertCount( 1, $clusters );

        $this->assertEquals( $expected, $clusters[ 'c1' ] );
    }

    public function testGetClusterNull()
    {
        $client = new Client( array( 'c1' => array( array( 'name' => 'local1', 'host' => 'localhost', 'port' => 11200 ) ) ), 'MemcachedManager\Tests\Fixtures\MockMemcached' );

        $this->assertNull( $client->getCluster( 'c2' ) );
    }

    public function testGetCluster()
    {
        $client = new Client( array( 'c1' => array( array( 'name' => 'local1', 'host' => 'localhost', 'port' => 11200 ) ) ), 'MemcachedManager\Tests\Fixtures\MockMemcached' );

        $node = new Node( 'localhost', 11200, 'local1' );
        $node->setAlive( 1 );
        $expected = new Cluster( 'c1' );
        $expected->addNode( $node );
        $this->assertEquals( $expected, $client->getCluster( 'c1' ) );
    }

    public function testGetNode()
    {
        $client = new Client( array( 'c1' => array( array( 'name' => 'local1', 'host' => 'localhost', 'port' => 11200 ), array( 'name' => 'local2', 'host' => 'localhost2', 'port' => 11201 ) ) ), 'MemcachedManager\Tests\Fixtures\MockMemcached' );

        $node2 = new Node( 'localhost2', 11201, 'local2' );
        $node2->setAlive( 1 );

        $this->assertEquals( $node2, $client->getNode( 'c1', 'local2' ) );
    }

    public function testGetNodeNull()
    {
        $client = new Client( array( 'c1' => array( array( 'name' => 'local1', 'host' => 'localhost', 'port' => 11200 ), array( 'name' => 'local2', 'host' => 'localhost2', 'port' => 11201 ) ) ), 'MemcachedManager\Tests\Fixtures\MockMemcached' );

        $node2 = new Node( 'localhost2', 11201, 'local2' );
        $node2->setAlive( 1 );

        $this->assertFalse( $client->getNode( 'c1', 'local3' ) );
    }

    public function testAddEditKey()
    {
        $client = new Client( array( 'c1' => array( array( 'name' => 'local1', 'host' => 'localhost', 'port' => 11200 ) ) ), 'MemcachedManager\Tests\Fixtures\MockMemcached' );

        $client->addKey( 'c1', 'k-test', 'k-value' );

        $expected = new Key();
        $expected->setKey( 'k-test' );
        $expected->setValue( 'k-value' );

        $this->assertEquals( $expected, $client->getKey( 'c1', Hash::encode( 'k-test' ) ) );

        $client->editKey( 'c1', Hash::encode( 'k-test' ), 'k-edit-value' );

        $expected->setValue( 'k-edit-value' );

        $this->assertEquals( $expected, $client->getKey( 'c1', Hash::encode( 'k-test' ) ) );

        $this->assertNull( $client->getKey( 'c1', 'k-test' ) );

        $client->deleteKey( 'c1', Hash::encode( 'k-test' ) );

        $this->assertNull( $client->getKey( 'c1', Hash::encode( 'k-test' ) ) );
    }

    public function testIncrementDecrementKey()
    {
        $client = new Client( array( 'c1' => array( array( 'name' => 'local1', 'host' => 'localhost', 'port' => 11200 ) ) ), 'MemcachedManager\Tests\Fixtures\MockMemcached' );

        $client->addKey( 'c1', 'k-test', 12 );

        $expected = new Key();
        $expected->setKey( 'k-test' );
        $expected->setValue( 12 );

        $this->assertEquals( $expected, $client->getKey( 'c1', Hash::encode( 'k-test' ) ) );

        $client->incrementKey( 'c1', Hash::encode( 'k-test' ) );
        $client->incrementKey( 'c1', Hash::encode( 'k-test' ) );

        $expected->setValue( 14 );

        $this->assertEquals( $expected, $client->getKey( 'c1', Hash::encode( 'k-test' ) ) );

        $client->decrementKey( 'c1', Hash::encode( 'k-test' ) );

        $expected->setValue( 13 );

        $this->assertEquals( $expected, $client->getKey( 'c1', Hash::encode( 'k-test' ) ) );
    }
}