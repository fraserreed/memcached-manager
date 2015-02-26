<?php

namespace MemcachedManager\Tests\Fixtures;


use MemcachedManager\Client\IClient;

class MockMemcached implements IClient
{
    protected $servers = array();

    protected $stats;

    protected $keys = array();

    protected $delayed;

    public function __construct( $servers = array() )
    {
        $this->servers = $servers;
    }

    public function getServers()
    {
        return $this->servers;
    }

    public function addServer( $host, $port )
    {
        $this->servers[ $host ][ $port ] = true;
    }

    public function setStats( $stats )
    {
        $this->stats = $stats;
    }

    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @return array
     */
    public function getAllKeys()
    {
        return $this->keys;
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return $this->getAllKeys();
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function getDelayed( $value )
    {
        $this->delayed = $value;
    }

    /**
     * @return mixed
     */
    public function fetchAll()
    {
        return $this->delayed;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function add( $key, $value )
    {
        $this->keys[ $key ] = $value;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function replace( $key, $value )
    {
        $this->keys[ $key ] = $value;
    }

    /**
     * @param $key
     *
     * @return null
     */
    public function get( $key )
    {
        return isset( $this->keys[ $key ] ) ? $this->keys[ $key ] : null;
    }

    /**
     * @param $key
     */
    public function delete( $key )
    {
        if( isset( $this->keys[ $key ] ) )
            unset( $this->keys[ $key ] );
    }

    /**
     * @param $key
     */
    public function increment( $key )
    {
        if( isset( $this->keys[ $key ] ) && is_integer( $this->keys[ $key ] ) )
            $this->keys[ $key ]++;
    }

    /**
     * @param $key
     */
    public function decrement( $key )
    {
        if( isset( $this->keys[ $key ] ) && is_integer( $this->keys[ $key ] ) )
            $this->keys[ $key ]--;
    }

    /**
     * @return IClient
     */
    public function getClientClass()
    {
        return $this;
    }

    /**
     * @param $host
     * @param $port
     *
     * @return bool
     */
    public function test( $host, $port )
    {
        return true;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function addKey( $key, $value )
    {
        $this->add( $key, $value );
    }

    /**
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function editKey( $key, $value )
    {
        $this->replace( $key, $value );
    }

    /**
     * @param $key
     *
     * @return \MemcachedManager\Memcached\Key
     */
    public function getKey( $key )
    {
        return $this->get( $key );
    }

    /**
     * @param $key
     *
     * @return void
     */
    public function deleteKey( $key )
    {
        $this->delete( $key );
    }

    /**
     * @param $key
     *
     * @return void
     */
    public function incrementKey( $key )
    {
        $this->increment( $key );
    }

    /**
     * @param $key
     *
     * @return void
     */
    public function decrementKey( $key )
    {
        $this->decrement( $key );
    }
}