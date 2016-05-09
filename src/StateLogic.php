<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

interface StateLogic
{
    /**
     * The update logic to be done during an update.
     *
     * @param StateMachine $machine The state machine.
     * @param mixed         $context  The object having state updated.
     */
    public function update(StateMachine $machine, $context);

    /**
     * Any logic to be performed when entering this state.
     *
     * @param StateMachine $machine      The state machine.
     * @param mixed         $previousState The previous state transitioned from.
     * @param mixed         $context       The object being state transitioned.
     */
    public function enter(StateMachine $machine, $previousState, $context);

    /**
     * Any logic to be performed when leaving this state.
     *
     * @param StateMachine $machine  The state machine.
     * @param mixed         $nextState The next state transitioning to.
     * @param mixed         $context   The object being state transitioned.
     */
    public function leave(StateMachine $machine, $nextState, $game);
}
