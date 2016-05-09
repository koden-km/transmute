<?php

declare (strict_types = 1); // @codeCoverageIgnore

namespace Icecave\Transmute;

use InvalidArgumentException;

class StateMachine
{
    /**
     * @param StateGraph $graph      The graph to use.
     * @param mixed|null $objectType The type of object being state transitioned.
     */
    public function __construct(StateGraph $graph, $objectType = null) {
        $this->graph = $graph;
        $this->objectType = $objectType;
    }

    public function update($currentState, $object) {
        if (
            null !== $this->objectType
            && false === $this->objectType instanceof $object
        ) {
            throw new InvalidArgumentException(
                'Object is not of the required type.'
            );
        }

        $this->executor->update($currentState, $object);
    }

    /**
     * @var StateGraph The state graph to use.
     */
    private $graph;

    /**
     * @var StateExecutor The executor of the graph.
     */
    private $executor;

    /**
     * @var mixed The type of object being state transitioned.
     */
    private $objectType;
}
