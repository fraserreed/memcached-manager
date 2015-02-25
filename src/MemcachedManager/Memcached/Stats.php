<?php

namespace MemcachedManager\Memcached;


class Stats
{
    /**
     * @var int
     */
    protected $activeNodes = 0;

    /**
     * Single node only
     *
     * @var int
     */
    protected $pid;

    /**
     * Single node only
     *
     * @var int
     */
    protected $uptime;

    /**
     * Single node only
     *
     * @var int
     */
    protected $threads;

    /**
     * Single node only
     *
     * @var int
     */
    protected $pointerSize;

    /**
     * Single node only
     *
     * @var string
     */
    protected $version;

    /**
     * @var int
     */
    protected $currItems = 0;

    /**
     * @var int
     */
    protected $totalItems = 0;

    /**
     * @var int
     */
    protected $limitMaxbytes = 0;

    /**
     * @var int
     */
    protected $currConnections = 0;

    /**
     * @var int
     */
    protected $totalConnections = 0;

    /**
     * @var int
     */
    protected $connectionStructures = 0;

    /**
     * @var int
     */
    protected $bytes = 0;

    /**
     * @var int
     */
    protected $cmdGet = 0;

    /**
     * @var int
     */
    protected $cmdSet = 0;

    /**
     * @var int
     */
    protected $getHits = 0;

    /**
     * @var int
     */
    protected $getMisses = 0;

    /**
     * @var int
     */
    protected $evictions = 0;

    /**
     * @var int
     */
    protected $bytesRead = 0;

    /**
     * @var int
     */
    protected $bytesWritten = 0;

    public function incrementActiveNodes()
    {
        $this->activeNodes++;
    }

    public function getActiveNodes()
    {
        return $this->activeNodes;
    }

    /**
     * @param int $pid
     */
    public function setPid( $pid )
    {
        $this->pid = $pid;
    }

    /**
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param int $uptime
     */
    public function setUptime( $uptime )
    {
        $this->uptime = (int) $uptime;
    }

    /**
     * @return int
     */
    public function getUptime()
    {
        if( $this->uptime == 0 )
            return null;

        $secondsInAMinute = 60;
        $secondsInAnHour  = 60 * $secondsInAMinute;
        $secondsInADay    = 24 * $secondsInAnHour;

        // extract days
        $days = floor( $this->uptime / $secondsInADay );

        // extract hours
        $hourSeconds = $this->uptime % $secondsInADay;
        $hours       = floor( $hourSeconds / $secondsInAnHour );

        // extract minutes
        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $minutes       = floor( $minuteSeconds / $secondsInAMinute );

        // extract the remaining seconds
        $seconds = ceil( $minuteSeconds % 60 );

        $time = array();

        if( $days > 0 )
            $time[ ] = $days . ( ( $days > 1 ) ? ' days' : ' day' );

        if( ( $hours > 0 ) )
            $time[ ] = $hours . ( ( $hours > 1 ) ? ' hours' : ' hour' );
        else if( count( $time ) > 0 )
            $time[ ] = '0 hours';

        if( ( $minutes > 0 ) )
            $time[ ] = $minutes . ( ( $minutes > 1 ) ? ' minutes' : ' minute' );
        else if( count( $time ) > 0 )
            $time[ ] = '0 minutes';

        if( ( $seconds > 0 ) )
            $time[ ] = $seconds . ( ( $seconds > 1 ) ? ' seconds' : ' second' );
        else if( count( $time ) > 0 )
            $time[ ] = '0 seconds';

        return implode( ' ', $time );
    }

    /**
     * @param int $threads
     */
    public function setThreads( $threads )
    {
        $this->threads = (int) $threads;
    }

    /**
     * @return int
     */
    public function getThreads()
    {
        return $this->threads;
    }

    /**
     * @param int $pointerSize
     */
    public function setPointerSize( $pointerSize )
    {
        $this->pointerSize = (int) $pointerSize;
    }

    /**
     * @return int
     */
    public function getPointerSize()
    {
        return $this->pointerSize;
    }

    /**
     * @param string $version
     */
    public function setVersion( $version )
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int $currItems
     */
    public function setCurrItems( $currItems )
    {
        $this->currItems = (int) $currItems;
    }

