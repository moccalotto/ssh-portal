<?php

namespace Moccalotto\SshPortal;

/**
 * Abstract class for creating singletons
 * with easy static accessors.
 */
abstract class Singletonian
{
    public static function instance()
    {
        static $instance = null;

        if (!$instance) {
            $instance = new static();
        }

        return $instance;
    }

    protected static function doMethodName($name)
    {
        return sprintf('do%s', ucfirst($name));
    }

    public static function __callStatic($name, $args)
    {
        $callable = [
            static::instance(),
            static::doMethodName($name),
        ];

        return call_user_func_array($callable, $args);
    }

    public function __call($name, $args)
    {
        $callable = [
            $this,
            static::doMethodName($name),
        ];

        return call_user_func_array($callable, $args);
    }
}
