<?php

namespace MemcachedManager\Utils;


class Time
{
    const SECONDS_IN_DAY = 86400;
    const SECONDS_IN_HOUR = 3600;
    const SECONDS_IN_MINUTE = 60;

    /**
     * Get the readable time for a number of seconds in the following format:
     *
     * 1 days 2 hours 3 minutes 4 seconds
     *
     * @param $seconds
     *
     * @return string
     */
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
        // extract the remaining days
        return self::formatResult(
            $seconds,
            floor( $seconds / self::SECONDS_IN_DAY ),
            'day',
            self::SECONDS_IN_DAY
        );
    }

    /**
     * @param $seconds
     *
     * @return null|string
     */
    private static function getReadableHours( $seconds )
    {
        // extract the remaining hours
        return self::formatResult(
            $seconds,
            floor( ( $seconds % self::SECONDS_IN_DAY ) / self::SECONDS_IN_HOUR ),
            'hour',
            self::SECONDS_IN_HOUR
        );
    }

    /**
     * @param $seconds
     *
     * @return null|string
     */
    private static function getReadableMinutes( $seconds )
    {
        // extract the remaining minutes
        return self::formatResult(
            $seconds,
            floor( ( ( $seconds % self::SECONDS_IN_DAY ) % self::SECONDS_IN_HOUR ) / self::SECONDS_IN_MINUTE ),
            'minute',
            self::SECONDS_IN_MINUTE
        );
    }

    /**
     * @param $seconds
     *
     * @return null|string
     */
    private static function getReadableSeconds( $seconds )
    {
        // extract the remaining seconds
        return self::formatResult(
            $seconds,
            ( ( $seconds % self::SECONDS_IN_DAY ) % self::SECONDS_IN_HOUR ) % self::SECONDS_IN_MINUTE,
            'second',
            self::SECONDS_IN_MINUTE
        );
    }

    /**
     * @param $seconds
     * @param $value
     * @param $word
     * @param $threshold
     *
     * @return null|string
     */
    private static function formatResult( $seconds, $value, $word, $threshold )
    {
        if( ( $value > 0 ) )
            return $value . ( ( $value > 1 ) ? ' ' . $word . 's' : ' ' . $word );
        else if( $seconds > $threshold )
            return '0 ' . $word . 's';

        return null;
    }
}