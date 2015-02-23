<?php

namespace MemcachedManager;


use MemcachedManager\Memcached\Cluster;
use MemcachedManager\Memcached\Key;
use MemcachedManager\Memcached\KeyStore;
use MemcachedManager\Memcached\Node;
use MemcachedManager\Client\IClient;
use MemcachedManager\Client\Memcached;
use MemcachedManager\Client\Memcache;
use MemcachedManager\Exceptions\ClientNotFoundException;
use MemcachedManager\Utils\Hash;

class Client
{
    /**
     * @var IClient
     */
    protected $client;

    /**
     * @var array
     */
    protected $clusters = array();

    public function __construct( $servers )
    {
        //determine the storage type
        if( class_exists( 'Memcached' ) )
        {
            $this->client = new Memcached( $servers );

//            if( !method_exists( $this->client, 'getAllKeys' ) )
//            {
//                $this->client = null;
//            }
        }

        if( !$this->client && class_exists( 'Memcache' ) )
        {
            $this->client = new Memcache( $servers );
        }
    }

    /**
     * @return IClient
     * @throws ClientNotFoundException
     */
    private function getClient()
    {
        if( !$this->client )
            throw new ClientNotFoundException( 'Client could not be initialized' );

        return $this->client;
    }

    /**
     * @param Node $node
     *
     * @return mixed
     * @throws ClientNotFoundException
     */
    private function testConnection( Node $node )
    {
        return $this->getClient()->test( $node->getHost(), $node->getPort() );
    }

    /**
     * Get a list of clusters
     *
     * @return array
     */
    public function getClusters()
    {
        $client = $this->getClient();

        if( empty( $this->clusters ) )
        {
            foreach( $client->getServers() as $server )
            {
                //if this is the first node in the cluster, initialize the cluster
                if( !isset( $this->clusters[ $server[ 'cluster' ] ] ) )
                    $this->clusters[ $server[ 'cluster' ] ] = new Cluster( $server[ 'cluster' ] );

                $node = new Node( $server[ 'host' ], $server[ 'port' ], $server[ 'name' ] );
                $node->setAlive( $this->testConnection( $node ) );

                //add the node to the cluster
                $this->clusters[ $server[ 'cluster' ] ]->addNode( $node );
            }

            //prime the cluster stats
            /** @var $cluster \MemcachedManager\Memcached\Cluster */
            foreach( $this->clusters as $cluster )
                $cluster->setStats( $this->getClient()->getStats() );
        }

        return $this->clusters;
    }

    /**
     * Get a cluster by name
     *
     * @param $clusterName
     *
     * @return Cluster
     */
    public function getCluster( $clusterName )
    {
        $clusters = $this->getClusters();

        return isset( $clusters[ $clusterName ] ) ? $clusters[ $clusterName ] : null;
    }

    /**
     * Get a node in a cluster by name
     *
     * @param $clusterName
     * @param $nodeName
     *
     * @return bool|Node
     */
    public function getNode( $clusterName, $nodeName )
    {
        $clusters = $this->getClusters();

        if( isset( $clusters[ $clusterName ] ) )
        {
            /** @var $node \MemcachedManager\Memcached\Node */
            foreach( $clusters[ $clusterName ]->getNodes() as $node )
            {
                if( $node->getName() == $nodeName )
                    return $node;
            }
        }

        return false;
    }

    /**
     * Proxy function for adding a key to the data source
     *
     * @param $key
     * @param $value
     *
     * @return mixed
     * @throws ClientNotFoundException
     */
    public function addKey( $key, $value )
    {
        $this->getClient()->addKey( $key, $value );
    }

    /**
     * Proxy function for editing a key in the data source
     *
     * @param $key
     * @param $value
     *
     * @throws ClientNotFoundException
     */
    public function editKey( $key, $value )
    {
        $this->getClient()->editKey( Hash::decode( $key ), $value );
    }

    /**
     * Proxy function for getting a key from the data source
     *
     * @param $key
     *
     * @return \MemcachedManager\Memcached\Key|null
     * @throws ClientNotFoundException
     */
    public function getKey( $key )
    {
        $decodedKey = Hash::decode( $key );

        if( $value = $this->getClient()->getKey( $decodedKey ) )
        {
            $object = new Key();
            $object->setKey( $decodedKey );
            $object->setValue( $value );

            return $object;
        }

        return null;
    }

    /**
     * Proxy function for purging a key from the data source
     *
     * @param $key
     *
     * @return mixed
     * @throws ClientNotFoundException
     */
    public function deleteKey( $key )
    {
        $this->getClient()->deleteKey( Hash::decode( $key ) );
    }

    /**
     * Proxy function for incrementing a key's value in the data source
     *
     * @param $key
     *
     * @throws ClientNotFoundException
     */
    public function incrementKey( $key )
    {
        $this->getClient()->incrementKey( Hash::decode( $key ) );
    }

    /**
     * Proxy function for decrementing a key's value in the data source
     *
     * @param $key
     *
     * @throws ClientNotFoundException
     */
    public function decrementKey( $key )
    {
        $this->getClient()->decrementKey( Hash::decode( $key ) );
    }

    /**
     * Proxy function for retrieving all key value pairs in the data source
     *
     * @param        $clusterName
     * @param string $nodeName
     *
     * @return KeyStore
     * @throws ClientNotFoundException
     */
    public function getAllKeys( $clusterName, $nodeName = '' )
    {
        return new KeyStore( $this->getClient()->getKeys() );
    }
}