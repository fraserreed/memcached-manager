<?php

namespace MemcachedManager\Memcached;


class Node
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var bool
     */
    protected $alive;

    public function __construct( $host, $port, $name = '' )
    {
        $this->setHost( $host );
        $this->setPort( $port );

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
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost( $host )
    {
        $this->host = $host;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort( $port )
    {
        $this->port = $port;
    }

    /**
     * @return boolean
     */
    public function getAlive()
    {
        return (bool) $this->alive;
    }

    /**
     * @param boolean $alive
     */
    public function setAlive( $alive )
    {
        $this->alive = $alive;
    }
}