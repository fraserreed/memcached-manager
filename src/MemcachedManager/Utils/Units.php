<?php

namespace MemcachedManager\Utils;


class Units
{
    /**
     * Get the readable size for a number of bytes:
     *
     * x.yy Gb
     * x Mb
     * x Kb
     * x bytes
     *
     * @param $bytes
     *
     * @return string
     */
    public static function readableSize( $bytes )
    {
        if( $bytes >= 1 << 30 )
            return number_format( $bytes / ( 1 << 30 ), 2 ) . " Gb";
        if( $bytes >= 1 << 20 )
            return number_format( $bytes / ( 1 << 20 ), 0 ) . " Mb";
        if( $bytes >= 1 << 10 )
            return number_format( $bytes / ( 1 << 10 ), 0 ) . " Kb";

        return number_format( $bytes ) . " bytes";
    }
}