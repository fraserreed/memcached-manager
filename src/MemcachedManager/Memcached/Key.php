<?php

namespace MemcachedManager\Memcached;


use MemcachedManager\Utils\Hash;

class Key
{
    const TYPE_INT = 'int';
    const TYPE_STRING = 'string';
    const TYPE_ARRAY = 'array';

    /**
     * @var string
     */
    protected $hash;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var int
     */
    protected $cas;

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isInteger()
    {
        return $this->getType() == self::TYPE_INT;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey( $key )
    {
        $this->key  = $key;
        $this->hash = Hash::encode( $key );
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue( $value )
    {
        $this->value = $value;

        if( is_int( $this->value ) || is_numeric( $this->value ) )
            $this->type = self::TYPE_INT;
        else if( is_array( $this->value ) )
            $this->type = self::TYPE_ARRAY;
        else
            $this->type = self::TYPE_STRING;
    }

    /**
     * @return int
     */
    public function getCas()
    {
        return $this->cas;
    }

    /**
     * @param int $cas
     */
    public function setCas( $cas )
    {
        $this->cas = $cas;
    }
}