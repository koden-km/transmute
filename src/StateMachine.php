<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use ArrayAccess;
use SplObjectStorage;

final class StateMachine implements ArrayAccess
{
    /**
     * @param StateGraph  $graph       The graph of state transitions.
     * @param string|null $contextType The type of data object accepted by the state logic implementation.
     */
    public function __construct(StateGraph $graph, string $contextType = null)
    {
        $this->graph = $graph;
        $this->logic = new SplObjectStorage();  // TODO: change this to use the TransitionMap?
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

    /**
     * Bind a logic object to a given state.
     *
     * @param mixed $offset The state to bind to.
     * @param mixed $value  The logic object to bind.
     */
    public function offsetSet($offset, $value)
    {
        assert($this->logic->contains($offset));

        $this->logic[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        $this->logic->offsetUnset($offset);
    }

    public function validate(...$states)
    {
        // TODO
        // assert(all states are mentioned either as intiial or target state - wildcard counts as mentioned)
        // assert(no states are mentioned that are not in $states)
        // assert that all states have associated logic, unless they are terminal states
    }

    /**
     * @param mixed $currentState The current state of the object.
     * @param mixed $context      The object having state updated.
     */
    public function update($currentState, $context)
    {
        assert($this->contextType === null || $currentState instanceof $this->contextType);
        assert($this->contextType !== null || $context === null);

        $transitioner = new Transitioner($this);

        while (true) {
            if ($this->graph->isTerminalState($currentState)) {
                break;
            }

            $this->logic[$currentState]->update($transitioner, $context);

            if ($transitioner->nextTransition === null) {
                break;
            }

            try {
                $nextState = $this->graph->findStateByTransition(
                    $currentState,
                    $transitionName
                );
            } finally {
                $transitioner->nextTransition = null;
            }

            // TODO: what to do on initial state?
            // if (!$this->graph->isInitialState($currentState)) {
            //     // anything to do or not do?
            // }

            $this->logic[$currentState]->leave($transitioner, $nextState, $context);
            $this->logic[$nextState]->enter($transitioner, $currentState, $context);
            $currentState = $nextState;
        }
    }

    /**
     * @var StateGraph The graph of state transitions.
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
}
