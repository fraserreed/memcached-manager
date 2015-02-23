<?php

namespace MemcachedManager\Memcached;


class KeyStore
{
    /**
     * @var array
     */
    protected $keys;

    /**
     * @param array $keys
     */
    public function __construct( array $keys )
    {
        /** @var $node \MemcachedManager\Memcached\Node */
        foreach( $keys as $value )
        {
            $key = new Key();
            $key->setKey( isset( $value[ 'key' ] ) ? $value[ 'key' ] : null );
            $key->setValue( isset( $value[ 'value' ] ) ? $value[ 'value' ] : null );
            $key->setCas( isset( $value[ 'cas' ] ) ? $value[ 'cas' ] : null );

            $this->addKey( $value[ 'key' ], $key );
        }

        //sort the keys
        ksort( $this->keys );
    }

    /**
     * @param null $index
     * @param Key  $key
     */
    public function addKey( $index = null, Key $key )
    {
        if( $index )
            $this->keys[ $index ] = $key;
        else
            $this->keys[ ] = $key;
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * @param array $keys
     */
    public function setKeys( $keys )
    {
        $this->keys = $keys;
    }
}