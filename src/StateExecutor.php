<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

class StateExecutor
{
    /**
     * @param StateGraph $graph The graph to use.
     */
    public function __construct(StateGraph $graph) {
        $this->graph = $graph;
        $this->previousState = null;
    }

    public function update(StateLogic $currentState, $object) {
        if ($currentState !== $previousState) {
            // TODO: check if can transition first?
            if (null !== $previousState) {
                $previousState->leave($this, $currentState, $object);
            }
            $currentState->enter($this, $previousState, $object);
            $previousState = $currentState;
        }

        $currentState->update($this, $object);
    }

    /**
     * @var StateGraph The state graph to use.
     */
    private $graph;

    /**
     * @var StateLogic|null The previous state.
     */
    private $previousState;
}
