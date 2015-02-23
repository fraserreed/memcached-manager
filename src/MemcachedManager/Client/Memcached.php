<?php

namespace MemcachedManager\Client;


use Memcached as PhpMemcached;

class Memcached extends AbstractClient
{
    /**
     * @return PhpMemcached
     */
    public function getClientClass()
    {
        return new PhpMemcached();
    }

    /**
     * @param $host
     * @param $port
     *
     * @return bool
     */
    public function test( $host, $port )
    {
        $client = $this->getClientClass();
        $client->addServer( $host, $port );

        $stats = $client->getStats();

        return ( isset( $stats[ $host . ':' . $port ][ 'pid' ] ) && $stats[ $host . ':' . $port ][ 'pid' ] > 0 ) ? true : false;
    }

    /**
     * Add a key/value pair to the data source
     *
     * @param $key
     * @param $value
     */
    public function addKey( $key, $value )
    {
        /** @var $client \Memcached */
        $client = $this->getClient();
        $client->add( $key, $value );
    }

    /**
     * Replace a key/value pair to the data source
     *
     * @param $key
     * @param $value
     */
    public function editKey( $key, $value )
    {
        /** @var $client \Memcached */
        $client = $this->getClient();
        $client->replace( $key, $value );
    }

    /**
     * Retrieve a key/value pair from the data source
     *
     * @param $key
     *
     * @return \MemcachedManager\Memcached\Key
     */
    public function getKey( $key )
    {
        /** @var $client \Memcached */
        $client = $this->getClient();

        return $client->get( $key );
    }

    /**
     * Purge a key/value pair from the data source
     *
     * @param $key
     */
    public function deleteKey( $key )
    {
        /** @var $client \Memcached */
        $client = $this->getClient();
        $client->delete( $key );
    }

    /**
     * Increment the value of a key in the data source
     *
     * @param $key
     */
    public function incrementKey( $key )
    {
        /** @var $client \Memcached */
        $client = $this->getClient();
        $client->increment( $key );
    }

    /**
     * Decrement the value of a key in the data source
     *
     * @param $key
     */
    public function decrementKey( $key )
    {
        /** @var $client \Memcached */
        $client = $this->getClient();
        $client->decrement( $key );
    }

    /**
     * Retrieve all keys from the data source
     *
     * @return array
     */
    public function getKeys()
    {
        /** @var $client \Memcached */
        $client = $this->getClient();

        $client->getDelayed( $client->getAllKeys(), true );

        return $client->fetchAll();
    }
}