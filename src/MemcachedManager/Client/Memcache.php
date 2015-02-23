<?php

namespace MemcachedManager\Client;


use Memcache as PhpMemcache;

class Memcache extends AbstractClient
{
    public function __construct( $servers )
    {
        throw new \Exception( 'Memcache adapter not supported.' );
    }
}