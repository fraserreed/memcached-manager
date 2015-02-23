<?php

namespace MemcachedManager;


/**
 * Configuration class for getting and setting configuration
 */
class Config
{
    static $confArray;

    /**
     * Read all configuration values
     *
     * @return mixed
     */
    public static function readAll()
    {
        return self::$confArray;
    }

    /**
     * Read a value from the static configuration array, if set
     *
     * @param $name
     *
     * @return null
     */
    public static function read( $name )
    {
        if( isset( self::$confArray[ $name ] ) )
            return self::$confArray[ $name ];

        return null;
    }

    /**
     * Set a number of values in the static configuration array
     *
     * @param $array
     */
    public static function writeBulk( $array )
    {
        if( is_array( $array ) )
            foreach( $array as $name => $value )
                self::write( $name, $value );
    }

    /**
     * Set a value in a static configuration array
     *
     * @param $name
     * @param $value
     */
    public static function write( $name, $value )
    {
        self::$confArray[ $name ] = $value;
    }
}