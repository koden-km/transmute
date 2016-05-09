<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

interface StateLogic
{
    /**
     * The update logic to be done during an update.
     *
     * @param Transitioner $transitioner The state transitioner.
     * @param mixed        $context      The object having state updated.
     */
    public function update(Transitioner $transitioner, $context);

    /**
     * Any logic to be performed when entering this state.
     *
     * @param Transitioner $transitioner  The state transitioner.
     * @param mixed        $previousState The previous state transitioned from.
     * @param mixed        $context       The object being state transitioned.
     */
    public function enter(Transitioner $transitioner, $previousState, $context);

    /**
     * Any logic to be performed when leaving this state.
     *
     * @param Transitioner $transitioner The state transitioner.
     * @param mixed        $nextState    The next state transitioning to.
     * @param mixed        $context      The object being state transitioned.
     */
    public function leave(Transitioner $transitioner, $nextState, $context);
}
