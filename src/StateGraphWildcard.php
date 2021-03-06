<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

/**
 * An object to use as a wildcard for object key based transition maps.
 */
final class StateGraphWildcard
{
    /**
     * Get the singleton instance.
     *
     * @return StateGraphWildcard
     */
    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Private constructor.
     *
     * Use the instance() factory method.
     */
    private function __construct()
    {
    }

    /**
     * @var self Singleton.
     */
    private static $instance;
}
