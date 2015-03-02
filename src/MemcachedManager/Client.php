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

    /**
     * @param      $clusters
     * @param null $clientClass
     *
     * @throws ClientNotFoundException
     */
    public function __construct( $clusters, $clientClass = null )
    {
        $this->clusters = $clusters;

        if( $clientClass )
        {
            $this->client = new $clientClass();
        }
        else
        {
            //determine the storage type
            if( class_exists( 'Memcached' ) )
            {
                $this->client = new Memcached();
            }

            if( !$this->client && class_exists( 'Memcache' ) )
            {
                $this->client = new Memcache();
            }
        }

        if( !( $this->client instanceof IClient ) )
            throw new ClientNotFoundException( 'Client could not be initialized' );
    }

    /**
     * @return IClient
     */
    private function getClient()
    {
        return $this->client;
    }

    /**
     * @param IClient $client
     * @param array   $nodes
     *
     * @return IClient
     */
    private function processNodes( IClient $client, array $nodes )
    {
        $client->clearServers();

        /** @var $node \MemcachedManager\Memcached\Node */
        foreach( $nodes as $node )
            $client->addServer( $node->getHost(), $node->getPort() );

        return $client;
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
        $populatedClusters = array();

        if( !empty( $this->clusters ) )
        {
            foreach( $this->clusters as $clusterName => $servers )
            {
                //if this is the first node in the cluster, initialize the cluster
                if( !isset( $populatedClusters[ $clusterName ] ) )
                    $populatedClusters[ $clusterName ] = new Cluster( $clusterName );

                foreach( $servers as $server )
                {
                    $node = new Node( $server[ 'host' ], $server[ 'port' ], isset( $server[ 'name' ] ) ? $server[ 'name' ] : $server[ 'host' ] );
                    $node->setAlive( $this->testConnection( $node ) );

                    //add the node to the cluster
                    $populatedClusters[ $clusterName ]->addNode( $node );
                }
            }
        }

        return $populatedClusters;
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

        if( $cluster = isset( $clusters[ $clusterName ] ) ? $clusters[ $clusterName ] : null )
        {
            $client = $this->processNodes( $this->getClient(), $cluster->getNodes() );

            if( $stats = $client->getStats() )
                $cluster->setStats( $stats );
        }

        return $cluster;
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
     * @param $clusterName
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function addKey( $clusterName, $key, $value )
    {
        $cluster = $this->getCluster( $clusterName );
        $client  = $this->processNodes( $this->getClient(), $cluster->getNodes() );

        $client->addKey( $key, $value );
    }

    /**
     * Proxy function for editing a key in the data source
     *
     * @param $clusterName
     * @param $key
     * @param $value
     *
     */
    public function editKey( $clusterName, $key, $value )
    {
        $cluster = $this->getCluster( $clusterName );
        $client  = $this->processNodes( $this->getClient(), $cluster->getNodes() );

        $client->editKey( Hash::decode( $key ), $value );
    }

    /**
     * Proxy function for getting a key from the data source
     *
     * @param $clusterName
     * @param $key
     *
     * @return Key|null
     */
    public function getKey( $clusterName, $key )
    {
        $decodedKey = Hash::decode( $key );

        $cluster = $this->getCluster( $clusterName );
        $client  = $this->processNodes( $this->getClient(), $cluster->getNodes() );

        if( $value = $client->getKey( $decodedKey ) )
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
     * @param $clusterName
     * @param $key
     *
     * @return mixed
     */
    public function deleteKey( $clusterName, $key )
    {
        $cluster = $this->getCluster( $clusterName );
        $client  = $this->processNodes( $this->getClient(), $cluster->getNodes() );

        $client->deleteKey( Hash::decode( $key ) );
    }

    /**
     * Proxy function for incrementing a key's value in the data source
     *
     * @param $clusterName
     * @param $key
     */
    public function incrementKey( $clusterName, $key )
    {
        $cluster = $this->getCluster( $clusterName );
        $client  = $this->processNodes( $this->getClient(), $cluster->getNodes() );

        $client->incrementKey( Hash::decode( $key ) );
    }

    /**
     * Proxy function for decrementing a key's value in the data source
     *
     * @param $clusterName
     * @param $key
     */
    public function decrementKey( $clusterName, $key )
    {
        $cluster = $this->getCluster( $clusterName );
        $client  = $this->processNodes( $this->getClient(), $cluster->getNodes() );

        $client->decrementKey( Hash::decode( $key ) );
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
        $cluster = $this->getCluster( $clusterName );

        $nodes = $cluster->getNodes();

        if( $nodeName )
        {
            /** @var $node \MemcachedManager\Memcached\Node */
            foreach( $nodes as $node )
            {
                if( $node->getName() == $nodeName )
                {
                    $nodes = array( $node );
                    break;
                }
            }
        }

        return new KeyStore( $this->getClient()->getKeys( $nodes ) );
    }
}