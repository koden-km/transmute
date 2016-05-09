<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

final class StateGraphWildcard
{
    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    /**
     * @var self Singletone.
     */
    private static $instance;
}
