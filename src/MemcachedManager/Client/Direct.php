<?php

namespace MemcachedManager\Client;


use MemcachedManager\Connection\IConnection;
use MemcachedManager\Connection\Socket;
use MemcachedManager\Exceptions\NotImplementedException;

class Direct extends AbstractClient
{
    private $connections = array();

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
     * Expose setter for testing
     *
     * @param             $host
     * @param             $port
     * @param IConnection $socket
     */
    public function setConnection( $host, $port, IConnection $socket )
    {
        $this->connections[ $host . ':' . $port ] = $socket;
    }

    /**
     * @param $host
     * @param $port
     *
     * @return mixed
     */
    private function getConnection( $host, $port )
    {
        if( !isset( $this->connections[ $host . ':' . $port ] ) )
            $this->connections[ $host . ':' . $port ] = new Socket( $host, $port );

        return $this->connections[ $host . ':' . $port ];
    }

    /**
     * @throws NotImplementedException
     * @return array
     */
    public function getKeys( array $nodes )
    {
        $keys = array();

        /** @var $node \MemcachedManager\Memcached\Node */
        foreach( $nodes as $node )
        {
            $connection = $this->getConnection( $node->getHost(), $node->getPort() );

            $slabs = $this->processRawResponse( '/^STAT\s(\d+)\:(\w+)\s(\d+)/', $connection->execute( 'stats slabs' ) );

            if( count( $slabs ) > 0 )
            {
                foreach( $slabs as $slabId => $slab )
                {
                    $items = $this->processRawResponse( '/^STAT\sitems:(\d+)\:(\w+)\s(\d+)/', $connection->execute( 'stats items ' . $slabId ) );

                    if( count( $items ) > 0 )
                    {
                        foreach( $items as $itemId => $item )
                        {
                            $keys = $this->processRawKeys( $keys, $connection->execute( 'stats cachedump ' . $itemId . ' 0' ) );

                            if ( count( $keys ) > 20 )
                                break( 3 );
                        }
                    }
                }
            }
        }

        return $keys;
    }

    /**
     * @param       $regex
     * @param array $response
     *
     * @return array
     */
    private function processRawResponse( $regex, array $response )
    {
        $slabs = array();

        if( !empty( $response ) )
        {
            foreach( $response as $item )
            {
                preg_match( $regex, $item, $m );

                if( $m && isset( $m[ 1 ] ) )
                    $slabs[ $m[ 1 ] ][ $m[ 2 ] ] = $m[ 3 ];
            }
        }

        return $slabs;
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
