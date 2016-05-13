<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use SplObjectStorage;

final class StateGraph
{
    /**
     * @param SplObjectStorage $graph
     */
    public function __construct(SplObjectStorage $graph)
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

        return $currentState === StateGraphWildcard::instance();
    }

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
     * @var SplObjectStorage<object, array<string, object>> The state graph structure.
     */
    private $graph;
}
