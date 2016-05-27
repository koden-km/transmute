<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

/**
 * An example state logic class.
 */
final class GameFinishedLogic implements StateLogic
{
    /**
     * The update logic to be done during an update.
     *
     * @param Transitioner $transitioner The state transitioner.
     * @param mixed        $context      The object having state updated.
     */
    public function update(Transitioner $transitioner, $context)
    {
        // To some updating FINISHED state logic work ...

        $context->setValue(
            sprintf(
                'update: current=%s',
                $context->state()->key()
            )
        );
    }

    /**
     * Any logic to be performed when entering this state.
     *
     * @param Transitioner $transitioner  The state transitioner.
     * @param mixed        $previousState The previous state transitioned from.
     * @param mixed        $context       The object being state transitioned.
     */
    public function enter(Transitioner $transitioner, $previousState, $context)
    {
        // To some entering FINISHED state logic work ...

        $context->setState(GameStatus::FINISHED());

        $context->setValue(
            sprintf(
                'enter: current=%s, previous=%s',
                $context->state()->key(),
                $previousState->key()
            )
        );
    }

    /**
     * Any logic to be performed when leaving this state.
     *
     * @param Transitioner $transitioner The state transitioner.
     * @param mixed        $nextState    The next state transitioning to.
     * @param mixed        $context      The object being state transitioned.
     */
    public function leave(Transitioner $transitioner, $nextState, $context)
    {
        // To some leaving FINISHED state logic work ...

        $context->setValue(
            sprintf(
                'leave: current=%s, next=%s',
                $context->state()->key(),
                $nextState->key()
            )
        );
    }
}
