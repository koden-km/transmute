<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use ArrayAccess;
use SplObjectStorage;

final class StateMachine implements ArrayAccess
{

// /**
//  * @param SplObjectStorage<object, array<string, object>> $graph       The graph of state transitions.
//  * @param string|null                                     $contextType The type of data object accepted by the state logic implementation.
//  */
// public function __construct(StateGraph $graph, string $contextType = null)
// {
//     $this->graph = $graph;
//     $this->contextType = $contextType;
// }

    /**
     * @param StateGraph  $graph       The graph of state transitions.
     * @param string|null $contextType The type of data object accepted by the state logic implementation.
     */
    public function __construct(StateGraph $graph, string $contextType = null)
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
        assert($this->contextType === null || $object instanceof $this->contextType);
        assert($this->contextType !== null || $context === null);

        $transitioner = new Transitioner($this);

        while (true) {
            // if ($this->graph->isTerminalState($currentState)) {
            //     break;
            // }

            $this->logic[$currentState]->update($transitioner, $context);

            if ($this->nextTransition === null) {
                break;
            }

            try {
                $nextState = $this->findTargetState($currentState, $this->nextTransition);
            } finally {
                $this->nextTransition = null;
            }

            // if (!$this->graph->isInitialState($currentState)) {
            // }

            $this->logic[$currentState]->leave($transitioner, $nextState, $context);
            $this->logic[$nextState]->enter($transitioner, $currentState, $context);
            $currentState = $nextState;
        }
    }

    /**
     * @param string $name The name of the transition to use.
     */
    public function transition(string $name)
    {
        $this->nextTransition = $name;
    }

// /**
//  * @param string $name      The name of the transition to use.
//  * @param array  $arguments The arguments (unused).
//  */
// public function __call(string $name, array $arguments)
// {
//     $this->nextTransition = $name;
// }

    /**
     * @param mixed  $currentState   The current state of the object.
     * @param string $transitionName The name of the transition to use.
     *
     * @return mixed
     */
    private function findTargetState($currentState, string $transitionName)
    {
        // if ($this->transitions->contains[$currentState]) {
        //     $transitions = $this->transitions[$currentState];
        //     $state = $transitions[$transitionName] ?? null;

        //     if ($state !== null) {
        //         return $state;
        //     }
        // }

        // $currentState = StateGraphWildcard::instance();
        // assert($this->transitions->contains[$currentState]);
        // $transitions = $this->transitions[$currentState];
        // assert($transitions[$transitionName] ?? false);

        // return $transitions[$transitionName];






        // if ($this->graph->contains($currentState)) {
        // }


        return $this->graph->findStateByTransition(
            $currentState,
            $transitionName
        );
    }

// /**
//  * @var SplObjectStorage<object, array<string, object>> The state graph to use.
//  */
// private $graph;

    /**
     * @var StateGraph The state graph to use.
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
