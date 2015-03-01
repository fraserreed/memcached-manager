<?php

namespace MemcachedManager\Memcached;


use MemcachedManager\Utils\Time;
use MemcachedManager\Utils\Units;

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

        return Time::readableTime( $this->uptime );
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
        return Units::readableSize( $this->getLimitMaxbytes() );
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
        return Units::readableSize( $this->bytes );
    }

    /**
     * @return int
     */
    public function getRawBytes()
    {
        return $this->bytes;
    }

    /**
     * @return string
     */
    public function getBytesFree()
    {
        return Units::readableSize( $this->getLimitMaxbytes() - $this->getRawBytes() );
    }

    /**
     * @return float
     */
    public function getBytesFreePercentage()
    {
        return $this->getLimitMaxbytes() ? number_format( 100 * ( 1 - ( $this->getRawBytes() / $this->getLimitMaxbytes() ) ), 2 ) : 0;
    }

    /**
     * @return float
     */
    public function getBytesUsedPercentage()
    {
        return $this->getLimitMaxbytes() ? number_format( 100 * ( $this->getRawBytes() / $this->getLimitMaxbytes() ), 2 ) : 0;
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
        return Units::readableSize( $this->bytesRead );
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
        return Units::readableSize( $this->bytesWritten );
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
     * @return float
     */
    public function getGetHitsPercentage()
    {
        return number_format( ( $this->getGetHits() / ( $this->getGetHits() + $this->getGetMisses() ) ) * 100, 2 );
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
     * @return float
     */
    public function getGetMissesPercentage()
    {
        return number_format( ( $this->getGetMisses() / ( $this->getGetHits() + $this->getGetMisses() ) ) * 100, 2 );
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
}