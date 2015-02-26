<?php

namespace MemcachedManager\Tests\Unit;


use MemcachedManager\Client;
use MemcachedManager\Memcached\Cluster;
use MemcachedManager\Memcached\Node;

class ClientTest extends UnitTestCase
{

    public function testGetClusters()
    {
        $client = new Client( array( array( 'cluster' => 'c1', 'name' => 'local1', 'host' => 'localhost', 'port' => 11200 ) ), 'MemcachedManager\Tests\Fixtures\MockMemcached' );

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
        $client = new Client( array( array( 'cluster' => 'c1', 'name' => 'local1', 'host' => 'localhost', 'port' => 11200 ) ), 'MemcachedManager\Tests\Fixtures\MockMemcached' );

        $this->assertNull( $client->getCluster( 'c2' ) );
    }

    public function testGetCluster()
    {
        $client = new Client( array( array( 'cluster' => 'c1', 'name' => 'local1', 'host' => 'localhost', 'port' => 11200 ) ), 'MemcachedManager\Tests\Fixtures\MockMemcached' );

        $node = new Node( 'localhost', 11200, 'local1' );
        $node->setAlive( 1 );
        $expected = new Cluster( 'c1' );
        $expected->addNode( $node );
        $this->assertEquals( $expected, $client->getCluster( 'c1' ) );
    }

//    public function getNode( $clusterName, $nodeName )
//    {
////        $clusters = $this->getClusters();
////
////        if( isset( $clusters[ $clusterName ] ) )
////        {
////            /** @var $node \MemcachedManager\Memcached\Node */
////            foreach( $clusters[ $clusterName ]->getNodes() as $node )
////            {
////                if( $node->getName() == $nodeName )
////                    return $node;
////            }
////        }
////
////        return false;
//    }
//
//    public function addKey( $key, $value )
//    {
////        $this->getClient()->addKey( $key, $value );
//    }
//
//    public function editKey( $key, $value )
//    {
////        $this->getClient()->editKey( Hash::decode( $key ), $value );
//    }
//
//    public function getKey( $key )
//    {
////        $decodedKey = Hash::decode( $key );
////
////        if( $value = $this->getClient()->getKey( $decodedKey ) )
////        {
////            $object = new Key();
////            $object->setKey( $decodedKey );
////            $object->setValue( $value );
////
////            return $object;
////        }
////
////        return null;
//    }
//
//    public function deleteKey( $key )
//    {
////        $this->getClient()->deleteKey( Hash::decode( $key ) );
//    }
//
//    public function incrementKey( $key )
//    {
////        $this->getClient()->incrementKey( Hash::decode( $key ) );
//    }
//
//    public function decrementKey( $key )
//    {
////        $this->getClient()->decrementKey( Hash::decode( $key ) );
//    }
//
//    public function getAllKeys( $clusterName, $nodeName = '' )
//    {
////        return new KeyStore( $this->getClient()->getKeys() );
//    }
}