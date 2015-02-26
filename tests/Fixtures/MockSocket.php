<?php

namespace MemcachedManager\Tests\Fixtures;


use MemcachedManager\Connection\IConnection;

class MockSocket implements IConnection
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var array
     */
    protected $responses = array();

    /**
     * @param $host
     * @param $port
     */
    public function __construct( $host, $port )
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @param $command
     * @param $response
     */
    public function setResponse( $command, $response )
    {
        $this->responses[ $command ] = $response;
    }

    /**
     * @param $command
     *
     * @return mixed
     * @throws \Exception
     */
    public function execute( $command )
    {
        if( isset( $this->responses[ $command ] ) )
            return $this->responses[ $command ];

        throw new \Exception( 'Execute should not be called with the command ' . $command );
    }
}