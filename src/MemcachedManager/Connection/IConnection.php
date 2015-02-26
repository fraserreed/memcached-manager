<?php

namespace MemcachedManager\Connection;


interface IConnection
{
    public function __construct( $host, $port );

    public function execute( $command );
}