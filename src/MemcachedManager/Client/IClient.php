<?php

namespace MemcachedManager\Client;


interface IClient
{
    /**
     * @return \Memcached|\Memcache
     */
    public function getClientClass();

    /**
     * @param $host
     * @param $port
     *
     * @return bool
     */
    public function test( $host, $port );

    /**
     * @return \MemcachedManager\Memcached\Stats
     */
    public function getStats();

    /**
     * @return \MemcachedManager\Memcached\KeyStore
     */
    public function getKeys( array $nodes );

    /**
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function addKey( $key, $value );

    /**
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function editKey( $key, $value );

    /**
     * @param $key
     *
     * @return \MemcachedManager\Memcached\Key
     */
    public function getKey( $key );

    /**
     * @param $key
     *
     * @return void
     */
    public function deleteKey( $key );

    /**
     * @param $key
     *
     * @return void
     */
    public function incrementKey( $key );

    /**
     * @param $key
     *
     * @return void
     */
    public function decrementKey( $key );
}