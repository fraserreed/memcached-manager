<?php

namespace MemcachedManager\Tests\Unit\Memcached;


use MemcachedManager\Memcached\Key;
use MemcachedManager\Tests\Unit\UnitTestCase;

class KeyTest extends UnitTestCase
{

    public function testGetHash()
    {
        $key = new Key();

        $this->assertNull( $key->getHash() );

        $key->setKey( '100ish' );
        $this->assertEquals( 'eSIgy0VCr3enDJvtITvbrq1B0Tt0Fh3OPMgidk0Zb98=', $key->getHash() );
    }

    public function testGetType()
    {
        $key = new Key();

        $this->assertNull( $key->getType() );

        $key->setValue( 'stringy' );
        $this->assertEquals( Key::TYPE_STRING, $key->getType() );

        $key->setValue( 200 );
        $this->assertEquals( Key::TYPE_INT, $key->getType() );

        $key->setValue( array( 'array' => array( 'of' => array( 'arrays' ) ) ) );
        $this->assertEquals( Key::TYPE_ARRAY, $key->getType() );

        $key->setValue( new \StdClass() );
        $this->assertEquals( Key::TYPE_OBJECT, $key->getType() );
    }

    public function testIsInteger()
    {
        $key = new Key();

        $this->assertFalse( $key->isInteger() );
        $key->setValue( 101 );
        $this->assertTrue( $key->isInteger() );
        $key->setValue( 'stringy' );
        $this->assertFalse( $key->isInteger() );
    }

    public function testSetGetKey()
    {
        $key = new Key();
        $this->assertNull( $key->getKey() );
        $key->setKey( 'heyo' );
        $this->assertEquals( 'heyo', $key->getKey() );
    }

    public function testSetGetValue()
    {
        $key = new Key();
        $this->assertNull( $key->getValue() );
        $key->setValue( 'veewee' );
        $this->assertEquals( 'veewee', $key->getValue() );
    }

    public function testSetGetCas()
    {
        $key = new Key();
        $this->assertNull( $key->getCas() );
        $key->setCas( 19 );
        $this->assertEquals( 19, $key->getCas() );
    }
}