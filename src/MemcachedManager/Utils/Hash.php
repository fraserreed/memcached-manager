<?php

namespace MemcachedManager\Utils;


class Hash
{
    /**
     * @return string
     */
    private static function _getSalt()
    {
        return md5( 'SaltyCach!ng' );
    }

    /**
     * @param $value
     *
     * @return string
     */
    public static function encode( $value )
    {
        $base64_string = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, self::_getSalt(), $value, MCRYPT_MODE_CBC, md5( self::_getSalt() ) ) );

        $url_safe_base64 = strtr( $base64_string, "+/", "-_" );

        return $url_safe_base64;
    }

    /**
     * @param $value
     *
     * @return string
     */
    public static function decode( $value )
    {
        $base64_string = strtr( $value, "-_", "+/" );

        return rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, self::_getSalt(), base64_decode( $base64_string ), MCRYPT_MODE_CBC, md5( self::_getSalt() ) ), "\0" );
    }
}