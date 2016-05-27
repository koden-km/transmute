<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use ArrayAccess;

final class StateMachine implements ArrayAccess
{
    /**
     * @param StateGraph  $graph         The graph of state transitions.
     * @param bool        $useObjectKeys True to use object keys for state logic map.
     * @param string|null $contextType   The type of data object accepted by the state logic implementation.
     */
    public function __construct(
        StateGraph $graph,
        bool $useObjectKeys,
        string $contextType = null
    ) {
        if ($useObjectKeys) {
            $stateMap = StateMap::createObjectMap();
        } else {
            $stateMap = StateMap::createArrayMap();
        }

        $this->graph = $graph;
        $this->logic = $stateMap;
        $this->contextType = $contextType;
    }

    /**
     * @param mixed $offset A state.
     *
     * @return mixed The state logic of the given state.
     */
    public function offsetGet($offset)
    {
        assert($this->logic->contains($offset));

        return $this->logic[$offset];
    }

    /**
     * @param mixed $offset A state.
     *
     * @return mixed The state logic of the given state.
     */
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
        assert(!$this->logic->contains($offset));

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
        assert($this->contextType === null || $context instanceof $this->contextType);
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
                    $transitioner->nextTransition
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
     * @var StateMap The logic implementations for each state.
     */
    private $logic;

    /**
     * @var string|null The type of data object accepted by the state logic implementation.
     */
    private $contextType;
}
