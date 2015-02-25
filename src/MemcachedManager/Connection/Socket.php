<?php

namespace MemcachedManager\Connection;


use MemcachedManager\Exceptions\SocketConnectionException;

class Socket
{
    const SOCKET_TIMEOUT = 3;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

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
     * @return resource
     * @throws SocketConnectionException
     */
    private function connect()
    {
        if( !$connection = fsockopen( $this->host, $this->port, $errNo, $errStr, self::SOCKET_TIMEOUT ) )
            throw new SocketConnectionException();

        if( !is_resource( $connection ) || $errNo != 0 )
            throw new SocketConnectionException();

        return $connection;
    }

    /**
     * @param $command
     *
     * @return string
     * @throws SocketConnectionException
     */
    public function execute( $command )
    {
        $socket = $this->connect();

        // Set timout to 1 second
        if( !stream_set_timeout( $socket, 3 ) )
            die( "Could not set timeout" );

        $response = array();

        $out = "$command\r\n";
        $out .= "quit\r\n";

        fwrite( $socket, $out );

        while( !feof( $socket ) )
            $response[ ] = trim( fgets( $socket, 128 ) );

        fclose( $socket );

        return $response;
    }
}