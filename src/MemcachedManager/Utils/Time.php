<?php

namespace MemcachedManager\Utils;


class Time
{
    const SECONDS_IN_DAY = 86400;
    const SECONDS_IN_HOUR = 3600;
    const SECONDS_IN_MINUTE = 60;

    public static function readableTime( $seconds )
    {
        return implode(
            ' ',
            array_filter(
                array(
                    self::getReadableDays( $seconds ),
                    self::getReadableHours( $seconds ),
                    self::getReadableMinutes( $seconds ),
                    self::getReadableSeconds( $seconds )
                )
            )
        );
    }

    /**
     * @param $seconds
     *
     * @return null|string
     */
    private static function getReadableDays( $seconds )
    {
        // extract days
        $days = floor( $seconds / self::SECONDS_IN_DAY );

        if( $days > 0 )
            return $days . ( ( $days > 1 ) ? ' days' : ' day' );

        return null;
    }

    /**
     * @param $seconds
     *
     * @return null|string
     */
    private static function getReadableHours( $seconds )
    {
        // extract hours
        $hours = floor( ( $seconds % self::SECONDS_IN_DAY ) / self::SECONDS_IN_HOUR );

        if( ( $hours > 0 ) )
            return $hours . ( ( $hours > 1 ) ? ' hours' : ' hour' );
        else if( $seconds > self::SECONDS_IN_HOUR )
            return '0 hours';

        return null;
    }

    /**
     * @param $seconds
     *
     * @return null|string
     */
    private static function getReadableMinutes( $seconds )
    {
        // extract minutes
        $minutes = floor( ( ( $seconds % self::SECONDS_IN_DAY ) % self::SECONDS_IN_HOUR ) / self::SECONDS_IN_MINUTE );

        if( ( $minutes > 0 ) )
            return $minutes . ( ( $minutes > 1 ) ? ' minutes' : ' minute' );
        else if( $seconds > self::SECONDS_IN_MINUTE )
            return '0 minutes';

        return null;
    }

    /**
     * @param $seconds
     *
     * @return null|string
     */
    private static function getReadableSeconds( $seconds )
    {
        // extract the remaining seconds
        $secondsRemaining = ( ( $seconds % self::SECONDS_IN_DAY ) % self::SECONDS_IN_HOUR ) % self::SECONDS_IN_MINUTE;

        if( ( $secondsRemaining > 0 ) )
            return $secondsRemaining . ( ( $secondsRemaining > 1 ) ? ' seconds' : ' second' );
        else if( $seconds > self::SECONDS_IN_MINUTE )
            return '0 seconds';

        return null;
    }
}