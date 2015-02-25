<?php

namespace MemcachedManager\Client;


use MemcachedManager\Memcached\Stats;

abstract class AbstractClient implements IClient
{
    /**
     * @var array
     */
    private $servers;

    /**
     * @var mixed
     */
    protected $clientClass;

    public function __construct( array $servers )
    {
        $this->servers = $servers;
    }

    /**
     * @return IClient
     */
    abstract public function getClientClass();

    /**
     * Expose public setter for unit testing
     *
     * @param $clientClass
     */
    public function setClientClass( $clientClass )
    {
        $this->clientClass = $clientClass;
    }

    public function getClient()
    {
        $client = $this->getClientClass();

        if( !empty( $this->servers ) )
        {
            /** @var $server \MemcachedManager\Memcached\Node */
            foreach( $this->servers as $server )
            {
                //$client->addServer( $server->getHost(), $server->getPort() );
                $client->addServer( $server[ 'host' ], $server[ 'port' ] );
            }
        }

        return $client;
    }

    /**
     * @return Direct
     */
    public function getDirectClient()
    {
        return new Direct( $this->servers );
    }

    /**
     * @return array
     */
    public function getServers()
    {
        return $this->servers;
    }

    /**
     * @return Stats
     */
    public function getStats()
    {
        //if no stats available for the nodes, return
        if( $clusterStats = $this->getClient()->getStats() )
        {
            $stats = new Stats();

            $nodeCount = count( $clusterStats );

            if( $nodeCount > 0 )
            {
                foreach( $clusterStats as $node => $nodeStatistics )
                {
                    if( $nodeStatistics[ 'pid' ] > 0 )
                        $stats->incrementActiveNodes();

                    if( $nodeCount == 1 )
                    {
                        $stats->setPid( $nodeStatistics[ 'pid' ] );
                        $stats->setUptime( $nodeStatistics[ 'uptime' ] );
                        $stats->setThreads( $nodeStatistics[ 'threads' ] );
                        $stats->setPointerSize( $nodeStatistics[ 'pointer_size' ] );
                        $stats->setVersion( $nodeStatistics[ 'version' ] );
                    }

                    $stats->setCurrItems( $stats->getCurrItems() + $nodeStatistics[ 'curr_items' ] );
                    $stats->setTotalItems( $stats->getTotalItems() + $nodeStatistics[ 'total_items' ] );
                    $stats->setLimitMaxbytes( $stats->getLimitMaxbytes() + $nodeStatistics[ 'limit_maxbytes' ] );
                    $stats->setCurrConnections( $stats->getCurrConnections() + $nodeStatistics[ 'curr_connections' ] );
                    $stats->setTotalConnections( $stats->getTotalConnections() + $nodeStatistics[ 'total_connections' ] );
                    $stats->setConnectionStructures( $stats->getConnectionStructures() + $nodeStatistics[ 'connection_structures' ] );
                    $stats->setBytes( $stats->getBytes() + $nodeStatistics[ 'bytes' ] );
                    $stats->setCmdGet( $stats->getCmdGet() + $nodeStatistics[ 'cmd_get' ] );
                    $stats->setCmdSet( $stats->getCmdSet() + $nodeStatistics[ 'cmd_set' ] );
                    $stats->setGetHits( $stats->getGetHits() + $nodeStatistics[ 'get_hits' ] );
                    $stats->setGetMisses( $stats->getGetMisses() + $nodeStatistics[ 'get_misses' ] );
                    $stats->setEvictions( $stats->getEvictions() + $nodeStatistics[ 'evictions' ] );
                    $stats->setBytesRead( $stats->getBytesRead() + $nodeStatistics[ 'bytes_read' ] );
                    $stats->setBytesWritten( $stats->getBytesWritten() + $nodeStatistics[ 'bytes_written' ] );
                }
            }

            return $stats;
        }

        return null;
    }
}