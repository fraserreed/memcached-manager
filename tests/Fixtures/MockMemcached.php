<?php

namespace MemcachedManager\Tests\Fixtures;


class MockMemcached
{
    protected $servers = array();

    protected $stats;

    protected $keys = array();

    protected $delayed;

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
}