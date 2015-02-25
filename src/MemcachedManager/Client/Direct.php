<?php

namespace MemcachedManager\Client;


use MemcachedManager\Connection\Socket;
use MemcachedManager\Exceptions\NotImplementedException;

class Direct extends AbstractClient
{
    /**
     * @throws NotImplementedException
     * @return \Memcached|\Memcache
     */
    public function getClientClass()
    {
        throw new NotImplementedException();
    }

    /**
     * @param $host
     * @param $port
     *
     * @throws NotImplementedException
     * @return bool
     */
    public function test( $host, $port )
    {
        throw new NotImplementedException();
    }

    /**
     * @throws NotImplementedException
     * @return \MemcachedManager\Memcached\Stats
     */
    public function getStats()
    {
        throw new NotImplementedException();
    }

    /**
     * @throws NotImplementedException
     * @return array
     */
    public function getKeys()
    {
        $keys = array();

        foreach( $this->getServers() as $server )
        {
            $connection = new Socket( $server[ 'host' ], $server[ 'port' ] );

            $slabs = $this->processRawSlabs( $connection->execute( 'stats slabs' ) );

            if( count( $slabs ) > 0 )
            {
                foreach( $slabs as $slabId => $slab )
                {
                    $items = $this->processRawItems( $connection->execute( 'stats items ' . $slabId ) );

                    if( count( $items ) > 0 )
                    {
                        foreach( $items as $itemId => $item )
                        {
                            $keys = $this->processRawKeys( $keys, $connection->execute( 'stats cachedump ' . $itemId . ' 0' ) );
                        }
                    }
                }
            }
        }

        return $keys;
    }

    /**
     * @param array $response
     *
     * @return array
     */
    private function processRawSlabs( array $response )
    {
        $slabs = array();

        foreach( $response as $item )
        {
            preg_match( '/^STAT\s(\d+)\:(\w+)\s(\d+)/', $item, $m );

            if( $m && isset( $m[ 1 ] ) )
                $slabs[ $m[ 1 ] ][ $m[ 2 ] ] = $m[ 3 ];
        }

        return $slabs;
    }

    /**
     * @param array $response
     *
     * @return array
     */
    private function processRawItems( array $response )
    {
        $items = array();

        foreach( $response as $item )
        {
            preg_match( '/^STAT\sitems:(\d+)\:(\w+)\s(\d+)/', $item, $m );

            if( $m && isset( $m[ 1 ] ) )
                $items[ $m[ 1 ] ][ $m[ 2 ] ] = $m[ 3 ];
        }

        return $items;
    }

    /**
     * @param array $keys
     * @param array $response
     *
     * @return array
     */
    private function processRawKeys( array $keys, array $response )
    {
        foreach( $response as $item )
        {
            preg_match( '/^ITEM\s([^\s]+)\s/', $item, $m );

            if( $m && isset( $m[ 1 ] ) && !isset( $keys[ $m[ 1 ] ] ) )
            {
                $keys[ $m[ 1 ] ] = array(
                    'key' => $m[ 1 ]
                );
            }
        }

        return $keys;
    }

    /**
     * @param $key
     * @param $value
     *
     * @throws NotImplementedException
     * @return void
     */
    public function addKey( $key, $value )
    {
        throw new NotImplementedException();
    }

    /**
     * @param $key
     * @param $value
     *
     * @throws NotImplementedException
     * @return void
     */
    public function editKey( $key, $value )
    {
        throw new NotImplementedException();
    }

    /**
     * @param $key
     *
     * @throws NotImplementedException
     * @return \MemcachedManager\Memcached\Key
     */
    public function getKey( $key )
    {
        throw new NotImplementedException();
    }

    /**
     * @param $key
     *
     * @throws NotImplementedException
     * @return void
     */
    public function deleteKey( $key )
    {
        throw new NotImplementedException();
    }

    /**
     * @param $key
     *
     * @throws NotImplementedException
     * @return void
     */
    public function incrementKey( $key )
    {
        throw new NotImplementedException();
    }

    /**
     * @param $key
     *
     * @throws NotImplementedException
     * @return void
     */
    public function decrementKey( $key )
    {
        throw new NotImplementedException();
    }
}
