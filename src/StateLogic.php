<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

interface StateLogic
{
    /**
     * The update logic to be done during an update. Updates may be tick based
     * or event based.
     *
     * @param StateExecutor $executor The state machine executor.
     * @param mixed         $context  The object having state updated.
     */
    public function update(StateExecutor $executor, $context);

    /**
     * Any logic to be performed when entering this state.
     *
     * @param StateExecutor $executor      The state machine executor.
     * @param mixed         $previousState The previous state transitioned from.
     * @param mixed         $context       The object being state transitioned.
     */
    public function enter(StateExecutor $executor, $previousState, $context);

    /**
     * Any logic to be performed when leaving this state.
     *
     * @param StateExecutor $executor  The state machine executor.
     * @param mixed         $nextState The next state transitioning to.
     * @param mixed         $context   The object being state transitioned.
     */
    public function leave(StateExecutor $executor, $nextState, $game);
}
