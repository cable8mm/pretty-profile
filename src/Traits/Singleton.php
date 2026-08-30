<?php

namespace Cable8mm\PrettyProfile\Traits;

trait Singleton
{
    protected static $instances = [];

    /**
     * Protected class constructor to prevent direct object creation.
     */
    protected function __construct() {}

    /**
     * Prevent object cloning
     */
    final protected function __clone(): void {}

    /**
     * Singletons should not be restorable from strings.
     */
    public function __wakeup(): void
    {
        throw new \Exception('Cannot unserialize a singleton.');
    }

    /**
     * To return new or existing Singleton instance of the class from which it is called.
     * As it sets to final it can't be overridden.
     *
     * @return object Singleton instance of the class.
     */
    final public static function getInstance(): static
    {
        /**
         * Returns name of the class the static method is called in.
         */
        $calledClass = get_called_class();

        if (! isset(static::$instances[$calledClass])) {

            static::$instances[$calledClass] = new $calledClass;

        }

        return static::$instances[$calledClass];

    }
}
