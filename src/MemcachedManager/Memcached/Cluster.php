<?php

namespace MemcachedManager\Memcached;


class Cluster
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Stats
     */
    protected $stats;

    /**
     * @var array
     */
    protected $nodes = array();

    /**
     * @param string $name
     */
    public function __construct( $name = '' )
    {
        if( $name )
            $this->setName( $name );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName( $name )
    {
        $this->name = $name;
    }

    /**
     * @param Node $node
     */
    public function addNode( Node $node )
    {
        $this->nodes[ ] = $node;
    }

    /**
     * @return array
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param array $nodes
     */
    public function setNodes( array $nodes )
    {
        $this->nodes = $nodes;
    }

    /**
     * @return Stats
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @param Stats $stats
     */
    public function setStats( Stats $stats )
    {
        $this->stats = $stats;
    }
}