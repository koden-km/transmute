<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

final class StateGraph
{
    /**
     * @param TransitionMap $graph
     */
    public function __construct(TransitionMap $graph)
    {
        $this->graph = $graph;
    }

    /**
     * @param mixed $currentState
     *
     * @return bool
     */
    public function contains($currentState): bool
    {
        return $this->graph->contains($currentState);
    }

    /**
     * @param mixed $currentState
     *
     * @return bool
     */
    public function isInitialState($currentState): bool
    {
        assert(isset($this->graph[$currentState]));

        return $currentState === $this->graph->wildcard();
    }

    /**
     * @param mixed $currentState
     *
     * @return bool
     */
    public function isTerminalState($currentState): bool
    {
        assert(isset($this->graph[$currentState]));

        // If any transition is not to the same state as current then its not terminal.
        $transitions = $this->graph[$currentState];
        foreach ($transitions as $name => $nextState) {
            if ($currentState !== $nextState) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed  $currentState   The current state.
     * @param string $transitionName The name of the available transition.
     *
     * @return mixed The state after transition.
     */
    public function findStateByTransition($currentState, string $transitionName)
    {
        assert(isset($this->graph[$currentState][$transitionName]));

        return $this->graph[$currentState][$transitionName];
    }

    /**
     * @var TransitionMap The state graph structure.
     */
    private $graph;
}
