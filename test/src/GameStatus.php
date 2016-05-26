<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * An example enumeration and state graph.
 */
class GameStatus extends AbstractEnumeration
{
    const CREATED     = 'created';
    const PENDING     = 'pending';
    const REGISTERING = 'registering';
    const DELAYED     = 'delayed';
    const LIVE        = 'live';
    const FINISHING   = 'finishing';
    const FINISHED    = 'finished';
    const CANCELLING  = 'cancelling';
    const CANCELLED   = 'cancelled';

    public static function graph(): StateGraph
    {
        return StateGraphBuilder::create(true)
            ->cancel(self::CANCELLING())

        [self::PENDING()]
            ->open(self::REGISTERING())
            ->delay(self::DELAYED())

        [self::REGISTERING()]
            ->start(self::LIVE())

        [self::LIVE()]
            ->finish(self::FINISHING())

        [self::FINISHING()]
            ->finalize(self::FINISHED())

        [self::FINISHED()]
            ->finish(self::FINISHED())

        [self::CANCELLING()]
            ->finalize(self::CANCELLED())

        [self::CANCELLED()]
            ->cancel(self::CANCELLED())

        ->build();
    }
}
