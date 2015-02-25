<?php

namespace MemcachedManager\Tests\Unit\Memcached;


use MemcachedManager\Memcached\Key;
use MemcachedManager\Memcached\KeyStore;
use MemcachedManager\Tests\Unit\UnitTestCase;

class KeyStoreTest extends UnitTestCase
{
    public function testConstructEmpty()
    {
        $store = new KeyStore( array() );

        $this->assertEquals( 0, count( $store->getKeys() ) );
    }

    public function testConstructNotEmpty()
    {
        $store = new KeyStore( array( array( 'key' => 'testKey', 'value' => 'testValue' ) ) );

        $expectedKey = new Key();
        $expectedKey->setKey( 'testKey' );
        $expectedKey->setValue( 'testValue' );

        $keys = $store->getKeys();
        $this->assertCount( 1, $keys );

        $this->assertEquals( $expectedKey, $keys[ 'testKey' ] );
    }

    public function testAddKey()
    {
        $expectedKeyOne = new Key();
        $expectedKeyOne->setKey( 'testKey' );
        $expectedKeyOne->setValue( 'testValue' );

        $expectedKeyTwo = new Key();
        $expectedKeyTwo->setKey( 'moreTestKey' );
        $expectedKeyTwo->setValue( 'moreTestValue' );

        $expected = array(
            'testKey' => $expectedKeyOne,
            0         => $expectedKeyTwo
        );

        $store = new KeyStore( array( array( 'key' => 'testKey', 'value' => 'testValue' ) ) );
        $store->addKey( null, $expectedKeyTwo );

        $this->assertEquals( $expected, $store->getKeys() );
    }

    public function testSetKeys()
    {
        $store = new KeyStore( array() );
        $this->assertEquals( 0, count( $store->getKeys() ) );

        $expectedKeyOne = new Key();
        $expectedKeyOne->setKey( 'testKey' );
        $expectedKeyOne->setValue( 'testValue' );

        $expectedKeyTwo = new Key();
        $expectedKeyTwo->setKey( 'moreTestKey' );
        $expectedKeyTwo->setValue( 'moreTestValue' );

        $store->setKeys( array( $expectedKeyOne, $expectedKeyTwo ) );

        $expected = array(
            0 => $expectedKeyOne,
            1 => $expectedKeyTwo
        );

        $this->assertEquals( $expected, $store->getKeys() );
    }
}