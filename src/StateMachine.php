<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use ArrayAccess;
use SplObjectStorage;
use InvalidArgumentException;

final class StateMachine implements ArrayAccess
{
    /**
     * @param SplObjectStorage<object, array<string, object>> $graph       The graph of state transitions.
     * @param string|null                                     $contextType The type of data object accepted by the state logic implementation.
     */
    public function __construct(SplObjectStorage $graph, string $contextType = null)
    {
        $this->graph = $graph;
        $this->contextType = $contextType;
    }

    public function offsetGet($offset)
    {
        assert($this->logic->contains($offset));

        return $this->logic[$offset];
    }

    public function offsetExists($offset)
    {
        return $this->logic->contains($offset);
    }

    public function offsetSet($offset, $value)
    {
        assert($this->graph->contains($offset));

        $this->logic[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        $this->logic->offsetUnset($offset);
    }

    public function validate(...$states)
    {
        // assert(all states are mentioned either as intiial or target state - wildcard counts as mentioned)
        // assert(no states are mentioned that are not in $states)
        // assert that all states have associated logic, unless they are terminal states
    }

    public function update($currentState, $context)
    {
        assert($this->contextType === null || $object instanceof $this->contextType);
        assert($this->contextType !== null || $context === null);

        while (true) {
            $this->logic[$currentState]->update($this, $context);

            if ($this->nextTransition === null) {
                break;
            }

            try {
                $nextState = $this->findTargetState($currentState, $this->nextTransition);
            } finally {
                $this->nextTransition = null;
            }

            $this->logic[$currentState]->leave($this, $nextState, $context);
            $this->logic[$nextState]->enter($this, $currentState, $context);
            $currentState = $nextState;
        }
    }

    public function transition(string $name)
    {
        $this->nextTransition = $name;
    }

    public function __call($name, array $arguments)
    {
        $this->nextTransition = $name;
    }

    private function findTargetState($currentState, string $transitionName)
    {
        if ($this->transitions->contains[$currentState])) {
            $transitions = $this->transitions[$currentState];
            $state = $transitions[$transitionName] ?? null;

            if ($state !== null) {
                return $state;
            }
        }

        $currentState = StateGraphWildcard::instance();
        assert($this->transitions->contains[$currentState]));
        $transitions = $this->transitions[$currentState];
        assert($transitions[$transitionName] ?? false);

        return $transitions[$transitionName];
    }

    /**
     * @var SplObjectStorage<object, array<string, object>> The state graph to use.
     */
    private $graph;

    /**
     * @var SplObjectStorage<object, StateLogic> The logic implementations for each state.
     */
    private $logic;

    /**
     * @var string|null The type of data object accepted by the state logic implementation.
     */
    private $contextType;

    /**
     * @var string|null The name of the next transition to perform, if any.
     */
    private $nextTransition;
}
