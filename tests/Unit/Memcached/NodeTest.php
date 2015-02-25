<?php

namespace MemcachedManager\Tests\Unit\Memcached;


use MemcachedManager\Memcached\Node;
use MemcachedManager\Tests\Unit\UnitTestCase;

class NodeTest extends UnitTestCase
{

    public function testConstruct()
    {
        $node = new Node( 'local', 2211, 'some name' );

        $this->assertEquals( 'local', $node->getHost() );
        $this->assertEquals( 2211, $node->getPort() );
        $this->assertEquals( 'some name', $node->getName() );
    }

    public function testSetGetName()
    {
        $node = new Node( 'localhost', 8819 );
        $node->setName( 'the name of the node' );

        $this->assertEquals( 'the name of the node', $node->getName() );
    }

    public function testSetGetHost()
    {
        $node = new Node( 'localhost', 11211 );
        $node->setHost( 'http://not-localhost' );

        $this->assertEquals( 'http://not-localhost', $node->getHost() );
    }

    public function testSetGetPort()
    {
        $node = new Node( 'localhost', 11211 );
        $node->setPort( 8888 );

        $this->assertEquals( 8888, $node->getPort() );
    }

    public function testGetAlive()
    {
        $node = new Node( 'localhost', 11211 );
        $this->assertFalse( $node->getAlive() );
    }

    public function testSetAlive()
    {
        $node = new Node( 'localhost', 11211 );
        $node->setAlive( true );
        $this->assertTrue( $node->getAlive() );
    }
}