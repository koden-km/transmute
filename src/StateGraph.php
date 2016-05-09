<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;
use SplObjectStorage;

final class StateGraph
{
    public function __construct(SplObjectStorage $graph)
    {
        $this->graph = $graph;
// $this->currentState = StateGraphWildcard::instance();
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

// public function isInitialState(): bool
// {
//     return $currentState === StateGraphWildcard::instance();
// }

    /**
     * @param mixed $currentState
     *
     * @return bool
     */
    public function isInitialState($currentState): bool
    {
        assert(isset($this->graph[$currentState]));

        return $currentState === StateGraphWildcard::instance();
    }

// public function isTerminalState(): bool
// {
//     return empty($this->graph[$this->currentState]);
// }

// public function isTerminalState(): bool
    /**
     * @param mixed $currentState
     *
     * @return bool
     */
    public function isTerminalState($currentState): bool
    {
        assert(isset($this->graph[$currentState]));

        return empty($this->graph[$currentState]);
    }

// /**
//  * @param string $transitionName The name of the available transition.
//  *
//  * @return mixed The state after transition.
//  */
// public function findStateByTransition(string $transitionName)
// {
//     assert(isset($this->graph[$this->currentState][$name]));

//     return $this->graph[$this->currentState][$transitionName];
// }

    /**
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
     * @var SplObjectStorage<object, array<string, object>> The state graph to use.
     */
    private $graph;

    // /**
    //  * @var mixed|null The current transition state, or null if none.
    //  */
    // private $currentState;
}