    /**
     * @return int
     */
    public function getCurrItems()
    {
        return $this->currItems;
    }

    /**
     * @param int $totalItems
     */
    public function setTotalItems( $totalItems )
    {
        $this->totalItems = (int) $totalItems;
    }

    /**
     * @return int
     */
    public function getTotalItems()
    {
        return $this->totalItems;
    }

    /**
     * @param int $limitMaxbytes
     */
    public function setLimitMaxbytes( $limitMaxbytes )
    {
        $this->limitMaxbytes = (int) $limitMaxbytes;
    }

    /**
     * @return int
     */
    public function getLimitMaxbytes()
    {
        return $this->limitMaxbytes;
    }

    /**
     * @return int
     */
    public function getLimitMaxsize()
    {
        $size = $this->getLimitMaxbytes();

        if( $size >= 1 << 30 )
            return number_format( $size / ( 1 << 30 ), 2 ) . " Gb";
        if( $size >= 1 << 20 )
            return number_format( $size / ( 1 << 20 ), 0 ) . " Mb";
        if( $size >= 1 << 10 )
            return number_format( $size / ( 1 << 10 ), 0 ) . " Kb";

        return number_format( $size ) . " bytes";
    }

    /**
     * @param int $currConnections
     */
    public function setCurrConnections( $currConnections )
    {
        $this->currConnections = (int) $currConnections;
    }

    /**
     * @return int
     */
    public function getCurrConnections()
    {
        return $this->currConnections;
    }

    /**
     * @param int $totalConnections
     */
    public function setTotalConnections( $totalConnections )
    {
        $this->totalConnections = (int) $totalConnections;
    }

    /**
     * @return int
     */
    public function getTotalConnections()
    {
        return $this->totalConnections;
    }

    /**
     * @param int $connectionStructures
     */
    public function setConnectionStructures( $connectionStructures )
    {
        $this->connectionStructures = (int) $connectionStructures;
    }

    /**
     * @return int
     */
    public function getConnectionStructures()
    {
        return $this->connectionStructures;
    }

    /**
     * @param int $bytes
     */
    public function setBytes( $bytes )
    {
        $this->bytes = (int) $bytes;
    }

    /**
     * @return int
     */
    public function getBytes()
    {
        return $this->bytes;
    }

    /**
     * @param int $cmdGet
     */
    public function setCmdGet( $cmdGet )
    {
        $this->cmdGet = (int) $cmdGet;
    }

    /**
     * @return int
     */
    public function getCmdGet()
    {
        return $this->cmdGet;
    }

    /**
     * @param int $cmdSet
     */
    public function setCmdSet( $cmdSet )
    {
        $this->cmdSet = (int) $cmdSet;
    }

    /**
     * @return int
     */
    public function getCmdSet()
    {
        return $this->cmdSet;
    }

    /**
     * @param int $getHits
     */
    public function setGetHits( $getHits )
    {
        $this->getHits = (int) $getHits;
    }

    /**
     * @return int
     */
    public function getGetHits()
    {
        return $this->getHits;
    }

    /**
     * @param int $getMisses
     */
    public function setGetMisses( $getMisses )
    {
        $this->getMisses = (int) $getMisses;
    }

    /**
     * @return int
     */
    public function getGetMisses()
    {
        return $this->getMisses;
    }

    /**
     * @param int $evictions
     */
    public function setEvictions( $evictions )
    {
        $this->evictions = (int) $evictions;
    }

    /**
     * @return int
     */
    public function getEvictions()
    {
        return $this->evictions;
    }

    /**
     * @param int $bytesRead
     */
    public function setBytesRead( $bytesRead )
    {
        $this->bytesRead = (int) $bytesRead;
    }

    /**
     * @return int
     */
    public function getBytesRead()
    {
        return $this->bytesRead;
    }

    /**
     * @param int $bytesWritten
     */
    public function setBytesWritten( $bytesWritten )
    {
        $this->bytesWritten = (int) $bytesWritten;
    }

    /**
     * @return int
     */
    public function getBytesWritten()
    {
        return $this->bytesWritten;
    }
}